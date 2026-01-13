<?php
/**
 * 标签页面
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
include 'header.php';
?>

<div class="content-wrapper">
    <div class="container">
        <div class="main-layout">
            <!-- 主内容区 -->
            <div class="content-main">
                <div class="tags-header">
                    <h1 class="tags-title">标签云</h1>
                    <p class="tags-meta">共收录 <?php echo getTotalTags(); ?> 个标签</p>
                </div>

                <!-- 标签云 -->
                <div class="tags-cloud">
                    <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=count&desc=1&ignoreZeroCount=1')->to($tags); ?>
                    <?php if($tags->have()): ?>
                    <?php while ($tags->next()): ?>
                    <a href="<?php $tags->permalink(); ?>" class="tag-item" title="共 <?php $tags->count(); ?> 篇文章">
                        <span class="tag-name"><?php $tags->name(); ?></span>
                        <span class="tag-count"><?php $tags->count(); ?></span>
                    </a>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <div class="no-tags">
                        <span class="no-tags-icon">🏷️</span>
                        <p>暂无标签</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 侧边栏 -->
            <aside class="sidebar">
                <?php include 'sidebar.php'; ?>
            </aside>
        </div>
    </div>
</div>

<!-- 页面样式 -->
<style>
.tags-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 40px 20px;
    background: var(--bg-secondary);
    border-radius: 16px;
}

.tags-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.tags-meta {
    font-size: 14px;
    color: var(--text-muted);
}

.tags-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
}

.tags-cloud .tag-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    font-size: 14px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.3s;
    position: relative;
}

.tags-cloud .tag-item:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    border-color: var(--accent-primary);
}

.tags-cloud .tag-name {
    font-weight: 500;
}

.tags-cloud .tag-count {
    background: var(--border-color);
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    min-width: 24px;
    text-align: center;
}

.tags-cloud .tag-item:hover .tag-count {
    background: rgba(255,255,255,0.3);
    color: white;
}

.no-tags {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}

.no-tags-icon {
    font-size: 48px;
    display: block;
    margin-bottom: 16px;
}

.no-tags p {
    font-size: 16px;
    margin: 0;
}

@media (max-width: 768px) {
    .tags-header {
        padding: 30px 20px;
    }

    .tags-title {
        font-size: 24px;
    }

    .tags-cloud {
        gap: 8px;
    }

    .tags-cloud .tag-item {
        padding: 10px 16px;
        font-size: 13px;
    }
}
</style>

<?php include 'footer.php'; ?>
