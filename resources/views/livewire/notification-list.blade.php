<!-- resources/views/livewire/notification-list.blade.php -->
<div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header with filters and actions -->
        <div class="p-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Notifications 
                    @if($unreadCount > 0)
                        <span class="ml-2 px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full">
                            {{ $unreadCount }} unread
                        </span>
                    @endif
                </h2>
            </div>
            
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <!-- Filter dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        
                        @if($filter === 'all')
                            All
                        @elseif($filter === 'unread')
                            Unread
                        @elseif($filter === 'read')
                            Read
                        @endif
                        
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" style="display: none;">
                        <div class="py-1" role="none">
                            <a wire:click="$set('filter', 'all')" @click="open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer {{ $filter === 'all' ? 'bg-gray-100 font-medium' : '' }}">
                                All notifications
                            </a>
                            <a wire:click="$set('filter', 'unread')" @click="open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer {{ $filter === 'unread' ? 'bg-gray-100 font-medium' : '' }}">
                                Unread only
                            </a>
                            <a wire:click="$set('filter', 'read')" @click="open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer {{ $filter === 'read' ? 'bg-gray-100 font-medium' : '' }}">
                                Read only
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Search input -->
                <div class="relative flex-grow sm:max-w-xs">
                    <input wire:model.debounce.300ms="search" type="text" placeholder="Search notifications..." class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    @if($search)
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button wire:click="$set('search', '')" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Actions dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Actions
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" style="display: none;">
                        <div class="py-1" role="none">
                            <a wire:click="markAllAsRead" @click="open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer {{ $unreadCount === 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $unreadCount === 0 ? 'disabled' : '' }}>
                                Mark all as read
                            </a>
                            <a wire:click="deleteAllRead" @click="open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                Delete all read notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notification List -->
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="flex p-4 {{ !$notification->is_read ? 'bg-blue-50' : '' }} hover:bg-gray-50">
                    <!-- Notification Icon -->
                    <div class="mr-4 flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full {{ !$notification->is_read ? 'bg-indigo-100' : 'bg-gray-200' }}">
                            {!! $notification->getIcon() !!}
                        </div>
                    </div>
                    
                    <!-- Notification Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between">
                            <p class="text-sm font-medium text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                {{ $notification->title }}
                            </p>
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Importance indicator -->
                                @if($notification->importance >= 4)
                                    <span class="flex h-2 w-2 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                @elseif($notification->importance == 3)
                                    <span class="inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                @endif
                                
                                <!-- Timestamp -->
                                <span class="text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        
                        <p class="mt-1 text-sm text-gray-600">
                            {{ $notification->content }}
                        </p>
                        
                        <!-- Source info -->
                        @if($notification->source_type)
                            <p class="mt-1 text-xs text-gray-500">
                                {{ ucfirst($notification->source_type) }} 
                                @if($notification->source)
                                    #{{ $notification->source->id }}
                                @endif
                            </p>
                        @endif
                        
                        <!-- Actions -->
                        <div class="mt-2 flex space-x-4">
                            <a href="{{ $notification->link }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                                View details
                            </a>
                            
                            <button wire:click="toggleReadStatus({{ $notification->id }})" class="text-xs text-gray-600 hover:text-gray-900">
                                {{ $notification->is_read ? 'Mark as unread' : 'Mark as read' }}
                            </button>
                            
                            <button wire:click="delete({{ $notification->id }})" class="text-xs text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($search)
                            No notifications matching your search "{{ $search }}".
                        @elseif($filter === 'unread')
                            You have no unread notifications.
                        @elseif($filter === 'read')
                            You have no read notifications.
                        @else
                            You have no notifications yet.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="px-4 py-3 border-t flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $notifications->links('pagination::simple-tailwind') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing 
                            <span class="font-medium">{{ $notifications->firstItem() }}</span>
                            to 
                            <span class="font-medium">{{ $notifications->lastItem() }}</span>
                            of 
                            <span class="font-medium">{{ $notifications->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show success alerts
        window.addEventListener('notifications-marked-read', event => {
            // You can use whatever notification system your app has
            alert('All notifications marked as read');
        });
        
        window.addEventListener('read-notifications-deleted', event => {
            alert('All read notifications have been deleted');
        });
    });
</script>
@endpush