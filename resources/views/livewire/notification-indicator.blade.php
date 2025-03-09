<!-- resources/views/livewire/notification-indicator.blade.php -->
<div class="relative" x-data="{ 
    init() {
        // Setup browser notifications
        if ('Notification' in window) {
            if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                this.$refs.notificationBtn.classList.add('animate-pulse');
            }
        }
        
        // Handle browser notification click event
        window.addEventListener('show-browser-notification', event => {
            this.showBrowserNotification(event.detail);
        });
    },
    async requestPermission() {
        if ('Notification' in window) {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                this.$refs.notificationBtn.classList.remove('animate-pulse');
            }
        }
    },
    showBrowserNotification(data) {
        if ('Notification' in window && Notification.permission === 'granted') {
            const notification = new Notification(data.title, {
                body: data.body,
                icon: data.icon
            });
            
            notification.onclick = function() {
                window.open(data.link, '_blank');
                notification.close();
            };
        }
    }
}" @click.away="$wire.closeDropdown()">
    <!-- Notification Bell Icon -->
    <button 
        x-ref="notificationBtn"
        @click="$wire.toggleDropdown(); requestPermission()"
        class="relative p-1 text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
        <span class="sr-only">View notifications</span>
        <!-- Bell Icon -->
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        <!-- Unread Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 flex h-5 w-5">
                <span class="animate-ping absolute h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative rounded-full h-5 w-5 bg-red-500 flex items-center justify-center text-xs text-white">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    @if($showDropdown)
        <div class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <div class="py-1">
                <!-- Header -->
                <div class="flex justify-between items-center px-4 py-2 border-b">
                    <h3 class="text-lg font-semibold text-gray-700">Notifications</h3>
                    @if($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="text-xs text-indigo-600 hover:text-indigo-800">
                            Mark all as read
                        </button>
                    @endif
                </div>
                
                <!-- Notification List -->
                <div class="max-h-64 overflow-y-auto">
                    @forelse($notifications as $notification)
                        <a 
                            href="{{ $notification['link'] }}"
                            wire:click.prevent="markAsRead({{ $notification['id'] }}); $dispatch('close-dropdown'); window.location.href='{{ $notification['link'] }}'"
                            class="block px-4 py-3 hover:bg-gray-50 transition duration-150 ease-in-out border-b last:border-b-0 {{ !$notification['is_read'] ? 'bg-blue-50' : '' }}"
                        >
                            <div class="flex items-start">
                                <!-- Icon based on notification type -->
                                <div class="mr-3 flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full {{ $notification['is_read'] ? 'bg-gray-200' : 'bg-indigo-100' }}">
                                        {!! str_replace('currentColor', $notification['is_read'] ? 'currentColor' : '#4F46E5', $this->getNotificationIcon($notification)) !!}
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 {{ !$notification['is_read'] ? 'font-bold' : '' }}">
                                        {{ $notification['title'] }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $notification['content'] }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                    </p>
                                </div>
                                
                                <!-- "New" indicator -->
                                @if(!$notification['is_read'])
                                    <div class="flex-shrink-0 ml-2">
                                        <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-gray-500">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>No notifications yet</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Footer -->
                <div class="border-t px-4 py-2 flex justify-between">
                    <button wire:click="viewAll" class="text-sm text-indigo-600 hover:text-indigo-800">
                        View all
                    </button>
                    
                    <button wire:click="goToSettings" class="text-sm text-gray-600 hover:text-gray-800">
                        Settings
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>