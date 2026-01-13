<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
include 'header.php';
?>

<div class="container">
    <div class="main-layout">
        <div class="main-content">
            <article class="page-full">
                <header class="page-header">
                    <h1 class="page-title"><?php $this->title(); ?></h1>
                    <div class="page-meta">
                        <span>📅 <?php $this->date('Y-m-d'); ?></span>
                        <span>👁 <?php echo getViews($this); ?> 阅读</span>
                    </div>
                </header>

                <div class="page-content">
                    <?php $this->content(); ?>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
