<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function threadedComments($comments, $options) {
    static $parentAuthors = array();
    static $floorCount = 0;

    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
    $commentLevel = $comments->levels;

    // 一级评论显示楼层号
    $floorInfo = '';
    if ($comments->parent == 0) {
        $floorCount++;
        $floorInfo = '<span class="comment-floor">' . $floorCount . '楼</span>';
    }

    // 如果是回复评论，获取父评论的作者信息
    $replyInfo = '';
    if ($comments->parent > 0) {
        if (!isset($parentAuthors[$comments->parent])) {
            $db = Typecho_Db::get();
            $parentComment = $db->fetchRow($db->select()->from('table.comments')->where('coid = ?', $comments->parent)->limit(1));
            if ($parentComment) {
                $parentAuthors[$comments->parent] = $parentComment['author'];
            }
        }
        if (isset($parentAuthors[$comments->parent])) {
            $replyInfo = '<span class="comment-reply-info">回复 ' . htmlspecialchars($parentAuthors[$comments->parent]) . '</span>';
        }
    }
?>
    <div id="comment-<?php echo $comments->coid; ?>" class="comment-item<?php echo $commentClass; ?>" data-level="<?php echo $commentLevel; ?>" data-parent="<?php echo $comments->parent ?: '0'; ?>">
        <div class="comment-avatar">
            <img src="<?php echo getCommentAvatar($comments->mail, $comments->author, 50); ?>" alt="<?php echo htmlspecialchars($comments->author); ?>">
        </div>
        <div class="comment-content">
            <div class="comment-header">
                <div class="comment-author-info">
                    <?php if ($comments->url): ?>
                        <a href="<?php echo htmlspecialchars($comments->url); ?>" target="_blank" rel="nofollow" class="comment-author"><?php echo htmlspecialchars($comments->author); ?></a>
                    <?php else: ?>
                        <span class="comment-author"><?php echo htmlspecialchars($comments->author); ?></span>
                    <?php endif; ?>
                    <?php echo $replyInfo; ?>
                    <?php echo $floorInfo; ?>
                    <?php if ($comments->authorId == $comments->ownerId): ?>
                        <span class="comment-badge">博主</span>
                    <?php endif; ?>
                    <?php
                    // 如果是待审核评论且当前用户是管理员，显示待审核标记
                    if ($comments->status == 'pending') {
                        $user = Typecho_Widget::widget('Widget_User');
                        if ($user->hasLogin() && $user->pass('administrator')) {
                            echo '<span class="comment-badge" style="background-color:#f59e0b;">待审核</span>';
                        }
                    }
                    ?>
                </div>
                <span class="comment-time"><?php $comments->date('Y-m-d H:i'); ?></span>
            </div>
            <div class="comment-text-wrapper">
                <div class="comment-text">
                    <?php $comments->content(); ?>
                </div>
                <div class="comment-actions">
                    <a href="#comment-form" class="comment-reply-link" data-coid="<?php echo $comments->coid; ?>" data-author="<?php echo htmlspecialchars($comments->author); ?>">回复</a>
                </div>
            </div>
        </div>
    </div>
<?php if ($comments->children) { ?>
    <div class="comment-children">
        <?php $comments->threadedComments($options, 'threadedComments'); ?>
    </div>
<?php } ?>
<?php }
?>

