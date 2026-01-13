<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');
while (@ob_end_clean());

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_COMPILE_ERROR)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('success' => false, 'message' => 'PHP致命错误: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']), JSON_UNESCAPED_UNICODE);
    }
});

// 检查是否是测试请求
if (isset($_GET['test'])) {
    echo json_encode(array('success' => true, 'message' => 'import.php正常工作', 'time' => date('Y-m-d H:i:s')), JSON_UNESCAPED_UNICODE);
    exit;
}

// 检查POST请求
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(array('success' => false, 'message' => '只接受POST请求。'), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_FILES['settings_file']) || !isset($_POST['import_settings'])) {
    echo json_encode(array('success' => false, 'message' => '无效的请求参数。'), JSON_UNESCAPED_UNICODE);
    exit;
}

$file = $_FILES['settings_file'];
$response = array('success' => false, 'message' => '');

// 检查上传错误
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = array(
        UPLOAD_ERR_INI_SIZE => '上传的文件大小超过了服务器限制。',
        UPLOAD_ERR_FORM_SIZE => '上传的文件大小超过了表单限制。',
        UPLOAD_ERR_PARTIAL => '文件只有部分被上传。',
        UPLOAD_ERR_NO_FILE => '没有文件被上传。',
        UPLOAD_ERR_NO_TMP_DIR => '缺少临时文件夹。',
        UPLOAD_ERR_CANT_WRITE => '写入文件到磁盘失败。',
        UPLOAD_ERR_EXTENSION => '文件上传被扩展阻止。'
    );
    $response['message'] = isset($errorMessages[$file['error']]) ? $errorMessages[$file['error']] : '文件上传错误：' . $file['error'];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 检查文件类型
