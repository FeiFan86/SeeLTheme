    <!-- 主题切换按钮 -->
    <div class="theme-switcher-fixed">
        <button class="theme-switch-btn-fixed" onclick="window.toggleThemeMenu && window.toggleThemeMenu()" id="themeToggleBtnFixed">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 8 6.5 8 8 8.67 8 9.5 7.33 11 6.5 11zm3-4C8.67 7 8 6.33 8 5.5S8.67 4 9.5 4s1.5.67 1.5 1.5S10.33 7 9.5 7zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 4 14.5 4s1.5.67 1.5 1.5S15.33 7 14.5 7zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 8 17.5 8s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
            </svg>
        </button>
        <div class="theme-dropdown-fixed" id="themeDropdownFixed">
            <div class="theme-group-title">主题</div>
            <div class="theme-option" onclick="window.switchTheme && window.switchTheme('v12')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
                <span class="theme-name">简洁化主题</span>
            </div>
            <div class="theme-option" onclick="window.switchTheme && window.switchTheme('v7')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
                <span class="theme-name">玻璃态主题</span>
            </div>
            <div class="theme-group-title">模式</div>
            <div class="theme-option" onclick="window.toggleMode && window.toggleMode()" id="modeSwitchBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 18 12a9 9 0 0 0 9-9c.52 0 1.03.06 1.5.17"/>
                    <path d="M21 12.79a9 9 0 0 1-9 9c-.52 0-1.03-.06-1.5-.17"/>
                </svg>
                <span class="theme-name" id="modeText">暗黑模式</span>
            </div>
        </div>
    </div>

    <!-- 返回顶部按钮 -->
    <?php if ($this->options->enableBackToTop == '1'): ?>
    <button class="back-to-top" id="backToTop" onclick="window.scrollToTop && window.scrollToTop()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>
    <?php endif; ?>

    <!-- Footer -->
</div>
<footer>
    <div class="footer-content">
        <!-- 社交媒体链接 -->
        <div class="footer-social">
            <?php if ($this->options->socialGithub): ?>
            <a href="<?php echo $this->options->socialGithub; ?>" target="_blank" rel="noopener" class="social-link social-github" aria-label="GitHub">
                <img src="<?php $this->options->themeUrl('img/GitHub.svg'); ?>" alt="GitHub" class="social-icon">
            </a>
            <?php endif; ?>

            <?php if ($this->options->socialWeibo): ?>
            <a href="<?php echo $this->options->socialWeibo; ?>" target="_blank" rel="noopener" class="social-link social-weibo" aria-label="微博">
                <img src="<?php $this->options->themeUrl('img/新浪微博.svg'); ?>" alt="微博" class="social-icon">
            </a>
            <?php endif; ?>

            <?php if ($this->options->socialWechat): ?>
            <div class="social-link social-wechat" onclick="showWechatModal()" aria-label="微信">
                <img src="<?php $this->options->themeUrl('img/微信.svg'); ?>" alt="微信" class="social-icon">
            </div>
            <?php endif; ?>

            <?php if ($this->options->socialEmail): ?>
            <a href="mailto:<?php echo $this->options->socialEmail; ?>" class="social-link social-email" aria-label="Email">
                <img src="<?php $this->options->themeUrl('img/邮箱.svg'); ?>" alt="邮箱" class="social-icon">
            </a>
            <?php endif; ?>
        </div>

        <!-- 底部信息 -->
        <div class="footer-info">
            <p class="footer-copyright">
                &copy; <?php echo date('Y'); ?> <?php $this->options->title(); ?>.
                <span class="separator">|</span>
                Powered by <a href="https://typecho.org" target="_blank" rel="noopener">Typecho</a>
                <span class="separator">|</span>
                Theme <a href="https://github.com/FeiFan86/SeeLTheme" target="_blank" rel="noopener">SeeLTheme</a>
            </p>
            <?php if ($this->options->icp): ?>
            <p class="footer-icp">
                <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h2v2h-2v-2zm0-8h2v6h-2V9zm1-5c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6z"/>
                    </svg>
                    <?php $this->options->icp(); ?>
                </a>
            </p>
            <?php endif; ?>
        </div>
    </div>
</footer>

<!-- 自定义 JavaScript -->
<?php if ($this->options->customJs): ?>
<script>
<?php $this->options->customJs(); ?>
</script>
<?php endif; ?>

<!-- 自定义 CSS -->
<?php if ($this->options->customCss): ?>
<style>
<?php $this->options->customCss(); ?>
</style>
<?php endif; ?>

<!-- 微信二维码弹窗 -->
<?php if ($this->options->socialWechatQr): ?>
<div id="wechatModal" class="wechat-modal" onclick="closeWechatModal(event)">
    <div class="wechat-modal-content">
        <div class="wechat-modal-header">
            <span>微信二维码</span>
            <span class="wechat-modal-close" onclick="closeWechatModal(event)">&times;</span>
        </div>
        <div class="wechat-modal-body">
            <img src="<?php echo $this->options->socialWechatQr; ?>" alt="微信二维码" class="wechat-qr-img">
            <?php if ($this->options->socialWechat): ?>
            <p class="wechat-number">微信号: <?php echo htmlspecialchars($this->options->socialWechat); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
function showWechatModal() {
    document.getElementById('wechatModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeWechatModal(event) {
    event.stopPropagation();
    document.getElementById('wechatModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}
</script>
<?php endif; ?>

<?php themeAutoLoadScript(); ?>

<?php $this->footer(); ?>
</body>
</html>
