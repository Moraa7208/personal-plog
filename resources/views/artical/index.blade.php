<x-app-layout>
    {{-- @dump($articles); --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Articles') }}
        </h2>
    </x-slot>
    {{-- <div class="max-w-4xl mx-auto my-8 p-6 bg-white rounded-lg shadow-lg"> --}}
    <form action="{{route('Articles.store')}}" method="POST" enctype="multipart/form-data" class="bg-white  max-w-4xl mx-auto my-8 p-6 rounded-lg shadow-lg w-full max-w-lg">
      <!-- Add CSRF token for Laravel -->
      @csrf
      <div class="mb-4">
          <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
          <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>
      <div class="mb-4">
          <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
          <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>
      <div class="mb-6">
          <label for="image" class="block text-gray-700 text-sm font-bold mb-2">image</label>
          <input type="file" name="image" id="image">

        </div>
      <div class="mb-6">
          <label for="full_text" class="block text-gray-700 text-sm font-bold mb-2">Article</label>
          <textarea name="full_text" id="full_text" rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
      </div>
      <div class="mb-4">
        <label for="tag_name" class="block text-gray-700 text-sm font-bold mb-2">Tags (separate with commas)</label>
        <textarea name="tag_name" id="tag_name" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="e.g. tag1, tag2, tag3"></textarea>
    </div>

      <div class="flex items-center justify-between">
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
      </div>
  </form>

    <div >
        @foreach ($articles as $article)
            <div class="bg-white flex space-x-2  max-w-4xl mx-auto my-8 p-6 rounded-lg shadow-lg w-full max-w-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ $article->user->name }}</span>
                            <span class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">#{{ $article->category->name }}</span>
                            @if($article->tags->isNotEmpty())
                            <p>Tags:
                                @foreach($article->tags as $tag)
                                    <span>{{ $tags->name }}</span>
                                @endforeach
                            </p>
                        @endif
                            <small class="ml-2 text-sm text-gray-600">{{ $article->created_at->format('j M Y, g:i a') }}</small>
                            @unless ($article->created_at->eq($article->updated_at))
                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                          @endunless
                        </div>
                        @if ($article->user->is(auth()->user()))
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('Articles.edit', $article->id)">
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('Articles.destroy', $article) }}">
                                    @csrf
                                    @method('delete')
                                    <x-dropdown-link :href="route('Articles.destroy', $article)" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </form>

                            </x-slot>
                        </x-dropdown>
                    @endif
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>
                    <p class="mt-4 text-lg text-gray-900">{{ $article->full_text }}</p>
                    @if($article->image_path)
                    <img class="rounded-lg border border-gray-300 shadow-lg object-cover object-center w-32 h-32 sm:w-48 sm:h-48 md:w-64 md:h-64 lg:w-80 lg:h-80 xl:w-96 xl:h-96" src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}">
                    @error('image_path')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
