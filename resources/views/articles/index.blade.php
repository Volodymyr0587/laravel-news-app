<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('All our articles') }}
            </h2>
        </div>
    </x-slot>

    <div class="container px-6 py-10 mx-auto">
        <div class="grid grid-cols-1 gap-8 mt-8 md:mt-16 md:grid-cols-2">
            @forelse ($articles as $article)
                <div class="lg:flex">
                    <img class="object-cover w-full h-56 rounded-lg lg:w-64"
                        src="{{ asset('/storage/' . $article->photo) }}" alt="{{ $article->title }}">

                    <div class="flex flex-col justify-between py-6 lg:mx-6">
                        <a href="{{ route('articleShow', $article->id) }}"
                            class="text-xl font-semibold text-gray-800 hover:underline dark:text-white ">
                            {{ $article->title }}
                        </a>
                        <p class="text-sm dark:text-white">{{ $article->created_at->diffForHumans() }}</p>

                    </div>
                </div>
            @empty
                <p class="text-3xl font-semibold text-gray-800 capitalize lg:text-4xl dark:text-white">
                    Unfortunately, there is nothing interesting yet:(
                    Be sure to come back a little later.
                </p>
            @endforelse
        </div>
        {{-- pagination  --}}
        <div class="m-8 p-2">
            {{ $articles->links() }}
        </div>
    </div>
</x-app-layout>
