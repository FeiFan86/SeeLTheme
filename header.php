<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $this->archiveTitle([
        'category' => '分类 %s 下的文章',
        'search'   => '包含关键字 %s 的文章',
        'tag'      => '标签 %s 下的文章',
        'author'   => '%s 发布的文章'
    ], '', ' - '); ?><?php $this->options->title(); ?> - <?php if ($this->options->siteDescription): ?><?php $this->options->siteDescription(); ?><?php endif; ?></title>
    

    
    <!-- Favicon -->
    <?php if ($this->options->faviconUrl): ?>
    <link rel="shortcut icon" href="<?php $this->options->faviconUrl(); ?>">
    <?php else: ?>
    <link rel="shortcut icon" href="<?php $this->options->siteUrl(); ?>favicon.ico">
    <?php endif; ?>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('components.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('comments.css'); ?>">
    <!-- V7 玻璃态主题专用样式 -->
    <?php if ($this->options->defaultTheme == 'v7'): ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('v7-glass.css'); ?>">
    <?php endif; ?>


    
    <!-- 统计代码 -->
    <?php if ($this->options->analyticsCode): ?>
    <?php $this->options->analyticsCode(); ?>
    <?php endif; ?>

    <?php $this->header(); ?>


</head>
<body>
<script>
    // 页面加载时立即应用保存的主题和模式
    (function() {
        const savedTheme = localStorage.getItem('seel_theme');
        const savedDarkMode = localStorage.getItem('seel_darkmode');
        const defaultTheme = '<?php $this->options->defaultTheme(); ?>';

        const theme = savedTheme || defaultTheme;
        const isDark = savedDarkMode === 'true';

        document.body.className = `theme-${theme}${isDark ? ' dark' : ''}`;

        // 如果是V7主题，加载玻璃态样式
        if (theme === 'v7') {
            const link = document.createElement('link');
            link.id = 'v7-glass-stylesheet';
            link.rel = 'stylesheet';
            link.href = '<?php $this->options->themeUrl('v7-glass.css'); ?>';
            document.head.appendChild(link);
        }

        // 显示/隐藏玻璃态背景
        const shapes = document.querySelector('.bg-shapes');
        if (shapes) {
            shapes.style.display = theme === 'v7' ? 'block' : 'none';
        }
    })();
</script>

<!-- 玻璃态背景装饰层 -->
<div class="bg-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
</div>

<!-- 公告栏 -->
<?php if ($this->options->enableAnnouncement == '1' && $this->options->announcementText): ?>
<div class="announcement-bar" id="announcementBar">
    <div class="announcement-content">
        <span class="announcement-icon">📢</span>
        <span class="announcement-text"><?php echo $this->options->announcementText; ?></span>
        <?php if ($this->options->announcementClose == '1'): ?>
        <button class="announcement-close" onclick="window.closeAnnouncement && window.closeAnnouncement()">✕</button>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Header -->
<?php $navStyle = $this->options->navStyle ? $this->options->navStyle : 'default'; ?>
<header class="nav-style-<?php echo $navStyle; ?>">
    <div class="header-inner">
        <a href="<?php $this->options->siteUrl(); ?>" class="logo">
            <?php if ($this->options->logoUrl): ?>
            <img src="<?php $this->options->logoUrl(); ?>" alt="Logo" class="logo-img">
            <?php else: ?>
            <?php $this->options->title(); ?>
            <?php endif; ?>
        </a>
        <nav>
            <ul class="nav-links" id="mainNav">
                <?php
                // 优先使用自定义导航
                if ($this->options->customNav && trim($this->options->customNav) !== '') {
                    $customNavItems = parseCustomNav($this->options->customNav);
                    echo renderNavItems($customNavItems, $this->request);
                } else {
                    // 默认导航
                    echo '<li><a href="' . $this->options->siteUrl() . '" class="' . ($this->is('index') ? 'active' : '') . '">首页</a></li>';
                    echo '<li><a href="' . $this->options->siteUrl() . 'archives.html" class="' . ($this->is('archive') ? 'active' : '') . '">归档</a></li>';

                    // 显示分类（暂时平面显示，后续可添加二级分类支持）
                    $this->widget('Widget_Metas_Category_List')->to($categories);
                    $displayCount = 6;
                    $count = 0;
                    while ($categories->next()):
                        if ($displayCount > 0 && $count >= $displayCount) break;
                        $count++;
                        echo '<li><a href="' . $categories->permalink() . '" class="' . ($this->is('category', $categories->slug) ? 'active' : '') . '">';
                        echo $categories->name();
                        echo '</a></li>';
                    endwhile;

                    echo '<li><a href="' . $this->options->siteUrl() . 'about.html" class="' . ($this->is('page', 'about') ? 'active' : '') . '">关于</a></li>';
                }
                ?>
            </ul>
        </nav>
        <div class="header-actions">
            <!-- 移动端菜单按钮 -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="window.toggleMobileMenu && window.toggleMobileMenu()">
                <div class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>

            <!-- 搜索按钮 -->
            <button class="search-toggle" id="searchToggleBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events: none;">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>


        </div>
    </div>
</header>

<!-- 移动端全屏菜单 -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay">
    <div class="mobile-menu-container">
        <!-- 菜单头部 -->
        <div class="mobile-menu-header">
            <div class="mobile-menu-title">菜单</div>
            <button class="mobile-menu-close" onclick="window.toggleMobileMenu && window.toggleMobileMenu()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- 菜单内容区 -->
        <div class="mobile-menu-content">
            <!-- 导航菜单 -->
            <div class="mobile-menu-section">
                <h4 class="mobile-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                    快捷导航
                </h4>
                <ul class="mobile-nav-list">
                    <li><a href="<?php $this->options->siteUrl(); ?>">首页</a></li>
                    <li><a href="<?php $this->options->siteUrl(); ?>archives.html">归档</a></li>
                    <li><a href="<?php $this->options->siteUrl(); ?>about.html">关于</a></li>
                </ul>
            </div>

            <!-- 分类 -->
            <div class="mobile-menu-section">
                <h4 class="mobile-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    文章分类
                </h4>
                <ul class="mobile-nav-list">
                    <?php $this->widget('Widget_Metas_Category_List')->to($categories); ?>
                    <?php while ($categories->next()): ?>
                    <li>
                        <a href="<?php $categories->permalink(); ?>">
                            <?php $categories->name(); ?>
                            <span class="category-count-badge"><?php $categories->count(); ?></span>
                        </a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- 热门文章 -->
            <div class="mobile-menu-section">
                <h4 class="mobile-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    热门文章
                </h4>
                <div class="mobile-hot-posts">
                    <?php $hotPostsCount = $this->options->hotPostsCount ? $this->options->hotPostsCount : 5; ?>
                    <?php $hotPosts = getHotPosts($hotPostsCount); ?>
                    <?php if (!empty($hotPosts)): ?>
                    <?php foreach ($hotPosts as $index => $post): ?>
                    <?php
                        $postWidget = Typecho_Widget::widget('Widget_Abstract_Contents');
                        $postWidget->load($post['cid']);
                        $postUrl = $postWidget->permalink;
                    ?>
                    <a href="<?php echo $postUrl; ?>" class="mobile-hot-post-item">
                        <span class="mobile-hot-rank rank-<?php echo $index + 1; ?>"><?php echo $index + 1; ?></span>
                        <span class="mobile-hot-title"><?php echo htmlspecialchars($post['title']); ?></span>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 网站统计 -->
            <div class="mobile-menu-section">
                <h4 class="mobile-section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20V10"></path>
                        <path d="M18 20V4"></path>
                        <path d="M6 20v-4"></path>
                    </svg>
                    网站统计
                </h4>
                <div class="mobile-stats-grid">
                    <div class="mobile-stat-item">
                        <div class="mobile-stat-value"><?php echo getTotalPosts(); ?></div>
                        <div class="mobile-stat-label">文章</div>
                    </div>
                    <div class="mobile-stat-item">
                        <div class="mobile-stat-value"><?php echo getTotalComments(); ?></div>
                        <div class="mobile-stat-label">评论</div>
                    </div>
                    <div class="mobile-stat-item">
                        <div class="mobile-stat-value"><?php echo getRealTotalViews(); ?></div>
                        <div class="mobile-stat-label">访问</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <!-- 阅读进度条 -->
    <?php 
    $enableProgressValue = $this->options->enableProgress;
    ?>

    <?php if ($enableProgressValue): ?>
    <div class="reading-progress" id="readingProgress"></div>
    <?php endif; ?>
    
    <!-- 搜索框 -->
    <div class="search-overlay" id="searchOverlay">
        <div class="search-container">
            <div class="search-header">
                <h3 class="search-title">搜索文章</h3>
                <button type="button" class="close-search" onclick="window.toggleSearch && window.toggleSearch()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events: none;">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form method="post" action="<?php $this->options->siteUrl(); ?>" class="search-form">
                <div class="search-input-wrapper">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="s" placeholder="输入关键词搜索..." id="searchInput" autocomplete="off">
                </div>
                <button type="submit" class="search-submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    搜索
                </button>
            </form>
            <div class="search-tips">
                <span>💡 提示：按 Enter 键快速搜索</span>
            </div>
        </div>
    </div>

<script>

    // 默认主题设置（从 PHP 获取）
    const defaultTheme = '<?php echo addslashes($this->options->defaultTheme()); ?>';
    const darkModeTime = '<?php echo addslashes($this->options->darkModeTime()); ?>';
    
    // 判断当前时间是否在暗黑模式时间段内
    function isInDarkModeTime() {
        if (!darkModeTime || !darkModeTime.includes('-')) {
            return false;
        }
        
        const [startTime, endTime] = darkModeTime.split('-');
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        const currentTotalMinutes = currentHour * 60 + currentMinute;
        
        const [startHour, startMinute] = startTime.split(':').map(Number);
        const [endHour, endMinute] = endTime.split(':').map(Number);
        const startTotalMinutes = startHour * 60 + startMinute;
        const endTotalMinutes = endHour * 60 + endMinute;
        
        // 如果结束时间小于开始时间，说明跨天（如 20:00-7:00）
        if (endTotalMinutes < startTotalMinutes) {
            return currentTotalMinutes >= startTotalMinutes || currentTotalMinutes < endTotalMinutes;
        } else {
            return currentTotalMinutes >= startTotalMinutes && currentTotalMinutes < endTotalMinutes;
        }
    }
    
    // 从 localStorage 读取保存的主题和模式，如果没有则使用默认值
    let currentTheme = localStorage.getItem('seel_theme') || defaultTheme;
    let isDarkMode = localStorage.getItem('seel_darkmode') === 'true';
    
    // 如果 localStorage 没有保存用户的选择，则根据时间段判断
    if (localStorage.getItem('seel_darkmode') === null) {
        isDarkMode = isInDarkModeTime();
    }


    // 公告栏关闭功能
    <?php if ($this->options->enableAnnouncement == '1'): ?>
    window.closeAnnouncement = function() {
        const announcementBar = document.getElementById('announcementBar');
        if (announcementBar) {
            announcementBar.style.display = 'none';
            localStorage.setItem('seel_announcement_closed', 'true');
        }
    };

    // 页面加载时检查公告栏状态（移到主DOMContentLoaded监听器中）
    <?php endif; ?>

    // 切换主题下拉菜单
    window.toggleThemeMenu = function() {
        // 控制固定的主题切换按钮下拉菜单
        const dropdownFixed = document.getElementById('themeDropdownFixed');
        if (dropdownFixed) {
            dropdownFixed.classList.toggle('active');
            
            // 点击外部关闭菜单
            if (dropdownFixed.classList.contains('active')) {
                document.addEventListener('click', window.closeThemeMenuOutside);
            }
        }
    };

    window.closeThemeMenuOutside = function(event) {
        const dropdownFixed = document.getElementById('themeDropdownFixed');
        const buttonFixed = document.getElementById('themeToggleBtnFixed');
        if (dropdownFixed && buttonFixed) {
            if (!dropdownFixed.contains(event.target) && !buttonFixed.contains(event.target)) {
                dropdownFixed.classList.remove('active');
                document.removeEventListener('click', window.closeThemeMenuOutside);
            }
        }
    };


    // 切换主题
    window.switchTheme = function(theme) {
        currentTheme = theme;
        document.body.className = `theme-${theme}${isDarkMode ? ' dark' : ''}`;

        // 切换玻璃态背景
        const shapes = document.querySelector('.bg-shapes');
        if (shapes) {
            shapes.style.display = theme === 'v7' ? 'block' : 'none';
        }

        // 动态加载/卸载 V7 玻璃态样式
        const v7StyleSheet = document.getElementById('v7-glass-stylesheet');
        if (theme === 'v7') {
            if (!v7StyleSheet) {
                const link = document.createElement('link');
                link.id = 'v7-glass-stylesheet';
                link.rel = 'stylesheet';
                link.href = '<?php $this->options->themeUrl('v7-glass.css'); ?>';
                document.head.appendChild(link);
            }
        } else {
            if (v7StyleSheet) {
                v7StyleSheet.remove();
            }
        }

        // 保存到 localStorage
        localStorage.setItem('seel_theme', theme);

        // 关闭下拉菜单
        const dropdownFixed = document.getElementById('themeDropdownFixed');
        if (dropdownFixed) {
            dropdownFixed.classList.remove('active');
        }
    };

    // 切换暗黑/亮色模式
    window.toggleMode = function() {
        isDarkMode = !isDarkMode;

        if (isDarkMode) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }

        const modeBtn = document.getElementById('modeSwitchBtn');
        const modeText = document.getElementById('modeText');

        if (isDarkMode) {
            modeText.textContent = '亮色模式';
        } else {
            modeText.textContent = '暗黑模式';
        }

        // 保存到 localStorage
        localStorage.setItem('seel_darkmode', isDarkMode);
    };

    // 搜索框切换
    window.toggleSearch = function() {
        const overlay = document.getElementById('searchOverlay');
        if (!overlay) {
            return;
        }
        
        const isActive = overlay.classList.contains('active');
        
        if (!isActive) {
            // 显示搜索框
            overlay.style.transition = 'none';
            
            requestAnimationFrame(() => {
                overlay.classList.add('active');
                overlay.offsetHeight; // 触发重排
                
                setTimeout(() => {
                    overlay.style.transition = '';
                }, 10);
            });
            
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.focus();
            }
            
            document.addEventListener('click', window.closeSearchOutside);
        } else {
            // 隐藏搜索框
            overlay.classList.remove('active');
            document.removeEventListener('click', window.closeSearchOutside);
        }
    };

    // 点击搜索框外部关闭搜索框
    window.closeSearchOutside = function(event) {
        const overlay = document.getElementById('searchOverlay');
        if (!overlay || !overlay.classList.contains('active')) {
            return;
        }
        const container = overlay.querySelector('.search-container');
        const searchButton = document.querySelector('.search-btn');
        
        // 如果点击的不是搜索框内部，也不是搜索按钮，则关闭
        if (event.target === overlay || 
            (container && !container.contains(event.target) && 
             (!searchButton || !searchButton.contains(event.target)))) {
            overlay.classList.remove('active');
            document.removeEventListener('click', window.closeSearchOutside);
        }
    };

    // 阻止点击搜索框内容时关闭
    // 这部分代码会在后面的主DOMContentLoaded监听器中执行
    const initSearchContainer = function() {
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer) {
            searchContainer.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        }
    };

    // 返回顶部
    window.scrollToTop = function() {
        var scrollToTopOptions = {
            top: 0,
            behavior: 'smooth'
        };
        window.scrollTo(scrollToTopOptions);
    };

    // 阅读进度条
    <?php if ($this->options->enableProgress): ?>
    const readingProgressEl = document.getElementById('readingProgress');
    if (readingProgressEl) {
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled;
            if (height <= 0) {
                scrolled = 100; // 页面不需要滚动，视为已阅读全部
            } else {
                scrolled = (winScroll / height) * 100;
                // 限制在0-100之间
                scrolled = Math.max(0, Math.min(100, scrolled));
            }
            readingProgressEl.style.width = scrolled + '%';
        });
    }
    <?php endif; ?>



    // 页面加载时恢复主题和模式
    window.addEventListener('DOMContentLoaded', function() {
        // 检查公告栏状态
        const announcementClosed = localStorage.getItem('seel_announcement_closed');
        if (announcementClosed === 'true') {
            const announcementBar = document.getElementById('announcementBar');
            if (announcementBar) {
                announcementBar.style.display = 'none';
            }
        }

        // 初始化搜索容器
        initSearchContainer();

        // 搜索按钮事件绑定
        const searchToggleBtn = document.getElementById('searchToggleBtn');
        if (searchToggleBtn && window.toggleSearch) {
            searchToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.toggleSearch();
            });
        }

        // 返回顶部按钮显示/隐藏和进度更新
        <?php if ($this->options->enableBackToTop): ?>
        const backToTopBtn = document.getElementById('backToTop');
        const isProgressStyle = backToTopBtn && backToTopBtn.classList.contains('back-to-top-progress');
        const progressIndicator = document.getElementById('progressIndicator');

        if (backToTopBtn) {
            window.addEventListener('scroll', function() {
                // 显示/隐藏按钮
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('visible');
                } else {
                    backToTopBtn.classList.remove('visible');
                }

                // 更新进度环
                if (isProgressStyle && progressIndicator) {
                    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    const scrolled = (winScroll / height) * 100;
                    const offset = 283 - (283 * scrolled / 100); // 283 = 2 * PI * 45 (圆的周长)
                    progressIndicator.style.strokeDashoffset = offset;
                }
            });
        }
        <?php endif; ?>

        // 轮播图功能
        const slider = document.querySelector('.hero-slider');
        if (slider) {
            const items = slider.querySelectorAll('.slider-item');
            const dotsContainer = slider.querySelector('.slider-dots');
            const prevBtn = slider.querySelector('.slider-prev');
            const nextBtn = slider.querySelector('.slider-next');

            // 只有在有轮播项时才初始化
            if (items.length > 0 && dotsContainer && prevBtn && nextBtn) {
                let currentIndex = 0;

                // 创建轮播指示点
                items.forEach((item, index) => {
                    const dot = document.createElement('div');
                    dot.className = 'slider-dot' + (index === 0 ? ' active' : '');
                    dot.addEventListener('click', () => goToSlide(index));
                    dotsContainer.appendChild(dot);
                });

                const dots = dotsContainer.querySelectorAll('.slider-dot');

                function goToSlide(index) {
                    items[currentIndex].classList.remove('active');
                    dots[currentIndex].classList.remove('active');
                    currentIndex = index;
                    items[currentIndex].classList.add('active');
                    dots[currentIndex].classList.add('active');
                }

                function nextSlide() {
                    const nextIndex = (currentIndex + 1) % items.length;
                    goToSlide(nextIndex);
                }

                function prevSlide() {
                    const prevIndex = (currentIndex - 1 + items.length) % items.length;
                    goToSlide(prevIndex);
                }

                prevBtn.addEventListener('click', prevSlide);
                nextBtn.addEventListener('click', nextSlide);

                // 自动轮播
                setInterval(nextSlide, 5000);
            }
        }
        // 设置初始主题（如果顶部 IIFE 已设置，则不再重复设置）
        if (!document.body.classList.contains('theme-v12') && !document.body.classList.contains('theme-v7')) {
            document.body.className = `theme-${currentTheme}${isDarkMode ? ' dark' : ''}`;
        }

        // 控制玻璃态背景显示
        const shapes = document.querySelector('.bg-shapes');
        if (shapes) {
            shapes.style.display = currentTheme === 'v7' ? 'block' : 'none';
        }

        // 更新暗黑模式按钮
        const modeBtn = document.getElementById('modeSwitchBtn');
        if (modeBtn) {
            const modeText = document.getElementById('modeText');

            if (isDarkMode) {
                if (modeText) modeText.textContent = '亮色模式';
            } else {
                if (modeText) modeText.textContent = '暗黑模式';
            }
        }
    });
