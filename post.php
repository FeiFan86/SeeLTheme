<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
// 增加文章浏览量
incrementViews($this->cid);
include 'header.php';
?>

<div class="container">
    <div class="main-layout">
        <div class="main-content">
            <article class="post-full">
                <!-- 文章标题区 -->
                <div class="post-header">
                    <div class="post-category-tag"><?php $this->category(); ?></div>
                    <h1 class="post-main-title"><?php $this->title(); ?></h1>
                    <div class="post-meta-info">
                        <span>📅 <?php $this->date('Y年m月d日'); ?></span>
                        <span>👁 <?php echo getViews($this); ?></span>
                        <span>💬 <?php $this->commentsNum(); ?></span>
                        <span>⏱️ <?php echo getReadingTime($this->content); ?></span>
                    </div>
                </div>

                <!-- 文章目录 -->
                <?php if ($this->options->enableToc == '1'): ?>
                <div class="post-toc toc-fixed" id="postToc">
                    <h4>📋 目录</h4>
                    <ul id="tocList"></ul>
                </div>
                <?php endif; ?>

                <!-- 文章正文 -->
                <div class="post-content-full">
                    <!-- 代码高亮库 -->
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>


                    <!-- 广告 - 开头 -->
                    <?php if ($this->options->enableContentAd == '1' && $this->options->contentAdPosition == 'before'): ?>
                    <div class="content-ad">
                        <?php echo $this->options->contentAdCode; ?>
                    </div>
                    <?php endif; ?>




                    <?php $this->content(); ?>

                    <!-- 广告 - 结尾 -->
                    <?php if ($this->options->enableContentAd == '1' && ($this->options->contentAdPosition == 'after' || $this->options->contentAdPosition == 'middle')): ?>
                    <div class="content-ad">
                        <?php echo $this->options->contentAdCode; ?>
                    </div>
                    <?php endif; ?>




                    <!-- 网盘下载信息 -->
                    <?php if ($this->options->enableNetdisk == '1' && $this->fields->netdiskInfo): ?>
                    <?php
                        // 网盘类型映射
                        $netdiskNames = array(
                            'baidu' => '百度网盘',
                            'aliyun' => '阿里云盘',
                            'tencent' => '腾讯微云',
                            'lanzou' => '蓝奏云',
                            'kuake' => '夸克网盘',
                            'uc' => 'UC网盘',
                            '123pan' => '123网盘',
                            'other' => '其他网盘'
                        );

                        // 解析网盘信息
                        $netdiskData = array();
                        $lines = explode("\n", trim($this->fields->netdiskInfo));

                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (empty($line)) continue;

                            $parts = explode('|', $line);
                            if (count($parts) >= 2) {
                                $type = trim($parts[0]);
                                $url = trim($parts[1]);

                                // 智能解析提取码和说明
                                $code = '';
                                $note = '';

                                if (count($parts) >= 4) {
                                    // 4个字段: 类型|链接|提取码|说明
                                    $code = trim($parts[2]);
                                    $note = trim($parts[3]);
                                } elseif (count($parts) == 3) {
                                    // 3个字段，需要判断是提取码还是说明
                                    $field3 = trim($parts[2]);
                                    // 判断是否是提取码：4-8位字母数字组合
                                    if (preg_match('/^[a-zA-Z0-9]{4,8}$/', $field3)) {
                                        $code = $field3;
                                        $note = '';
                                    } else {
                                        $code = '';
                                        $note = $field3;
                                    }
                                }

                                $netdiskData[] = array(
                                    'type' => $type,
                                    'url' => $url,
                                    'code' => $code,
                                    'note' => $note
                                );
                            }
                        }

                        // 网盘图标映射
                        $netdiskIcons = array(
                            'baidu' => '🔷',
                            'aliyun' => '🔶',
                            'tencent' => '🔵',
                            'lanzou' => '🟢',
                            'kuake' => '🟣',
                            'uc' => '🟠',
                            '123pan' => '🔴',
                            'other' => '☁️'
                        );
                    ?>

                    <?php if (!empty($netdiskData)): ?>
                    <div class="netdisk-grid">
                        <?php foreach ($netdiskData as $index => $netdisk): ?>
                        <?php
                            $displayName = isset($netdiskNames[$netdisk['type']]) ? $netdiskNames[$netdisk['type']] : '网盘';
                            $icon = isset($netdiskIcons[$netdisk['type']]) ? $netdiskIcons[$netdisk['type']] : '☁️';
                            $hasCode = !empty($netdisk['code']);
                            $hasNote = !empty($netdisk['note']);
                        ?>
                        <div class="netdisk-card">
                            <!-- 网盘头部 -->
                            <div class="netdisk-card-header">
                                <div class="netdisk-logo">
                                    <img src="<?php echo $this->options->themeUrl; ?>/img/<?php
                                        if ($netdisk['type'] == 'baidu') echo '百度网盘.svg';
                                        elseif ($netdisk['type'] == 'aliyun') echo '阿里云盘.svg';
                                        elseif ($netdisk['type'] == 'tencent') echo '腾讯微云.svg';
                                        elseif ($netdisk['type'] == 'lanzou') echo '蓝奏云盘.svg';
                                        elseif ($netdisk['type'] == 'kuake') echo '夸克.svg';
                                        elseif ($netdisk['type'] == 'uc') echo 'UC浏览器.svg';
                                        elseif ($netdisk['type'] == '123pan') echo '123网盘.svg';
                                        else echo '网盘.svg';
                                    ?>" alt="<?php echo $displayName; ?>" class="netdisk-icon-img">
                                </div>
                                <div class="netdisk-info">
                                    <div class="netdisk-name"><?php echo $displayName; ?></div>
                                    <div class="netdisk-note"><?php echo $hasNote ? htmlspecialchars($netdisk['note']) : '网盘下载'; ?></div>
                                </div>
                            </div>

                            <!-- 提取码 -->
                            <div class="netdisk-code-row">
                                <span class="code-label">提取码</span>
                                <?php if ($hasCode): ?>
                                <span class="code-value" onclick="copyNetdiskCode(this)"><?php echo htmlspecialchars($netdisk['code']); ?></span>
                                <span class="copy-hint">点击复制</span>
                                <?php else: ?>
                                <span class="code-empty">无提取码</span>
                                <?php endif; ?>
                            </div>

                            <!-- 下载链接 -->
                            <a href="<?php echo htmlspecialchars($netdisk['url']); ?>"
                               class="netdisk-link-btn"
                               target="_blank"
                               rel="noopener noreferrer">
                                <span>立即下载</span>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- 文章底部信息 -->
                <div class="post-footer">
                    <!-- 打赏 -->
                    <?php if ($this->options->enableDonate == '1' && ($this->options->donateWechat || $this->options->donateAlipay)): ?>
                    <div class="post-donate">
                        <h4>❤️ 打赏支持</h4>
                        <p>如果这篇文章对你有帮助，欢迎打赏支持！</p>
                        <div class="donate-qr-codes">
                            <?php if ($this->options->donateWechat): ?>
                            <div class="qr-code">
                                <img src="<?php echo $this->options->donateWechat; ?>" alt="微信支付">
                                <span>微信</span>
                            </div>
                            <?php endif; ?>




                            <?php if ($this->options->donateAlipay): ?>
                            <div class="qr-code">
                                <img src="<?php echo $this->options->donateAlipay; ?>" alt="支付宝">
                                <span>支付宝</span>
                            </div>
                            <?php endif; ?>




                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 标签 -->
                    <?php if ($this->tags): ?>
                    <div class="post-tags-section">
                        <span class="tags-label">🏷️ 标签：</span>
                        <div class="post-tags">
                            <?php $this->tags('', true, ''); ?>
                        </div>
                    </div>
                    <?php endif; ?>






                    <!-- 分享 -->
                    <?php if ($this->options->enableShare == '1'): ?>
                    <div class="post-share-section">
                        <span class="share-label">📤 分享：</span>
                        <div class="post-share-buttons">
                            <?php
                            $platforms = $this->options->sharePlatforms ? $this->options->sharePlatforms : 'weibo,qq,wechat,twitter,link';
                            $platformArray = explode(',', $platforms);

                            $shareButtons = array(
                                'weibo' => array('name' => '微博', 'action' => 'shareToWeibo'),
                                'qq' => array('name' => 'QQ', 'action' => 'shareToQQ'),
                                'wechat' => array('name' => '微信', 'action' => 'shareToWechat'),
                                'twitter' => array('name' => 'Twitter', 'action' => 'shareToTwitter'),
                                'link' => array('name' => '复制链接', 'action' => 'copyLink'),
                            );

                            foreach ($platformArray as $platform):
                                $platform = trim($platform);
                                if (isset($shareButtons[$platform])):
                            ?>
                                <button class="share-btn" onclick="<?php echo $shareButtons[$platform]['action']; ?>()">
                                    <?php echo $shareButtons[$platform]['name']; ?>
                                </button>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>



                </div>



                <!-- 版权声明 -->
                <?php if ($this->options->enableCopyright == '1'): ?>
                <div class="post-copyright">
                    <div class="copyright-icon">©</div>
                    <div class="copyright-content">

                        <p><?php echo $this->options->copyrightText ? $this->options->copyrightText : '本文为原创文章，未经作者许可禁止转载'; ?></p>
                        <div class="copyright-meta">
                            <span><strong>作者：</strong><?php $this->author(); ?></span>
                            <span><strong>日期：</strong><?php $this->date('Y-m-d'); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>






                <!-- 上一篇/下一篇 -->
                <nav class="post-navigation">
                    <?php
                    // 获取上一篇
                    $prevLink = '';
                    $prevTitle = '';
                    ob_start();
                    $this->thePrev('%s');
                    $prevOutput = ob_get_clean();
                    if (!empty($prevOutput)) {
                        if (preg_match('/href="([^"]+)"/', $prevOutput, $matches)) {
                            $prevLink = $matches[1];
                        }
                        if (preg_match('/title="([^"]+)"/', $prevOutput, $matches)) {
                            $prevTitle = $matches[1];
                        }
                    }

                    // 获取下一篇
                    $nextLink = '';
                    $nextTitle = '';
                    ob_start();
                    $this->theNext('%s');
                    $nextOutput = ob_get_clean();
                    if (!empty($nextOutput)) {
                        if (preg_match('/href="([^"]+)"/', $nextOutput, $matches)) {
                            $nextLink = $matches[1];
                        }
                        if (preg_match('/title="([^"]+)"/', $nextOutput, $matches)) {
                            $nextTitle = $matches[1];
                        }
                    }
                    ?>

                    <?php if (!empty($prevLink)): ?>
                    <a href="<?php echo htmlspecialchars($prevLink); ?>" class="nav-card prev">
                        <div class="nav-icon">←</div>
                        <div class="nav-info">
                            <span class="nav-label">上一篇</span>
                            <span class="nav-title"><?php echo htmlspecialchars($prevTitle); ?></span>
                        </div>
                    </a>
                    <?php else: ?>
                    <div class="nav-card prev disabled">
                        <div class="nav-icon">←</div>
                        <div class="nav-info">
                            <span class="nav-label">上一篇</span>
                            <span class="nav-title">没有上一篇</span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($nextLink)): ?>
                    <a href="<?php echo htmlspecialchars($nextLink); ?>" class="nav-card next">
                        <div class="nav-icon">→</div>
                        <div class="nav-info">
                            <span class="nav-label">下一篇</span>
                            <span class="nav-title"><?php echo htmlspecialchars($nextTitle); ?></span>
                        </div>
                    </a>
                    <?php else: ?>
                    <div class="nav-card next disabled">
                        <div class="nav-icon">→</div>
                        <div class="nav-info">
                            <span class="nav-label">下一篇</span>
                            <span class="nav-title">没有下一篇</span>
                        </div>
                    </div>
                    <?php endif; ?>
                </nav>
            </article>

