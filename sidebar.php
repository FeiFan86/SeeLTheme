<aside class="sidebar">
    <?php
    // 判断当前页面类型
    $isHomePage = $this->is('index');

    // 获取当前页面的侧边栏组件配置
    $sidebarWidgets = $isHomePage ?
        ($this->options->sidebarHomeWidgets ?: array()) :
        ($this->options->sidebarOtherWidgets ?: array());

    // 获取广告位位置设置
    $sidebarAdPosition = $this->options->sidebarAdPosition ? $this->options->sidebarAdPosition : 'top';
    ?>

    <!-- Sidebar Ad Widget - 顶部 -->
    <?php if ($sidebarAdPosition == 'top' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Website Stats Widget -->
    <?php if (in_array('stats', $sidebarWidgets)): ?>
    <!-- Sidebar Ad Widget - 网站统计上面 -->
    <?php if ($sidebarAdPosition == 'stats' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget stats-widget">
        <h3 class="widget-title">网站统计</h3>
        <div class="stats-grid-full">
            <div class="stat-box">
                <div class="stat-icon stat-icon-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo getTotalPosts(); ?></div>
                    <div class="stat-label">文章</div>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-icon stat-icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo getTotalComments(); ?></div>
                    <div class="stat-label">评论</div>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-icon stat-icon-purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo getRealTotalViews(); ?></div>
                    <div class="stat-label">访问</div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search Widget -->
    <?php if (in_array('search', $sidebarWidgets)): ?>
    <!-- Sidebar Ad Widget - 搜索上面 -->
    <?php if ($sidebarAdPosition == 'search' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget">
        <h3 class="widget-title">搜索</h3>
        <form method="post" action="<?php $this->options->siteUrl(); ?>">
            <input type="text" name="s" placeholder="搜索文章...">
        </form>
    </div>
    <?php endif; ?>

    <!-- Categories Widget -->
    <?php if (in_array('category', $sidebarWidgets)): ?>
    <!-- Sidebar Ad Widget - 分类上面 -->
    <?php if ($sidebarAdPosition == 'category' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget">
        <h3 class="widget-title">分类</h3>
        <ul class="category-list">
            <?php $this->widget('Widget_Metas_Category_List')->to($categories); ?>
            <?php while ($categories->next()): ?>
                <li class="category-item">
                    <a href="<?php $categories->permalink(); ?>" class="category-link">
                        <span><?php $categories->name(); ?></span>
                        <span class="category-count"><?php $categories->count(); ?></span>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Tags Widget -->
    <?php if (in_array('tags', $sidebarWidgets)): ?>
    <!-- Sidebar Ad Widget - 标签云上面 -->
    <?php if ($sidebarAdPosition == 'tags' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget">
        <h3 class="widget-title">标签云</h3>
        <div class="tag-cloud">
            <?php
                $tagsCount = $this->options->tagsCloudCount ? (int)$this->options->tagsCloudCount : 30;
                if ($tagsCount > 0) {
                    $this->widget('Widget_Metas_Tag_Cloud', 'limit=' . $tagsCount)->to($tags);
                } else {
                    $this->widget('Widget_Metas_Tag_Cloud')->to($tags);
                }
            ?>
            <?php while ($tags->next()): ?>
                <a href="<?php $tags->permalink(); ?>" class="tag-item"><?php $tags->name(); ?></a>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Archives Widget -->
    <?php if (in_array('archive', $sidebarWidgets)): ?>
    <!-- Sidebar Ad Widget - 文章归档上面 -->
    <?php if ($sidebarAdPosition == 'archive' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget">
        <h3 class="widget-title">文章归档</h3>
        <ul class="archive-list">
            <?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=Y年m月')
                ->to($archive); ?>
            <?php while ($archive->next()): ?>
                <li class="archive-item">
                    <a href="<?php $archive->permalink(); ?>" class="archive-link">
                        <span><?php $archive->date(); ?></span>
                        <span class="archive-count"><?php echo $archive->count(); ?></span>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Hot Posts Widget -->
    <?php if (in_array('hotPosts', $sidebarWidgets)): ?>
    <!-- Sidebar Ad Widget - 热门文章上面 -->
    <?php if ($sidebarAdPosition == 'hotPosts' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget">
        <h3 class="widget-title">热门文章</h3>
        <?php $hotPostsCount = $this->options->hotPostsCount ? $this->options->hotPostsCount : 5; ?>
        <?php $hotPosts = getHotPosts($hotPostsCount); ?>
        <?php if (!empty($hotPosts)): ?>
        <div class="hot-posts-list">
            <?php foreach ($hotPosts as $index => $post): ?>
            <?php
                $postWidget = Typecho_Widget::widget('Widget_Abstract_Contents');
                $postWidget->load($post['cid']);
                $postUrl = $postWidget->permalink;
            ?>
            <a href="<?php echo $postUrl; ?>" class="hot-post-item">
                <div class="hot-post-rank rank-<?php echo $index + 1; ?>"><?php echo $index + 1; ?></div>
                <div class="hot-post-content">
                    <div class="hot-post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                    <div class="hot-post-meta">
                        <span>👁 <?php echo isset($post['views']) ? $post['views'] : '0'; ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="no-posts">暂无热门文章</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Latest Comments Widget -->
    <?php if (in_array('latestComments', $sidebarWidgets)): ?>
    <!-- Sidebar Ad Widget - 最新评论上面 -->
    <?php if ($sidebarAdPosition == 'latestComments' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget">
        <h3 class="widget-title">最新评论</h3>
        <?php $commentsCount = $this->options->latestCommentsCount ? $this->options->latestCommentsCount : 5; ?>
        <?php $comments = getLatestComments($commentsCount); ?>
        <?php if (!empty($comments)): ?>
        <div class="latest-comments-list">
            <?php foreach ($comments as $comment): ?>
            <?php
                $post = Typecho_Widget::widget('Widget_Abstract_Contents');
                $post->load($comment['cid']);
                $postUrl = $post->permalink;
            ?>
            <a href="<?php echo $postUrl; ?>" class="latest-comment-item">
                <div class="comment-content">
                    <div class="comment-author"><?php echo htmlspecialchars($comment['author']); ?></div>
                    <div class="comment-text"><?php echo mb_substr(strip_tags($comment['text']), 0, 50); ?>...</div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="no-comments">暂无评论</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Friend Links Widget -->
    <?php if (in_array('friendLinks', $sidebarWidgets) && $this->options->friendLinks): ?>
    <!-- Sidebar Ad Widget - 友情链接上面 -->
    <?php if ($sidebarAdPosition == 'friendLinks' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-widget">
        <h3 class="widget-title">友情链接</h3>
        <ul class="links-list">
            <?php
                $friendLinksData = parseFriendLinks($this->options->friendLinks);
                $friendLinks = isset($friendLinksData['ungrouped']) ? $friendLinksData['ungrouped'] : array();
                if (!empty($friendLinks)) {
                    foreach ($friendLinks as $link) {
            ?>
                <li class="link-item">
                    <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank">
                        <?php echo htmlspecialchars($link['name']); ?>
                    </a>
                </li>
            <?php
                    }
                }
            ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Sidebar Ad Widget - 底部 -->
    <?php if ($sidebarAdPosition == 'bottom' && $this->options->enableSidebarAd == '1' && $this->options->sidebarAdCode): ?>
    <div class="sidebar-widget">
        <div class="sidebar-ad">
            <?php echo $this->options->sidebarAdCode; ?>
        </div>
    </div>
    <?php endif; ?>
</aside>
