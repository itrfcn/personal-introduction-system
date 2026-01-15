// 后台页面加载完成后执行
window.addEventListener('DOMContentLoaded', function() {
    // 后台导航栏滚动效果
    const adminNavbar = document.querySelector('.admin-navbar');
    if (adminNavbar) {
        // 初始化导航栏样式
        adminNavbar.style.backgroundColor = '#34495e';
        
        // 移除可能导致问题的滚动事件监听器
        // 如果需要添加滚动效果，请使用CSS类而不是直接修改样式
    }
    
    // 平滑滚动效果
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            // 只有当目标元素存在时才阻止默认行为
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // 按钮悬停效果增强
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.2)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // 后台侧边栏导航
    const sidebarLinks = document.querySelectorAll('.sidebar-menu li a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            // 移除所有激活状态
            sidebarLinks.forEach(l => l.classList.remove('active'));
            // 添加当前激活状态
            this.classList.add('active');
        });
    });
    
    // 统计卡片动画
    const statCards = document.querySelectorAll('.stat-card');
    if (statCards.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        statCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    }
    
    // 移动端菜单处理
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileNavMenu = document.querySelector('.admin-sidebar');
    
    if (mobileMenuBtn && mobileNavMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileNavMenu.classList.toggle('mobile-expanded');
            this.classList.toggle('active');
            
            // 当侧边栏展开时隐藏菜单按钮
            if (mobileNavMenu.classList.contains('mobile-expanded')) {
                this.style.display = 'none';
            } else {
                this.style.display = 'block';
            }
        });
        
        // 点击侧边栏外部关闭侧边栏
        const closeSidebar = function(event) {
            // 仅在移动端模式下且侧边栏展开时执行
            if (window.innerWidth <= 576 && mobileMenuBtn && mobileNavMenu) {
                // 确保点击的不是菜单按钮或侧边栏内容
                if (!mobileMenuBtn.contains(event.target) && !mobileNavMenu.contains(event.target)) {
                    // 确保侧边栏确实是展开状态
                    if (mobileNavMenu.classList.contains('mobile-expanded')) {
                        mobileNavMenu.classList.remove('mobile-expanded');
                        mobileMenuBtn.classList.remove('active');
                        mobileMenuBtn.style.display = 'block';
                    }
                }
            }
        };
        
        // 添加事件监听器，在函数内部检查条件
        document.addEventListener('click', closeSidebar);
        
        // 监听窗口大小变化，调整侧边栏状态
        window.addEventListener('resize', function() {
            if (window.innerWidth > 576 && mobileNavMenu) {
                // 在桌面模式下，确保侧边栏是收起状态
                mobileNavMenu.classList.remove('mobile-expanded');
                if (mobileMenuBtn) {
                    mobileMenuBtn.classList.remove('active');
                    mobileMenuBtn.style.display = 'none';
                }
            } else if (window.innerWidth <= 576 && mobileMenuBtn) {
                // 在移动端模式下，确保菜单按钮可见
                mobileMenuBtn.style.display = 'block';
            }
        });
    }
});

// 页面加载动画
window.addEventListener('load', function() {
    // 移除加载状态
    const loader = document.querySelector('.loader');
    if (loader) {
        loader.style.display = 'none';
    }
});

// 工具函数
function showAlert(message, type = 'success') {
    // 创建alert元素
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    
    // 添加到页面顶部
    document.body.insertBefore(alert, document.body.firstChild);
    
    // 设置样式
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.left = '50%';
    alert.style.transform = 'translateX(-50%)';
    alert.style.zIndex = '9999';
    alert.style.padding = '1rem 2rem';
    alert.style.borderRadius = '5px';
    alert.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.2)';
    alert.style.fontWeight = '500';
    alert.style.display = 'flex';
    alert.style.alignItems = 'center';
    alert.style.gap = '0.5rem';
    
    // 3秒后自动移除
    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            document.body.removeChild(alert);
        }, 500);
    }, 3000);
}

// 确认对话框
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}