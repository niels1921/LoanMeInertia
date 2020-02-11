<?php

namespace App\Http\Controllers;

use App\Category;
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Categories/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'categories' => Category::query()
                ->orderBy('name')
                ->filter(\Illuminate\Support\Facades\Request::only('search', 'trashed'))
                ->paginate()
                ->only('id', 'name')

        ]);
    }

    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    public function store()
    {
        Category::create(
            \Illuminate\Support\Facades\Request::validate([
                'name' => ['required', 'max:100'],
            ])
        );

        return Redirect::route('categories')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'deleted_at' => $category->deleted_at,
            ],
        ]);
    }

    public function update(Category $category)
    {
//        dd($category);

        $category->update(
            \Illuminate\Support\Facades\Request::validate([
                'name' => ['required', 'max:100'],
            ])
        );

        return Redirect::back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return Redirect::route('categories')->with('success', 'Category deleted.');
    }

}
