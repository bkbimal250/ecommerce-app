<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        $parentCategories = Category::where('parent_id', null)->get();
        return view('admin.categories.create', compact('parentCategories'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->is_active = $request->has('is_active');
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $category->image = $path;
        }
        
        $category->save();
        
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }
    
    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)
            ->where(function($query) use ($category) {
                $query->whereNull('parent_id')
                      ->orWhere('parent_id', '!=', $category->id);
            })
            ->get();
        
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->is_active = $request->has('is_active');
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $path = $request->file('image')->store('categories', 'public');
            $category->image = $path;
        }
        
        $category->save();
        
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }
    
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with products');
        }
        
        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