<!-- 代码复制功能 -->




<!-- 代码复制功能 -->




            <!-- 评论区 -->
            <?php include 'comments.php'; ?>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <?php include 'sidebar.php'; ?>
        </aside>
    </div>
</div>

<?php
// 文章目录 JavaScript
if ($this->options->enableToc == '1'):
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toc = document.getElementById('tocList');
    const postToc = document.getElementById('postToc');
    const headings = document.querySelectorAll('.post-content-full h1, .post-content-full h2, .post-content-full h3, .post-content-full h4');

    if (!toc) {
        if (postToc) {
            postToc.style.display = 'none';
        }
        return;
    }

    if (headings.length > 0) {
        headings.forEach((heading, index) => {
            const id = 'heading-' + index;
            heading.id = id;

            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#' + id;
            a.textContent = heading.textContent;
            const tagName = heading.tagName.toLowerCase();
            if (tagName === 'h1') {
                a.className = 'toc-h1';
            } else if (tagName === 'h2') {
                a.className = 'toc-h2';
            } else {
                a.className = 'toc-h3';
            }

            li.appendChild(a);
            toc.appendChild(li);
        });
    } else {
        if (postToc) {
            postToc.style.display = 'none';
        }
    }
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.post-toc a')) {
        e.preventDefault();
        const targetId = e.target.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            const offset = 80;
            const bodyRect = document.body.getBoundingClientRect().top;
            const elementRect = targetElement.getBoundingClientRect().top;
            const elementPosition = elementRect - bodyRect;
            const offsetPosition = elementPosition - offset;

            var scrollOptions = {
                top: offsetPosition,
                behavior: 'smooth'
            };
            window.scrollTo(scrollOptions);
        }
    }
});
</script>
<?php endif; ?>



