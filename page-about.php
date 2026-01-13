<?php
/**
 * 关于页面
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
                <article class="page-full about-page">
                    <!-- 页面标题 -->
                    <div class="tags-header">
                        <h1 class="tags-title">关于我</h1>
                        <p class="tags-meta">个人简介与信息</p>
                    </div>

                    <!-- 页面内容 -->
                    <div class="page-content">
                        <div class="intro-content">
                            <?php $this->content(); ?>
                        </div>
                    </div>

                    <!-- 个人信息卡片 -->
                    <div class="about-section">
                        <div class="section-header">
                            <h2 class="section-title">个人信息</h2>
                            <div class="section-line"></div>
                        </div>
                        <div class="info-grid">
                            <!-- 作者信息 -->
                            <div class="info-card info-card-primary">
                                <div class="info-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <div class="info-card-content">
                                    <h3>作者</h3>
                                    <p class="info-value"><?php $this->author(); ?></p>
                                    <span class="info-badge">博主</span>
                                </div>
                            </div>

                            <!-- 技术栈 -->
                            <div class="info-card info-card-secondary">
                                <div class="info-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="16 18 22 12 16 6"/>
                                        <polyline points="8 6 2 12 8 18"/>
                                    </svg>
                                </div>
                                <div class="info-card-content">
                                    <h3>技术栈</h3>
                                    <div class="tech-skills">
                                        <span>PHP</span>
                                        <span>MySQL</span>
                                        <span>HTML5</span>
                                        <span>CSS3</span>
                                        <span>JavaScript</span>
                                    </div>
                                    <span class="info-badge">8项</span>
                                </div>
                            </div>

                            <!-- 联系方式 -->
                            <div class="info-card info-card-success">
                                <div class="info-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                </div>
                                <div class="info-card-content">
                                    <h3>联系邮箱</h3>
                                    <p class="info-value"><?php echo $this->options->socialEmail ?: '暂无'; ?></p>
                                    <span class="info-badge">邮箱</span>
                                </div>
                            </div>

                            <!-- GitHub -->
                            <?php if ($this->options->socialGithub): ?>
                            <div class="info-card info-card-dark">
                                <div class="info-card-icon">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.164 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.605-3.369-1.343-3.369-1.343-.454-1.156-.554-1.463-.554-1.463-.909-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12c0-5.523-4.477-10-10-10z"/>
                                    </svg>
                                </div>
                                <div class="info-card-content">
                                    <h3>GitHub</h3>
                                    <a href="<?php echo $this->options->socialGithub; ?>" target="_blank" rel="noopener" class="github-link">查看主页 →</a>
                                    <span class="info-badge">开源</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            </div>

            <!-- 侧边栏 -->
            <aside class="sidebar">
                <?php include 'sidebar.php'; ?>
            </aside>
        </div>
    </div>
</div>

<!-- 关于页面样式 -->
<style>
.about-page {
    padding: 40px;
}

/* 页面内容区域 */
.page-content {
    margin-bottom: 40px;
}

.intro-content {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 28px;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-md);
}

.intro-content p {
    font-size: 16px;
    line-height: 1.9;
    color: var(--text-secondary);
    margin-bottom: 20px;
}

.intro-content p:last-child {
    margin-bottom: 0;
}

/* 信息卡片区域 */
.about-section {
    margin-bottom: 32px;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
}

.section-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0;
    padding-right: 20px;
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-line {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--accent-primary) 0%, transparent 100%);
    border-radius: 1px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.info-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 24px;
    border: 2px solid transparent;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s;
}

.info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.info-card:hover::before {
    opacity: 1;
}

.info-card-primary {
    border-color: rgba(59, 130, 246, 0.2);
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.03) 0%, rgba(96, 165, 250, 0.03) 100%);
}

.info-card-primary:hover {
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 12px 24px rgba(59, 130, 246, 0.2);
}

.info-card-secondary {
    border-color: rgba(147, 51, 234, 0.2);
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.03) 0%, rgba(192, 132, 252, 0.03) 100%);
}

.info-card-secondary:hover {
    border-color: rgba(147, 51, 234, 0.5);
    box-shadow: 0 12px 24px rgba(147, 51, 234, 0.2);
}

.info-card-success {
    border-color: rgba(34, 197, 94, 0.2);
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.03) 0%, rgba(74, 222, 128, 0.03) 100%);
}

.info-card-success:hover {
    border-color: rgba(34, 197, 94, 0.5);
    box-shadow: 0 12px 24px rgba(34, 197, 94, 0.2);
}

.info-card-dark {
    border-color: rgba(71, 85, 105, 0.2);
    background: linear-gradient(135deg, rgba(71, 85, 105, 0.03) 0%, rgba(100, 116, 139, 0.03) 100%);
}

.info-card-dark:hover {
    border-color: rgba(71, 85, 105, 0.5);
    box-shadow: 0 12px 24px rgba(71, 85, 105, 0.2);
}

.info-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    position: relative;
    z-index: 1;
}

.info-card-primary .info-card-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.info-card-secondary .info-card-icon {
    background: linear-gradient(135deg, #9333ea 0%, #c084fc 100%);
    box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
}

.info-card-success .info-card-icon {
    background: linear-gradient(135deg, #22c55e 0%, #4ade80 100%);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.info-card-dark .info-card-icon {
    background: linear-gradient(135deg, #475569 0%, #64748b 100%);
    box-shadow: 0 4px 12px rgba(71, 85, 105, 0.3);
}

.info-card-icon svg {
    width: 24px;
    height: 24px;
    color: white;
}

.info-card-content {
    position: relative;
    z-index: 1;
}

.info-card-content h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 10px 0;
}

.info-value {
    font-size: 16px;
    color: var(--text-secondary);
    margin-bottom: 12px;
    font-weight: 500;
}

.github-link {
    display: inline-block;
    color: var(--accent-primary);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.github-link:hover {
    color: var(--accent-secondary);
    text-decoration: underline;
}

.info-badge {
    display: inline-block;
    padding: 4px 12px;
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tech-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
}

.tech-skills span {
    padding: 5px 10px;
    background: var(--bg-secondary);
    border-radius: 5px;
    font-size: 12px;
    color: var(--text-primary);
    font-weight: 500;
    transition: all 0.2s;
}

.tech-skills span:hover {
    background: var(--accent-primary);
    color: white;
    transform: scale(1.05);
}

/* 响应式 */
@media (max-width: 1024px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .about-page {
        padding: 20px;
    }

    .intro-content {
        padding: 24px;
    }

    .section-title {
        font-size: 22px;
        padding-right: 16px;
    }

    .info-grid {
        gap: 12px;
    }

    .info-card {
        padding: 20px;
    }

    .info-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
    }

    .info-card-icon svg {
        width: 22px;
        height: 22px;
    }

    .info-card-content h3 {
        font-size: 17px;
    }

    .info-value {
        font-size: 15px;
    }

    .tech-skills span {
        padding: 4px 10px;
        font-size: 12px;
    }
}
</style>

<?php include 'footer.php'; ?>
