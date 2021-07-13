<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::when($request->search, function ($q) use ($request){
            return $q->whereTranslationLike('name',  '%' .$request->search .'%');
        })->latest()->paginate(5);

        return view('dashboard.categories.index',compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    public function store(Request $request)
    {   $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations', 'name')]];
            $rules += [$locale . '.description' => ['required', Rule::unique('category_translations', 'description')]];

        }

        $request->validate($rules);

        Category::create($request->except(['_token']));
        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');
    }


    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('dashboard.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations', 'name')->ignore($category->id, 'category_id')]];
            $rules += [$locale . '.description' => ['required', Rule::unique('category_translations', 'description')->ignore($category->id, 'category_id')]];

        }

        $request->validate($rules);

        $category->update($request->all());
        session()->flash('success',__('site.update_successfully'));
        return redirect()->route('dashboard.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.categories.index');
    }
}