<!-- 代码高亮初始化 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 为所有代码块添加行号
    const codeBlocks = document.querySelectorAll('.post-content-full pre');
    codeBlocks.forEach((codeBlock, index) => {
        // 获取或创建code元素
        let codeElement = codeBlock.querySelector('code');
        if (!codeElement) {
            codeElement = document.createElement('code');
            codeElement.innerHTML = codeBlock.innerHTML;
            codeBlock.innerHTML = '';
            codeBlock.appendChild(codeElement);
        }

        // 添加语言类名（如果指定了）
        const classList = codeElement.className.split(' ');
        codeElement.className = classList.join(' ');

        // 调用Prism高亮
        if (typeof Prism !== 'undefined') {
            Prism.highlightElement(codeElement);
        }
    });

    // 为H1添加特殊样式
    const h1Elements = document.querySelectorAll('.post-main-title');
    h1Elements.forEach(h1 => {
        h1.classList.add('main-heading');
    });
});
</script>





<!-- 社交分享 -->
<script>
window.shareToWeibo = function() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.querySelector('.post-main-title').textContent);
    window.open(`https://service.weibo.com/share/share.php?url=${url}&title=${title}`, '_blank');
};

window.shareToQQ = function() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.querySelector('.post-main-title').textContent);
    window.open(`https://connect.qq.com/widget/shareqq/index.html?url=${url}&title=${title}`, '_blank');
};

