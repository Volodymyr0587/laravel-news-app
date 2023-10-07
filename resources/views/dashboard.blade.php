<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                @admin
                    <div class="p-6">
                        {{ __("You're logged in as Administrator! Click 'Articles' link to manage content.") }}
                    </div>
                @else
                    <div class="p-6">
                        {{ __("You're logged in as guest user. Click logo link to see our content.") }}
                    </div>
                @endadmin
            </div>
        </div>
    </div>
</x-app-layout>
