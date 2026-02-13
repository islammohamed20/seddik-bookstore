/**
 * Admin Notifications System
 * تحديثات فورية للطلبات، التسجيلات، الرسائل، والبريد
 */

class AdminNotifications {
    constructor() {
        this.notificationsContainer = null;
        this.unreadBadge = null;
        this.notificationsList = null;
        this.popupContainer = null;
        this.isOpen = false;
        this.pollingInterval = null;
        this.pollingFrequency = 15000; // 15 seconds
        this.sound = null;
        this.lastNotificationId = 0;
        
        this.init();
    }

    init() {
        this.createNotificationElements();
        this.setupEventListeners();
        this.loadNotifications();
        this.startPolling();
        this.setupSound();
    }

    createNotificationElements() {
        // Create notification bell icon in header
        const header = document.querySelector('header .flex.items-center.gap-4');
        if (!header) return;

        this.notificationsContainer = document.createElement('div');
        this.notificationsContainer.className = 'relative';
        this.notificationsContainer.innerHTML = `
            <button id="notifications-bell" class="relative p-2 text-gray-600 hover:text-indigo-600 transition-colors">
                <i class="fas fa-bell text-xl"></i>
                <span id="unread-badge" class="absolute -top-1 -left-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                    0
                </span>
            </button>
        `;

        // Create popup container
        this.popupContainer = document.createElement('div');
        this.popupContainer.id = 'notifications-popup';
        this.popupContainer.className = 'hidden absolute left-0 mt-2 w-96 bg-white rounded-lg shadow-2xl border border-gray-200 z-50';
        this.popupContainer.innerHTML = `
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-indigo-50 rounded-t-lg">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-bell text-indigo-600"></i>
                    الإشعارات
                </h3>
                <button id="mark-all-read" class="text-xs text-indigo-600 hover:text-indigo-800 transition-colors">
                    تعليم الكل كمقروء
                </button>
            </div>
            <div id="notifications-list" class="max-h-96 overflow-y-auto">
                <div class="flex items-center justify-center p-8 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                </div>
            </div>
            <div class="p-3 border-t border-gray-200 text-center bg-gray-50 rounded-b-lg">
                <a href="/admin/notifications" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    عرض جميع الإشعارات
                    <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
        `;

        this.notificationsContainer.appendChild(this.popupContainer);
        header.insertBefore(this.notificationsContainer, header.firstChild);

        this.unreadBadge = document.getElementById('unread-badge');
        this.notificationsList = document.getElementById('notifications-list');
    }

    setupEventListeners() {
        // Toggle popup
        const bellButton = document.getElementById('notifications-bell');
        if (bellButton) {
            bellButton.addEventListener('click', (e) => {
                e.stopPropagation();
                this.togglePopup();
            });
        }

        // Mark all as read
        const markAllBtn = document.getElementById('mark-all-read');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', () => this.markAllAsRead());
        }