window.shareToWechat = function() {
    alert('请使用微信扫描二维码分享');
};

window.shareToTwitter = function() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(document.querySelector('.post-main-title').textContent);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
};

window.copyLink = function() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('链接已复制到剪贴板！');
    });
};
</script>





<!-- 代码复制功能 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 为所有代码块添加复制按钮
    const codeBlocks = document.querySelectorAll('.post-content-full pre[class*="language-"]');

    codeBlocks.forEach((preBlock, index) => {
        // 创建复制按钮
        const copyButton = document.createElement('button');
        copyButton.className = 'copy-code-button';
        copyButton.textContent = '复制';
        copyButton.setAttribute('aria-label', '复制代码');

        // 将按钮插入到代码块中
        preBlock.style.position = 'relative';
        preBlock.appendChild(copyButton);

        // 点击事件：复制代码
        copyButton.addEventListener('click', function(e) {
            e.stopPropagation();
            const codeElement = preBlock.querySelector('code');
            const codeText = codeElement ? codeElement.textContent : preBlock.textContent;

            navigator.clipboard.writeText(codeText).then(() => {
                // 复制成功反馈
                const originalText = copyButton.textContent;
                copyButton.textContent = '已复制';
                copyButton.classList.add('copied');

                // 2秒后恢复
                setTimeout(() => {
                    copyButton.textContent = originalText;
                    copyButton.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('复制失败:', err);
                copyButton.textContent = '复制失败';
                setTimeout(() => {
                    copyButton.textContent = '复制';
                }, 2000);
            });
        });
    });
});
</script>

<!-- 网盘复制功能 -->
<script>
// 复制提取码
function copyNetdiskCode(element) {
    const text = element.textContent;
    const hint = element.parentElement.querySelector('.copy-hint');

    navigator.clipboard.writeText(text).then(() => {
        // 显示复制成功提示
        const originalText = hint.textContent;
        hint.textContent = '已复制 ✓';
        hint.style.color = '#10b981';

        setTimeout(() => {
            hint.textContent = originalText;
            hint.style.color = '';
        }, 1500);
    }).catch(err => {
        console.error('复制失败:', err);
        alert('复制失败，请手动复制');
    });
}
</script>

<?php include 'footer.php'; ?>