elseif ($file['type'] !== 'application/json' && pathinfo($file['name'], PATHINFO_EXTENSION) !== 'json') {
    $response['message'] = '只支持 JSON 格式的文件。';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 检查文件大小
elseif ($file['size'] > 2 * 1024 * 1024) {
    $response['message'] = '文件大小不能超过 2MB。';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 读取并解析 JSON
$json = file_get_contents($file['tmp_name']);
if ($json === false) {
    $response['message'] = '无法读取上传的文件。';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$settings = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $response['message'] = 'JSON 解析错误：' . json_last_error_msg();
    $response['json_error'] = json_last_error();
    $response['json_content'] = substr($json, 0, 1000);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 连接数据库
$possiblePaths = array(
    '../../../config.inc.php',  // 相对路径
    '../../../../config.inc.php',
    dirname(dirname(dirname(dirname(__FILE__)))) . '/config.inc.php',
    dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.inc.php'
);

$found = false;
$configPath = '';
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $configPath = $path;
        $found = true;
        break;
    }
}

if (!$found) {
    $response['message'] = '无法找到数据库配置文件。尝试的路径: ' . implode(', ', $possiblePaths);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$configContent = file_get_contents($configPath);

// 解析Typecho配置 - 支持多种格式
$configStr = '';
$matches = array();
$dbConfig = array(); // 初始化配置数组

// 尝试匹配 Db::set(array(...)) 格式（老格式）
if (preg_match('/Db::set\s*\(\s*array\s*\(([^)]*(?:\([^)]*\)[^)]*)*)\)\s*\)/s', $configContent, $matches)) {
    $configStr = $matches[0];
}
// 尝试匹配 Db::set([...]) 格式 (PHP 7.4+)
elseif (preg_match('/Db::set\s*\(\s*\[([^\]]*(?:\[[^\]]*\][^\]]*)*)\]\s*\)/s', $configContent, $matches)) {
    $configStr = $matches[0];
}
// 尝试匹配 new \Typecho\Db(...) 格式（新格式）
elseif (preg_match('/new\s*\\\\?Typecho\\\\?Db\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]/', $configContent, $matches)) {
    // 提取 adapter 和 prefix
    $dbConfig['adapter'] = $matches[1];
    $dbConfig['prefix'] = $matches[2];
    $configStr = $configContent; // 保存完整配置用于其他字段提取
}
// 如果上述都失败，尝试直接解析整个文件
else {
    $configStr = $configContent;
}

if (empty($configStr)) {
    $response['message'] = '无法解析数据库配置。';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 提取数据库配置 - 改进的正则表达式
// $dbConfig 已经在前面初始化了，不需要再次初始化

// 尝试匹配 'host' => 'value' 或 "host" => 'value' 格式
$patterns = array(
    'host' => array("/['\"]host['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/host\\s*=\\s*['\"]([^'\"]+)['\"]/"),
    'user' => array("/['\"]user['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/user\\s*=\\s*['\"]([^'\"]+)['\"]/", "/['\"]username['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/"),
    'pass' => array("/['\"]pass['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/pass\\s*=\\s*['\"]([^'\"]+)['\"]/", "/['\"]password['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/['\"]pwd['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/"),
    'name' => array("/['\"]database['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/['\"]name['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/database\\s*=\\s*['\"]([^'\"]+)['\"]/", "/name\\s*=\\s*['\"]([^'\"]+)['\"]/", "/['\"]db['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/"),
    'port' => array("/['\"]port['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/port\\s*=\\s*['\"]([^'\"]+)['\"]/", "/[:\s]port\\s*=\\s*([0-9]+)/"),
    'charset' => array("/['\"]charset['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/charset\\s*=\\s*['\"]([^'\"]+)['\"]/", "/[:\s]charset\\s*=\\s*['\"]?([^',\")\s]+)['\"]?/"),
    'adapter' => array("/['\"]adapter['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/adapter\\s*=\\s*['\"]([^'\"]+)['\"]/", "/['\"]type['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/type\\s*=\\s*['\"]([^'\"]+)['\"]/"),
    'prefix' => array("/['\"]prefix['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/", "/prefix\\s*=\\s*['\"]([^'\"]+)['\"]/", "/['\"]tablePrefix['\"]\\s*=>\\s*['\"]([^'\"]+)['\"]/")
);

foreach ($patterns as $key => $patternList) {
    // 如果这个字段已经存在（例如从新格式提取的），就跳过
    if (isset($dbConfig[$key])) {
        continue;
    }
    foreach ($patternList as $pattern) {
        if (preg_match($pattern, $configStr, $m)) {
            $dbConfig[$key] = $m[1];
            break;
        }
    }
}

if (empty($dbConfig['name']) || empty($dbConfig['adapter'])) {
    // 返回更详细的错误信息，帮助调试
    $response['message'] = '无法提取数据库配置。请检查配置文件格式。';
    $response['config_preview'] = substr($configStr, 0, 500); // 显示部分配置内容
    $response['extracted_config'] = $dbConfig; // 显示已提取的配置
    $response['config_path'] = $configPath; // 显示找到的配置文件路径
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $dbConfig['host'] = isset($dbConfig['host']) ? $dbConfig['host'] : 'localhost';
    $dbConfig['port'] = isset($dbConfig['port']) ? $dbConfig['port'] : '3306';
    $dbConfig['name'] = isset($dbConfig['name']) ? $dbConfig['name'] : 'typecho';
    $dbConfig['user'] = isset($dbConfig['user']) ? $dbConfig['user'] : 'root';
    $dbConfig['pass'] = isset($dbConfig['pass']) ? $dbConfig['pass'] : '';
    $dbConfig['charset'] = isset($dbConfig['charset']) ? $dbConfig['charset'] : 'utf8';
    $dbConfig['adapter'] = isset($dbConfig['adapter']) ? $dbConfig['adapter'] : 'Pdo_Mysql';
    $dbConfig['prefix'] = isset($dbConfig['prefix']) ? $dbConfig['prefix'] : 'typecho_';

    $dsn = '';
    if (strpos($dbConfig['adapter'], 'Mysql') !== false || strpos($dbConfig['adapter'], 'MYSQL') !== false) {
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}";
    } elseif (strpos($dbConfig['adapter'], 'Pgsql') !== false || strpos($dbConfig['adapter'], 'PGSQL') !== false) {
        $dsn = "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['name']}";
    } elseif (strpos($dbConfig['adapter'], 'Sqlite') !== false || strpos($dbConfig['adapter'], 'SQLITE') !== false) {
        $dsn = "sqlite:{$dbConfig['name']}";
    } else {
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}";
    }

    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 开始事务
    $pdo->beginTransaction();

    $updates = array();
    $successCount = 0;

    foreach ($settings as $key => $value) {
        if ($key !== 'exportDate' && $value !== null) {
            $updates[$key] = $value;
        }
    }

    $tableName = $dbConfig['prefix'] . 'options';

    if (!empty($updates)) {
        // 先读取并更新 theme:SeeLTheme 序列化数组（在事务开始前）
        $stmt = $pdo->prepare("SELECT value FROM {$tableName} WHERE name = ?");
        $stmt->execute(array('theme:SeeLTheme'));
        $result = $stmt->fetch();

        $themeData = array();
        if ($result) {
            $themeData = unserialize($result['value']);
            if ($themeData === false) {
                $themeData = array();
            }
        }

        foreach ($updates as $key => $value) {
            // 处理数组类型：sidebarHomeWidgets 和 sidebarOtherWidgets
            $isWidgetOption = in_array($key, array('sidebarHomeWidgets', 'sidebarOtherWidgets'));
            if ($isWidgetOption) {
                if (is_array($value)) {
                    $value = serialize($value);
                }
            }

            // 使用正确的主题名前缀
            $optionName = 'theme:' . $key;

            // 检查是否存在
            $stmt = $pdo->prepare("SELECT value FROM {$tableName} WHERE name = ?");
            $stmt->execute(array($optionName));
            $exists = $stmt->fetch();

            if ($exists) {
                // 更新
                $stmt = $pdo->prepare("UPDATE {$tableName} SET value = ? WHERE name = ?");
                $stmt->execute(array($value, $optionName));
            } else {
                // 插入
                $stmt = $pdo->prepare("INSERT INTO {$tableName} (name, value, user) VALUES (?, ?, 0)");
                $stmt->execute(array($optionName, $value));
            }

            // 同时更新 themeData（保持原始数组格式）
            if ($isWidgetOption) {
                // 对于数组类型，从 $updates 中取原始数组
                $themeData[$key] = $updates[$key];
            } else {
                $themeData[$key] = $value;
            }
            $successCount++;
        }

        // 更新 theme:SeeLTheme 序列化数组（在同一个事务中）
        $serializedValue = serialize($themeData);
        $stmt = $pdo->prepare("UPDATE {$tableName} SET value = ? WHERE name = ?");
        $stmt->execute(array($serializedValue, 'theme:SeeLTheme'));
    }

    // 提交事务（一次性提交所有更改）
    $pdo->commit();

        // 验证导入结果 - 随机抽查几个选项
        $checkKeys = array_slice(array_keys($updates), 0, 3);
        $verified = array();
        foreach ($checkKeys as $key) {
            $stmt = $pdo->prepare("SELECT value FROM {$tableName} WHERE name = ?");
            $stmt->execute(array('theme:' . $key));
            $result = $stmt->fetch();
            $verified[$key] = $result ? $result['value'] : 'NOT FOUND';
        }

        $response['success'] = true;
        $response['message'] = '主题设置导入成功！共导入 ' . $successCount . ' 项设置。';
        $response['count'] = $successCount;
        $response['verified'] = $verified; // 返回验证结果
        $response['hint'] = '如果页面没有更新，请按 Ctrl+F5 强制刷新浏览器缓存。';

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response['message'] = '数据库操作失败：' . $e->getMessage();
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response['message'] = '导入失败：' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
