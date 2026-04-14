<div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

<style>
    .toast-notification {
        min-width: 320px;
        max-width: 450px;
        padding: 1.25rem 1.5rem;
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transform: translateX(120%);
        transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        pointer-events: auto;
        position: relative;
        overflow: hidden;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-icon {
        flex-shrink: 0;
        width: 3rem;
        height: 3rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .toast-success .toast-icon { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534; }
    .toast-error .toast-icon { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b; }
    .toast-info .toast-icon { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; }
    .toast-warning .toast-icon { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; }

    .toast-content { flex-grow: 1; }
    .toast-title { font-weight: 900; font-size: 0.95rem; color: #111827; margin-bottom: 0.25rem; letter-spacing: -0.01em; }
    .toast-message { font-size: 0.85rem; color: #4b5563; font-weight: 600; line-height: 1.4; }

    .toast-close {
        color: #9ca3af;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.75rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .toast-close:hover { 
        color: #374151; 
        background: rgba(0,0,0,0.05);
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        background: rgba(0, 0, 0, 0.03);
        width: 100%;
    }

    .toast-progress-bar {
        height: 100%;
        width: 100%;
        transform-origin: left;
    }

    .toast-success .toast-progress-bar { background: #10b981; }
    .toast-error .toast-progress-bar { background: #ef4444; }
    .toast-info .toast-progress-bar { background: #3b82f6; }
    .toast-warning .toast-progress-bar { background: #f59e0b; }
</style>

<script>
    window.showToast = function(message, type = 'info', title = null) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        
        if (!title) {
            title = type.charAt(0).toUpperCase() + type.slice(1);
            if (type === 'error') title = 'Action Failed';
            if (type === 'success') title = 'Success';
        }

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${icons[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <div class="toast-close">
                <i class="fas fa-times text-xs"></i>
            </div>
            <div class="toast-progress">
                <div class="toast-progress-bar"></div>
            </div>
        `;

        container.appendChild(toast);

        // Animate in
        setTimeout(() => toast.classList.add('show'), 100);

        const progressBar = toast.querySelector('.toast-progress-bar');
        const duration = 10000; // 10 seconds
        
        // Progress bar animation
        progressBar.animate([
            { transform: 'scaleX(1)' },
            { transform: 'scaleX(0)' }
        ], {
            duration: duration,
            fill: 'forwards',
            easing: 'linear'
        });

        // Auto remove
        const timeoutId = setTimeout(() => removeToast(toast), duration);

        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            clearTimeout(timeoutId);
            removeToast(toast);
        });

        function removeToast(t) {
            t.classList.remove('show');
            setTimeout(() => t.remove(), 600);
        }
    };
</script>
