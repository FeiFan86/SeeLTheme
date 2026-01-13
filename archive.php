<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
include 'header.php';
?>

<div class="container">
    <div class="main-layout">
        <div class="main-content">
            <!-- 页面标题 -->
            <?php
            if ($this->is('category') || $this->is('tag') || $this->is('search') || $this->is('author') || $this->is('date')): ?>
            <div class="tags-header">
                <?php if ($this->is('category')): ?>
                    <h3 class="tags-title"><?php $this->archiveTitle('', ''); ?></h3>
                    <?php
                    // 获取分类描述
                    $categoryDescription = '';
                    // 首先尝试从categories数组获取描述
                    if (isset($this->categories) && isset($this->categories[0]['description'])) {
                        $categoryDescription = $this->categories[0]['description'];
                    }
                    // 如果上面没有获取到，尝试其他方法
                    if (empty($categoryDescription) && method_exists($this, 'getDescription')) {
                        $categoryDescription = $this->getDescription();
                    } elseif (empty($categoryDescription) && isset($this->description)) {
                        $categoryDescription = $this->description;
                    }
                    
                    // 直接从当前页面的categories数组获取分类数量
                    $categoryCount = 0;
                    if (isset($this->categories) && isset($this->categories[0]['count'])) {
                        $categoryCount = $this->categories[0]['count'];
                    }
                    ?>
                    <?php if (!empty($categoryDescription)): ?>
                    <div class="category-description"><?php echo $categoryDescription; ?></div>
                    <?php endif; ?>
                    <p class="tags-meta">共收录 <?php echo intval($categoryCount); ?> 篇文章</p>
                <?php elseif ($this->is('tag')): ?>
                    <h3 class="tags-title">标签：<?php $this->archiveTitle('', ''); ?></h3>
                    <?php
                    // 标签数量 - 直接遍历统计文章数
                    $tagCount = 0;
                    // 保存当前指针位置
                    $currentIndex = $this->sequence;
                    // 重置到开头并统计
                    $this->rewind();
                    while ($this->next()) {
                        $tagCount++;
                    }
                    // 恢复指针位置
                    $this->sequence = $currentIndex;
                    ?>
                    <p class="tags-meta">共收录 <?php echo intval($tagCount); ?> 篇文章</p>
                <?php elseif ($this->is('search')): ?>
                    <h3 class="tags-title">搜索：<?php $this->archiveTitle('', ''); ?></h3>
                    <p class="tags-meta">共找到 <?php echo intval($this->total()); ?> 篇相关文章</p>
                <?php elseif ($this->is('author')): ?>
                    <h3 class="tags-title">作者：<?php $this->archiveTitle('', ''); ?></h3>
                    <p class="tags-meta">共收录 <?php echo intval($this->total()); ?> 篇文章</p>
                <?php elseif ($this->is('date')): ?>
                    <h3 class="tags-title">归档：<?php $this->archiveTitle('', ''); ?></h3>
                    <p class="tags-meta">共收录 <?php echo intval($this->total()); ?> 篇文章</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- 文章列表 -->
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
                            <?php echo getExcerpt($this, 80); ?>
                        </p>
                        <div class="post-meta">
                            <span>📅 <?php $this->date('Y-m-d'); ?></span>
                            <span>👁 <?php echo getViews($this); ?></span>
                            <span>💬 <?php $this->commentsNum(); ?></span>
                        </div>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <!-- 分页 -->
            <?php themePager($this); ?>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <?php include 'sidebar.php'; ?>
        </aside>
    </div>
</div>

<style>
.category-description {
    font-size: 16px;
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 12px 0;
    padding: 16px;
    background: var(--bg-secondary);
    border-radius: 12px;
    border-left: 4px solid var(--accent-primary);
    overflow-wrap: break-word;
    word-wrap: break-word;
}

/* 移动端优化 */
@media (max-width: 768px) {
    .category-description {
        font-size: 14px;
        padding: 12px;
        margin: 8px 0;
    }
}
</style>
<?php include 'footer.php'; ?>
