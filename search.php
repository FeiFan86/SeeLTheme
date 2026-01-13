<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
include 'header.php';
?>

<div class="container">
    <div class="main-layout">
        <div class="main-content">
            <div class="tags-header">
                <h1 class="tags-title">
                    搜索结果：<strong><?php $this->archiveTitle('', '', ' - '); ?></strong>
                </h1>
            </div>

            <div class="posts-grid">
                <?php while ($this->next()): ?>
                    <article class="post-card">
                        <div class="post-cover">
                            <a href="<?php $this->permalink(); ?>">
                                <img src="<?php echo getThumbnail($this, 600, 340); ?>" alt="<?php $this->title(); ?>">
                            </a>
                        </div>
                        <div class="post-content">
                            <h3 class="post-title">
                                <a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
                            </h3>
                            <p class="post-excerpt">
                                <?php echo getExcerpt($this, 100); ?>
                            </p>
                        <div class="post-meta">
                            <span class="post-date">📅 <?php $this->date('Y-m-d'); ?></span>
                            <span class="post-views">👁 <?php echo getViews($this); ?></span>
                            <span class="post-comments">💬 <?php $this->commentsNum(); ?></span>
                        </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ($this->total() == 0): ?>
            <div class="no-results">
                <p>🔍 没有找到相关文章，换个关键词试试吧！</p>
                <form method="post" action="<?php $this->options->siteUrl(); ?>" class="search-form-inline">
                    <input type="text" name="s" placeholder="输入关键词搜索...">
                    <button type="submit">搜索</button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
