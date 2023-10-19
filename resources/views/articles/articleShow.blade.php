<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Article') }}
            </h2>
        </div>
    </x-slot>

    <section class="bg-white dark:bg-gray-900">
        <div class="container px-6 py-10 mx-auto">
            <h1 class="text-3xl font-semibold text-gray-800 capitalize lg:text-4xl dark:text-white">{{ $article->title }}
            </h1>

            <div class="mt-8 lg:-mx-6 lg:flex lg:items-center">
                <img class="object-cover w-full lg:mx-6 lg:w-1/2 rounded-xl h-72 lg:h-96"
                    src="{{ asset('/storage/' . $article->photo) }}" alt="">

                <div class="mt-6 lg:w-1/2 lg:mt-0 lg:mx-6 ">
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-300 md:text-sm">
                        {!! $article->content !!}
                    </p>
                </div>
            </div>
            <div class="m-4 dark:text-white">

                {{-- @foreach ($article->tags()->get() as $tag)
                    <p class="text-sm"> {{ $tag->name }}</p>
                @endforeach --}}
                <p class="text-sm"> Tags: {{ $article->tags()->get()->pluck('name')->implode(', ') }}</p>
                {{-- <p class="text-sm">Tags: {{ $article->tags()->get() }}</p> --}}
                <p>Related Articles: </p>
                @foreach ($relatedArticles as $relatedArticle)
                    <ul>
                        <li class="ml-4"><a href="{{ route('articleShow', $relatedArticle->id) }}" class="text-blue-600 hover:text-blue-400"> {{ $relatedArticle->title }}</a></li>
                    </ul>
                @endforeach

            </div>
            <div class="mt-4">
                <a href="{{ URL::previous() }}" class="text-blue-500 underline">Back</a>
            </div>

        </div>
    </section>
</x-app-layout>
