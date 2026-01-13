<?php
/**
 * 归档页面
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
                    <h1 class="tags-title">文章归档</h1>
                    <p class="tags-meta">共收录 <?php $this->widget('Widget_Stat')->to($stat); echo $stat->publishedPostsNum; ?> 篇文章</p>
                </div>

                <!-- 归档内容 -->
                <?php
                $db = Typecho_Db::get();
                $select = $db->select('created', 'cid', 'title', 'slug')
                    ->from('table.contents')
                    ->where('type = ?', 'post')
                    ->where('status = ?', 'publish')
                    ->order('created', Typecho_Db::SORT_DESC);
                $posts = $db->fetchAll($select);

                $archives = array();
                foreach ($posts as $post) {
                    $year = date('Y', $post['created']);
                    $month = date('m', $post['created']);
                    $key = $year . '-' . $month;

                    if (!isset($archives[$key])) {
                        $archives[$key] = array(
                            'year' => $year,
                            'month' => $month,
                            'posts' => array()
                        );
                    }
                    $archives[$key]['posts'][] = $post;
                }

                foreach ($archives as $archive):
                ?>
                <div class="archive-group">
                    <h2 class="archive-group-title"><?php echo $archive['year']; ?>年<?php echo $archive['month']; ?>月</h2>
                    <ul class="archive-list">
                        <?php foreach ($archive['posts'] as $post): ?>
                        <li>
                            <a href="<?php echo Typecho_Router::url('post', array('cid' => $post['cid'])); ?>">
                                <span class="archive-date"><?php echo date('m-d', $post['created']); ?></span>
                                <span class="archive-title"><?php echo htmlspecialchars($post['title']); ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>


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
.archive-group {
    margin-bottom: 40px;
}

.archive-group-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.archive-group-title::before {
    content: '';
    width: 4px;
    height: 24px;
    background: var(--accent-primary);
    border-radius: 2px;
}

.archive-list {
    list-style: none;
}

.archive-list li {
    margin-bottom: 12px;
}

.archive-list li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-secondary);
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.3s;
    border: 1px solid transparent;
}

.archive-list li a:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateX(8px);
    border-color: var(--accent-primary);
}

.archive-date {
    flex-shrink: 0;
    font-size: 13px;
    color: var(--text-muted);
    min-width: 50px;
}

.archive-list li a:hover .archive-date {
    color: rgba(255,255,255,0.9);
}

.archive-title {
    flex: 1;
    font-size: 14px;
    font-weight: 500;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* 日历形式样式 */
.archive-calendar {
    margin-top: 20px;
}

.archive-year {
    margin-bottom: 40px;
}

.archive-year-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 24px;
    padding-bottom: 12px;
    border-bottom: 3px solid var(--accent-primary);
    display: flex;
    align-items: center;
    gap: 12px;
}

.archive-year-title::before {
    content: '📅';
    font-size: 20px;
}

.archive-months {
    display: grid;
    gap: 24px;
}

.archive-month {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border-color);
}

.archive-month-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.post-count {
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 400;
}

.archive-days {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.archive-day {
    padding: 12px 16px;
    background: var(--bg-primary);
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.3s;
    border: 1px solid var(--border-color);
    font-size: 14px;
}

.archive-day:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateX(8px);
    border-color: var(--accent-primary);
}

@media (max-width: 768px) {
    .archive-list li a {
        flex-wrap: wrap;
        gap: 8px;
    }

    .archive-date {
        min-width: auto;
    }

    .archive-month {
        padding: 16px;
    }

    .archive-days {
        gap: 6px;
    }

    .archive-day {
        padding: 10px 12px;
        font-size: 13px;
    }
}
</style>

<?php include 'footer.php'; ?>
