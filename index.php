<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * SeeLTheme - 塞尔主题
 *
 * @package SeeLTheme
 * @author Jessadmin
 * @version 1.20
 * @link https://github.com/FeiFan86/SeeLTheme
 * @description 一款现代化的 Typecho 博客主题，支持简洁化与玻璃态双主题自由切换。主题内置丰富的功能模块，包括暗黑模式、自定义页面模板、主题设置导入导出、响应式设计等。简洁化主题干净利落，玻璃态主题炫酷美观，完美适配各种设备，为您的博客打造独特的视觉体验。
 */

include 'header.php';
?>

<div class="container">
    <div class="main-layout">
        <div class="main-content">
            <!-- 轮播图 -->
            <?php if ($this->options->enableSlider == '1'): ?>
            <div class="hero-slider">
                <div class="slider-wrapper">
                    <?php
                        $featured = getSliderPosts();
                        $index = 0;
                        foreach ($featured as $post) {
                            $activeClass = $index === 0 ? ' active' : '';

                            // 从内容中提取第一张图片
                            $content = $post['text'];
                            $pattern = '/<img.*?src=[\'"](.*?)[\'"].*?>/i';
                            preg_match($pattern, $content, $matches);
                            if (isset($matches[1])) {
                                $imageUrl = $matches[1];
                            } else {
                                // 使用 Unsplash 随机图片
                                $randomImages = array(
                                    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200',
                                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=1200',
                                    'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200',
                                );
                                $imageUrl = $randomImages[array_rand($randomImages)];
                            }

                            // 获取文章链接
                            $db = Typecho_Db::get();
                            $postRow = $db->fetchRow($db->select()->from('table.contents')
                                ->where('cid = ?', $post['cid'])
                                ->limit(1));

                            if ($postRow) {
                                $postUrl = Typecho_Router::url('post', array(
                                    'cid' => $postRow['cid'],
                                    'slug' => $postRow['slug']
                                ), $this->options->index);
                            } else {
                                $postUrl = '#';
                            }
                    ?>
                    <div class="slider-item<?php echo $activeClass; ?>">
                        <a href="<?php echo $postUrl; ?>" class="slider-link">
                            <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            <div class="slider-overlay">
                                <h2 class="slider-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                            </div>
                        </a>
                    </div>
                    <?php
                        $index++;
                        }
                    ?>
                </div>
                <div class="slider-dots"></div>
                <button class="slider-prev">❮</button>
                <button class="slider-next">❯</button>
            </div>
            <?php endif; ?>

            <!-- 文章列表 -->
            <div class="posts-list">
                <?php while ($this->next()): ?>
                <article class="post-item">
                    <!-- 文章封面 -->
                    <div class="post-item-cover">
                        <a href="<?php $this->permalink(); ?>">
                            <img src="<?php echo getThumbnail($this, 800, 450); ?>" alt="<?php $this->title(); ?>">
                        </a>
                    </div>

                    <!-- 文章内容 -->
                    <div class="post-item-content">
                        <h2 class="post-item-title">
                            <a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
                        </h2>
                        <p class="post-item-excerpt">
                            <?php echo getExcerpt($this, 120); ?>
                        </p>
                        <div class="post-item-meta">
                            <span class="meta-date">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?php $this->date('Y-m-d'); ?>
                            </span>
                            <span class="meta-views">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <?php echo getViews($this); ?>
                            </span>
                            <span class="meta-comments">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                </svg>
                                <?php $this->commentsNum(); ?>
                            </span>
                        </div>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <!-- 分页 -->
            <?php themePager($this); ?>
        </div>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
