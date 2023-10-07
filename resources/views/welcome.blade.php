{{-- <x-app-layout>
    <!-- component -->
    <section class="bg-white dark:bg-gray-900">
        <div class="container px-6 py-10 mx-auto">
            <h1 class="text-3xl font-semibold text-gray-800 capitalize lg:text-4xl dark:text-white">
                Latest Articles
            </h1>

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


                        </div>
                    </div>
                @empty
                    <p class="text-3xl font-semibold text-gray-800 capitalize lg:text-4xl dark:text-white">
                        Unfortunately, there is nothing interesting yet :(
                        Be sure to come back a little later or create your own event.
                    </p>
                @endforelse
            </div>
        </div>
        {{-- pagination  --}}
        {{-- <div class="m-2 p-2">
            {{ $articles->links() }}
        </div>
    </section>
</x-app-layout> --}}
