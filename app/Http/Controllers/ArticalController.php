<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use Illuminate\Support\Facades\Gate;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\category;


class ArticalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('artical.index', [
            'articles'=> Article::with('user', 'Category')->latest()->get(),
                'tags' => Tag::latest()->get()
        ]);

        // $articles = Article::with('user', 'category', 'tags')->latest()->get();

        // return view('artical.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'full_text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'tag_name' => 'required|string',
            'tags.*' => 'required|string|max:255',
        ]);
        
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('images', 'public');
        }
        
        $category = Category::firstOrCreate(['name' => $validated['name']]);
        
        $article = $request->user()->article()->create([
            'title' => $validated['title'],
            'full_text' => $validated['full_text'],
            'image_path' => $validated['image_path'] ?? null, 
            'category_id' => $category->id,
        ]); 
        
        // $tags = collect($validated['tags'])->map(function ($tagName) {
            //     return Tag::firstOrCreate(['tag_name' => $tagName]);
            // });
            
            $tagNames = explode(',', $validated['tag_name']); // Split the tags string into an array
            foreach ($tagNames as $name) {
                $name = trim($name); // Trim whitespace
                // Find or create the tag
                $tag = Tag::firstOrCreate(['tag_name' => $name]);
                // Attach the tag to the article
                $article->tags()->attach($tag->id);
            }
            
            
            
            return redirect(route('Articles.index'));
            // dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $artical)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::authorize('update', $article);
        return view('artical.edit', [
            'articles' => $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $artical)
    {
        Gate::authorize('update', $artical);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'full_text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'name' => 'required|string|max:255',
            
        ]);
        
        
        if ($request->hasFile('image')) {
            // Store the new image and get the path
            $imagePath = $request->file('image')->store('images', 'public');
            // Add the image path to the validated data array
            $validated['image_path'] = $imagePath;
        }
        
        $artical->update($validated);
        $artical->category->update([
         'name' => $validated['name'],
        ]);
        
        // dd($request->all());
        return redirect(route('Articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $artical)
    {
        Gate::authorize('delete', $artical);

        $artical->delete();
        return redirect(route('Articles.index'));
    }
}
