<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
include 'header.php';
?>

<div class="container">
    <div class="error-page">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">页面未找到</h1>
            <p class="error-message">
                抱歉，您访问的页面不存在或已被删除。
            </p>
            <div class="error-actions">
                <a href="<?php $this->options->siteUrl(); ?>" class="btn-primary">返回首页</a>
                <button onclick="history.back()" class="btn-secondary">返回上一页</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
