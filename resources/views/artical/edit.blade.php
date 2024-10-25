<x-app-layout>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form  action="{{ route('Articles.update', $articles) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <textarea
            name="title"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        >{{ old('title', $articles->title) }}</textarea>
            <textarea  name="full_text"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        >{{ old('full_text', $articles->full_text) }}</textarea>
            <textarea  name="name"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        >{{ old('name', $articles->category->name) }}</textarea>

        <div class="mb-4">

            @if ($articles->image_path)
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror

                <div class="mt-3">
                    <img src="{{ asset('storage/' . $articles->image_path) }}" alt="Article Image" class="img-thumbnail">
                </div>
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            @endif  
            
            
            <div class="mt-4 space-x-2">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                <a href="{{ route('Articles.index') }}">{{ __('Cancel') }}</a>
            </div>
       </form> 
    </div>
</x-app-layout>