        // Close popup when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.notificationsContainer?.contains(e.target)) {
                this.closePopup();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closePopup();
            }
        });
    }

    togglePopup() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.openPopup();
        } else {
            this.closePopup();
        }
    }

    openPopup() {
        this.popupContainer.classList.remove('hidden');
        this.isOpen = true;
        // Reload notifications when opening
        this.loadNotifications();
    }

    closePopup() {
        this.popupContainer.classList.add('hidden');
        this.isOpen = false;
    }

    async loadNotifications() {
        try {
            const response = await fetch('/admin/notifications/recent?limit=10');
            const data = await response.json();
            
            this.updateUnreadBadge(data.unread_count);
            this.renderNotifications(data.notifications);
            
            // Check for new notifications
            if (data.notifications.length > 0) {
                const latestId = data.notifications[0].id;
                if (latestId > this.lastNotificationId && this.lastNotificationId !== 0) {
                    this.playSound();
                    this.showToast(data.notifications[0]);
                }
                this.lastNotificationId = Math.max(this.lastNotificationId, latestId);
            }
        } catch (error) {
            console.error('Failed to load notifications:', error);
        }
    }

    renderNotifications(notifications) {
        if (!this.notificationsList) return;

        if (notifications.length === 0) {
            this.notificationsList.innerHTML = `
                <div class="flex flex-col items-center justify-center p-8 text-gray-400">
                    <i class="fas fa-bell-slash text-4xl mb-3"></i>
                    <p>لا توجد إشعارات</p>
                </div>
            `;
            return;
        }

        this.notificationsList.innerHTML = notifications.map(notification => `
            <div class="notification-item border-b border-gray-100 hover:bg-gray-50 transition-colors ${notification.is_read ? 'opacity-60' : ''}"
                 data-notification-id="${notification.id}">
                <a href="${notification.url || '#'}" 
                   class="block p-4"
                   onclick="adminNotifications.markAsRead(${notification.id}, event)">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center ${this.getTypeColor(notification.type)}">
                                <i class="${notification.icon} text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="font-semibold text-gray-800 text-sm">${notification.title}</p>
                                ${!notification.is_read ? '<span class="w-2 h-2 bg-indigo-600 rounded-full flex-shrink-0 mt-1"></span>' : ''}
                            </div>
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">${notification.message}</p>
                            <p class="text-gray-400 text-xs mt-2">
                                <i class="far fa-clock"></i>
                                ${notification.time_ago}
                            </p>
                        </div>
                    </div>
                </a>
                <button onclick="adminNotifications.deleteNotification(${notification.id}, event)" 
                        class="absolute left-2 top-2 p-1 text-gray-400 hover:text-red-600 transition-colors"
                        title="حذف الإشعار">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `).join('');
    }

    getTypeColor(type) {
        const colors = {
            'order': 'bg-green-500',
            'login': 'bg-blue-500',
            'registration': 'bg-purple-500',
            'email': 'bg-orange-500',
            'message': 'bg-pink-500'
        };
        return colors[type] || 'bg-gray-500';
    }

    updateUnreadBadge(count) {
        if (!this.unreadBadge) return;
        
        if (count > 0) {
            this.unreadBadge.textContent = count > 99 ? '99+' : count;
            this.unreadBadge.classList.remove('hidden');
            
            // Add pulse animation
            this.unreadBadge.classList.add('animate-pulse');
            setTimeout(() => {
                this.unreadBadge?.classList.remove('animate-pulse');
            }, 2000);
        } else {
            this.unreadBadge.classList.add('hidden');
        }
    }

    async markAsRead(notificationId, event) {
        if (event) {
            // Don't prevent default - let the link work
            event.stopPropagation();
        }

        try {
            await fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            
            // Update UI
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.add('opacity-60');
                const unreadDot = notificationElement.querySelector('.bg-indigo-600');
                if (unreadDot) unreadDot.remove();
            }
            
            // Reload to update badge
            await this.loadNotifications();
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            await fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            
            await this.loadNotifications();
        } catch (error) {
            console.error('Failed to mark all as read:', error);
        }
    }

    async deleteNotification(notificationId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!confirm('هل تريد حذف هذا الإشعار؟')) return;

        try {
            await fetch(`/admin/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            
            await this.loadNotifications();
        } catch (error) {
            console.error('Failed to delete notification:', error);
        }
    }

    startPolling() {
        // Initial load
        this.loadNotifications();
        
        // Poll every 15 seconds
        this.pollingInterval = setInterval(() => {
            this.loadNotifications();
        }, this.pollingFrequency);
    }

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }

    setupSound() {
        // Create notification sound (using Web Audio API for a simple beep)
        this.sound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2i77eWeTRALUKXi8LllHgU2jdXyzn0vBSJ1xe/glEILElyx6OyrWBUIQ5zd8sFuJAUuhM/z2YkyBxdmue3mnEwRC0+j4fCzYBoFN4/W8tGAMQUgc8Pv45ZFCw9YrOPwq1oVCEGZ2/C9bCAEK4DO8diIMQcXZbbq5qBPEgtLot/us2EdBTWL0/HSgTQGH2/A7+OZSAwNVKrh8KtiGAc8ltjwwXAfBC6BzfHVhiwFGGCz6OaeSxELSp3d7bJfHAU0i9Lx0oExBh5tv+/kmUwODFGn4O+sYRkGOJHV8MFvHwQugc7y2Yg0Bhdmtu3moU0SC0qc3PKzYBwFMovT8dOAMQYfb7/w5JpNDgtQpuHvq2IZBT2O1fDAbh4ELoHO8tmIPAYVYrTr5qBNEgxKnN3xs2AcBTSL0vHUgTEGH2+/8OSaTQ4LUKbh76tiFQU5jdXwy3AfBC6BzvLZiToGFWG06+aiThILSZzc8LNgHAU0i9Px04ExBh9vv+/kmnEOC0+l4PCtYhkFPY7V8MpwHwQtgc7y2YlBBhVhtevlo04SCkmb2+yzYBwFM4rR8NSBMQYfb7/v5JpxDgtPpeHwrGIZBTyN1fDKcB8ELYHO8tmJQQYVYbXr5aNPEgpJm9vss2AcBTOK0fDUgTEGH2+/7+SacQ4LT6Xg8KxiGQU8jtXwynAfBC2Bze/YjD4HFWCx6OiiUA8KSJnZ6rFfHgYzitDx1YE0Bh5vv+7hmksPDU6k4PCqYRsFPY3S8c1xHwQsgczu15BDCBlgsOjnoFESC0iY2euvanAeBTKJz/HWgjQGHm++7+OYSA0MUqjh8LBkGQU9jdPx0HYhBSt/zO/ajjoFGF606OmgUhIKR5jZ6rd0IAYxh8/w14Q4Bx1tu+zjmkoPDVCm4fCvZRsFPIzS8dGAMgYbbrzv5JdIDANXrOXwr2MZBT+N0/HQcR0FKoPK79iQOwcbbLzs4plKDw1Ppt/vrmUaBTyN0vHSgDAGHG+77+OWSAsEWKzk8K5jGQY+jdLx0HEdBSeEy+/XkD4GGGy77OKZSg8NTqXg769lGgU8jdPx0X4xBhxuu+/jl0gLBFis5PCuYxkGPo3S8c9vHQUng8zv2I9BBhdrtuvimEsPDU6k4O+vZRoFO43T8dF9MQYcb7vv45ZICwNYrOTwrmQZBj2M0vHPcB0FJ4PK79iPQgYXa7Xs45hKDw1Npe/vr2QaBTyM0/HQfjIGHG677+OWSAsEWK3k8K9jGQY9jdLxz3AcBSeEyu/YkEIHF2q17OOYSg8NTaXv769kGgU8jNPx0H0yBhtvv+/jlkgLBFit5PCvYxkGPo3S8c9wHQQng8ru2I9DBhZrtuzjmUoPDU6l4O+vZRoFO43S8dB+MQYcb7vv45ZICwNYrOTwr2MZBT2N0fHOcB0EJ4PK7tiPQgYWa7bs45lKDw1Npe/vr2UaBTuN0vHQfjEGHG677+OWSAsEWKzk8K9jGQU9jdHxz3AdBCeDy+/YjkAGGGy77OKZSg8OTqbg769lGgU8jNLx0X4xBhtuu+/jl0gLBFis5PCuYxkFPY3R8c5wHQQnhMvv14xABhlstOrjmEoPDU6l4O6vZRoFPY3S8c99MgYbbrvv45dICwRYq+TwrmQZBT2N0vHOcB0FJ4PM79iPQwYXa7Xr4plKDw5Opd/vrmUZBTyN0vHPfjEGHG+77+OWSAsEV63k8LBkGQU9jNLxzXAcBSeDzO/Yj0QGFmq17OOYSg8OTKXR');
    }

    playSound() {
        if (this.sound) {
            this.sound.play().catch(err => console.log('Could not play notification sound:', err));
        }
    }

    showToast(notification) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 left-4 bg-white rounded-lg shadow-2xl border-l-4 p-4 max-w-sm z-50 transform translate-y-0 transition-all duration-300';
        toast.style.borderLeftColor = this.getTypeColorHex(notification.type);
        
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center ${this.getTypeColor(notification.type)}">
                        <i class="${notification.icon} text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 text-sm">${notification.title}</p>
                    <p class="text-gray-600 text-xs mt-1">${notification.message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.transform = 'translateY(100%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    getTypeColorHex(type) {
        const colors = {
            'order': '#10b981',
            'login': '#3b82f6',
            'registration': '#8b5cf6',
            'email': '#f97316',
            'message': '#ec4899'
        };
        return colors[type] || '#6b7280';
    }
}

// Initialize when DOM is ready
let adminNotifications;
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        adminNotifications = new AdminNotifications();
    });
} else {
    adminNotifications = new AdminNotifications();
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (adminNotifications) {
        adminNotifications.stopPolling();
    }
});