</script>

    <!-- 移动端菜单 -->
<script>
    // 切换移动端菜单
    window.toggleMobileMenu = function() {
        const menuOverlay = document.getElementById('mobileMenuOverlay');
        const menuToggle = document.getElementById('mobileMenuToggle');

        if (!menuOverlay || !menuToggle) return;

        menuOverlay.classList.toggle('active');
        menuToggle.classList.toggle('active');

        // 阻止页面滚动
        if (menuOverlay.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    };

    // 点击导航链接后关闭移动端菜单
    document.addEventListener('DOMContentLoaded', function() {
        const menuOverlay = document.getElementById('mobileMenuOverlay');
        if (!menuOverlay) return;

        // 为菜单内所有链接添加点击事件
        menuOverlay.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                // 在移动端点击后关闭菜单
                if (window.innerWidth <= 768) {
                    toggleMobileMenu();
                }
            });
        });

        // 点击外部关闭移动端菜单
        document.addEventListener('click', function(e) {
            if (window.innerWidth > 768) return;

            const menuOverlay = document.getElementById('mobileMenuOverlay');
            const menuToggle = document.getElementById('mobileMenuToggle');

            if (!menuOverlay || !menuToggle) return;

            // 如果点击的是菜单内部或菜单按钮，不关闭
            if (menuOverlay.contains(e.target) || menuToggle.contains(e.target)) return;

            // 如果菜单是激活状态，关闭它
            if (menuOverlay.classList.contains('active')) {
                toggleMobileMenu();
            }
        });

        // 窗口大小改变时重置菜单状态
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const menuOverlay = document.getElementById('mobileMenuOverlay');
                const menuToggle = document.getElementById('mobileMenuToggle');

                if (menuOverlay) menuOverlay.classList.remove('active');
                if (menuToggle) menuToggle.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
</script>
