<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Articles') }}
            </h2>

            <a href="{{ route('admin.create') }}"
                class="group relative text-center pt-2 overflow-hidden rounded-lg bg-slate-100 dark:bg-slate-700 text-lg shadow-md sm:w-36 sm:h-10 md:w-48 md:h-12 lg:h-16 xl:h-16">
                <div
                    class="absolute inset-0 w-3 bg-amber-400 transition-all duration-[250ms] ease-out group-hover:w-full">
                </div>
                <span class="relative m-6 text-black group-hover:text-white">New Article</span>
            </a>
            {{-- <a href="{{ route('events.create') }}" class="dark:text-white hover:text-slate-200">New Event</a> --}}

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-lg text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Updated At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($articles as $article)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $article->title }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $article->created_at }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $article->updated_at }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                         <a href="#" {{--{{ route('events.edit', $event) }} --}}
                                            class="text-green-400 hover:text-green-600">Edit</a>
                                        <form method="POST" action="{{ route('admin.destroy', $article) }}"
                                            class="text-red-400 hover:text-red-600"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No articles found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