<!-- 评论区 -->
<div class="comments-section" id="comments">
    <h3 class="section-title">
        💬 评论
        <span class="comment-count">(<?php echo isset($this) && $this ? $this->commentsNum() : '0'; ?>)</span>
    </h3>

    <?php if ($this->allow('comment')): ?>
    <!-- 评论表单 -->
    <div class="comment-form-wrapper" id="respond-post-<?php $this->cid(); ?>">
        <h4>发表评论</h4>
        <form id="comment-form" method="post" action="<?php $this->commentUrl(); ?>">
            <input type="hidden" name="parent" id="comment-parent" value="0">
            <input type="hidden" name="cid" value="<?php echo $this->cid; ?>">
            <?php $this->securityToken(); ?>
            <div class="form-group">
                <textarea id="comment-textarea" name="text" placeholder="说点什么吧..." required></textarea>
            </div>
            <div class="form-row">
                <input type="text" name="author" placeholder="昵称" value="<?php $this->remember('author'); ?>" required>
                <input type="email" name="mail" placeholder="邮箱" value="<?php $this->remember('mail'); ?>" required>
                <input type="url" name="url" placeholder="网站" value="<?php $this->remember('url'); ?>">
            </div>
            <div class="form-submit">
                <button type="submit" class="btn-submit">提交评论</button>
                <button type="button" id="cancel-reply" class="btn-cancel" style="display:none; margin-left:8px;">取消回复</button>
                <span class="form-tip">支持 Markdown</span>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <script>
    // 评论回复功能
    document.addEventListener('DOMContentLoaded', function() {
        var replyLinks = document.querySelectorAll('.comment-reply-link');
        var parentInput = document.getElementById('comment-parent');
        var commentTextarea = document.getElementById('comment-textarea');
        
        replyLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                var coid = this.getAttribute('data-coid');
                var author = this.getAttribute('data-author');
                
                // 设置父评论ID
                parentInput.value = coid;
                console.log('回复评论: coid=' + coid + ', author=' + author + ', parent field set to ' + parentInput.value);
                
                // 显示取消回复按钮
                var cancelReply = document.getElementById('cancel-reply');
                if (cancelReply) {
                    cancelReply.style.display = 'inline-block';
                }
                
                // 在文本框中添加引用
                var replyText = '@' + author + ' ';
                if (commentTextarea.value.indexOf(replyText) !== 0) {
                    commentTextarea.value = replyText + commentTextarea.value;
                }
                
                // 滚动到评论表单
                var commentForm = document.getElementById('comment-form');
                if (commentForm) {
                    var scrollOptions = { behavior: 'smooth', block: 'start' };
                    commentForm.scrollIntoView(scrollOptions);
                    commentTextarea.focus();
                }
                
                // 可选：高亮被回复的评论
                var targetComment = document.getElementById('comment-' + coid);
                if (targetComment) {
                    targetComment.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
                    setTimeout(function() {
                        targetComment.style.backgroundColor = '';
                    }, 2000);
                }
            });
        });
        
        // 重置回复功能
        var cancelReply = document.getElementById('cancel-reply');
        if (cancelReply) {
            cancelReply.addEventListener('click', function() {
                parentInput.value = '0';
                commentTextarea.value = '';
                // 隐藏取消回复按钮
                this.style.display = 'none';
                console.log('已取消回复，parent重置为0');
            });
        }

        // 表单提交时验证parent字段
        var commentForm = document.getElementById('comment-form');
        if (commentForm) {
            commentForm.addEventListener('submit', function(e) {
                console.log('提交评论: parent=' + parentInput.value);
                // 提交后隐藏取消回复按钮
                var cancelReply = document.getElementById('cancel-reply');
                if (cancelReply) {
                    cancelReply.style.display = 'none';
                }
                // 可选：如果parent为0，可以提示用户是否要回复某条评论
            });
        }
    });
    </script>

    <!-- 评论列表 -->
    <?php $this->comments('pageSize=0&status=approved&parentId=0')->to($comments); ?>

    <?php if ($comments->have()): ?>
    <div class="comment-list">
        <?php $comments->listComments('threadedComments', array(
            'before'        =>  '',
            'after'         =>  '',
            'beforeAuthor'  =>  '',
            'afterAuthor'   =>  '',
            'dateFormat'    =>  'Y-m-d H:i',
            'wordLimit'     =>  0,
            'avatarSize'    =>  50,
            'defaultAvatar' =>  NULL
        )); ?>
    </div>

    <!-- 评论分页 -->
    <?php $comments->pageNav('&laquo;', '&raquo;', 3, '...', array(
        'wrapTag' => 'div',
        'wrapClass' => 'pagination',
        'itemTag' => '',
        'textTag' => 'span',
        'aClass' => '',
        'currentClass' => 'current',
        'prevClass' => '',
        'nextClass' => ''
    )); ?>
    <?php else: ?>
    <div class="no-comments">
        <p>📭 还没有评论，快来抢沙发吧！</p>
    </div>
    <?php endif; ?>
</div>
