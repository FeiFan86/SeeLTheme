<?php
// 直接输出JSON响应头，绕过Typecho路由
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="seel-theme-settings-' . date('Y-m-d-H-i-s') . '.json"');

// 禁用输出缓冲
while (@ob_end_clean());

// 尝试加载Typecho配置
if (!defined('__TYPECHO_ROOT_DIR__')) {
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
            define('__TYPECHO_ROOT_DIR__', dirname($configPath));
            @include $configPath;
            break;
        }
    }

    if (!$found) {
        echo json_encode(array('error' => '无法访问数据库配置文件。尝试的路径: ' . implode(', ', $possiblePaths)), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 加载Typecho核心类
    $typechoLib = __TYPECHO_ROOT_DIR__ . '/var/Typecho';
    if (file_exists($typechoLib)) {
        set_include_path(get_include_path() . PATH_SEPARATOR . $typechoLib);
        require_once $typechoLib . '/Widget.php';
        require_once $typechoLib . '/Config.php';
    }
}

try {
    $options = Typecho_Widget::widget('Widget_Options');
    $settings = array(
        'logoUrl' => $options->logoUrl,
        'faviconUrl' => $options->faviconUrl,
        'siteDescription' => $options->siteDescription,
        'defaultTheme' => $options->defaultTheme,
        'darkModeTime' => $options->darkModeTime,
        'enableAnnouncement' => $options->enableAnnouncement,
        'announcementText' => $options->announcementText,
        'announcementClose' => $options->announcementClose,
        'enableProgress' => $options->enableProgress,
        'enableToc' => $options->enableToc,

        'enableShare' => $options->enableShare,
        'sharePlatforms' => $options->sharePlatforms,
        'enableCopyright' => $options->enableCopyright,
        'copyrightText' => $options->copyrightText,
        'enableDonate' => $options->enableDonate,
        'donateWechat' => $options->donateWechat,
        'donateAlipay' => $options->donateAlipay,
        'enableBackToTop' => $options->enableBackToTop,
        'hotPostsCount' => $options->hotPostsCount,
        'latestCommentsCount' => $options->latestCommentsCount,
        'tagsCloudCount' => $options->tagsCloudCount,
        'friendLinks' => $options->friendLinks,
        'sidebarHomeWidgets' => $options->sidebarHomeWidgets,
        'sidebarOtherWidgets' => $options->sidebarOtherWidgets,

        'enableNetdisk' => $options->enableNetdisk,
        'enableSidebarAd' => $options->enableSidebarAd,
        'sidebarAdCode' => $options->sidebarAdCode,
        'sidebarAdPosition' => $options->sidebarAdPosition,
        'enableContentAd' => $options->enableContentAd,
        'contentAdCode' => $options->contentAdCode,
        'contentAdPosition' => $options->contentAdPosition,
        'customNav' => $options->customNav,
        'navStyle' => $options->navStyle,
        'socialGithub' => $options->socialGithub,
        'socialWeibo' => $options->socialWeibo,
        'socialWechat' => $options->socialWechat,
        'socialWechatQr' => $options->socialWechatQr,
        'socialEmail' => $options->socialEmail,
        'analyticsCode' => $options->analyticsCode,
        'customCss' => $options->customCss,
        'customJs' => $options->customJs,
        'exportDate' => date('Y-m-d H:i:s')
    );

    echo json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(array('error' => '导出失败：' . $e->getMessage()), JSON_UNESCAPED_UNICODE);
}

exit;
