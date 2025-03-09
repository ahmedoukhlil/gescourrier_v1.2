<!-- resources/views/livewire/notification-settings.blade.php -->
<div class="bg-white shadow-md rounded-lg">
    <!-- Header -->
    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Notification Preferences</h2>
        <p class="text-sm text-gray-600 mt-1">Customize how and when you receive notifications</p>
    </div>
    
    <!-- Success message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6 mt-4">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="p-6 space-y-6">
        <!-- Notification Channels -->
        <div>
            <h3 class="text-md font-medium text-gray-700 mb-3">Notification Channels</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="email-notifications" wire:model="emailNotifications" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email-notifications" class="font-medium text-gray-700">Email Notifications</label>
                        <p class="text-gray-500">Receive notifications via email</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="browser-notifications" wire:model="browserNotifications" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="browser-notifications" class="font-medium text-gray-700">Browser Notifications</label>
                        <p class="text-gray-500">Receive desktop notifications when the browser is open</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="daily-digest" wire:model="dailyDigest" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="daily-digest" class="font-medium text-gray-700">Daily Digest</label>
                        <p class="text-gray-500">Receive a daily summary of notifications instead of individual emails</p>
                        
                        @if($dailyDigest)
                            <div class="mt-2">
                                <label for="daily-digest-time" class="block text-sm text-gray-700">Delivery time</label>
                                <input type="time" id="daily-digest-time" wire:model="dailyDigestTime" class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notification Types -->
        <div class="border-t pt-6">
            <h3 class="text-md font-medium text-gray-700 mb-3">Notification Types</h3>
            <p class="text-sm text-gray-500 mb-4">Select which types of notifications you want to receive</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($availableTypes as $type => $description)
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="type-{{ $type }}" wire:model="notificationTypes" value="{{ $type }}" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="type-{{ $type }}" class="font-medium text-gray-700">{{ $description }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Priority Filter -->
        <div class="border-t pt-6">
            <h3 class="text-md font-medium text-gray-700 mb-3">Importance Filter</h3>
            <p class="text-sm text-gray-500 mb-4">Only receive notifications with the selected importance level or higher</p>
            
            <div class="w-full" x-data="{importance: @entangle('minimumImportance')}">
                <input type="range" min="1" max="5" wire:model="minimumImportance" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                
                <div class="flex justify-between text-xs text-gray-600 px-1 mt-2">
                    @foreach($importanceLevels as $level => $label)
                        <div class="relative">
                            <div class="absolute -left-2">{{ $level }}</div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-3 text-center">
                    <p class="text-sm font-medium text-indigo-600">
                        Current setting: {{ $importanceLevels[$minimumImportance] ?? 'All notifications' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Pause Notifications -->
        <div class="border-t pt-6" x-data="{isPaused: {{ $pauseUntil ? 'true' : 'false' }}}">
            <h3 class="text-md font-medium text-gray-700 mb-3">Pause Notifications</h3>
            <p class="text-sm text-gray-500 mb-4">Temporarily stop receiving notifications</p>
            
            @if($pauseUntil && $pauseUntil->isFuture())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Notifications are paused until {{ $pauseUntil->format('F j, Y \a\t g:i A') }}
                            </p>
                            <div class="mt-2">
                                <button wire:click="resumeNotifications" type="button" class="text-sm font-medium text-yellow-700 hover:text-yellow-600">
                                    Resume notifications now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <label for="pause-duration" class="block text-sm font-medium text-gray-700">Pause for</label>
                            <select id="pause-duration" wire:model="pauseDuration" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Don't pause</option>
                                @foreach($pauseDurationOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    @if($showCustomPause)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="custom-pause-date" class="block text-sm font-medium text-gray-700">Until date</label>
                                <input type="date" id="custom-pause-date" wire:model="customPauseDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="custom-pause-time" class="block text-sm font-medium text-gray-700">Until time</label>
                                <input type="time" id="custom-pause-time" wire:model="customPauseTime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        
        <!-- Save Button -->
        <div class="border-t pt-6 flex justify-end">
            <button wire:click="save" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span wire:loading.remove wire:target="save">Save preferences</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </div>
</div>

<!-- Browser notification permission request script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show a saved message when preferences are successfully saved
        window.addEventListener('preferences-saved', event => {
            const message = document.querySelector('[x-data="{ isVisible: false }"]');
            if (message) {
                message.__x.$data.isVisible = true;
                setTimeout(() => {
                    message.__x.$data.isVisible = false;
                }, 3000);
            }
        });
    });
</script>