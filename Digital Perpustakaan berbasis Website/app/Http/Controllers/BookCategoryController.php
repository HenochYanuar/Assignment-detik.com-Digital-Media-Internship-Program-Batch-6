<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BookCategory;
use Illuminate\Http\Request;

class BookCategoryController extends BaseController
{
    public function index()
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $categories = BookCategory::query()
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('name', 'like', $searchTerm);
            })->paginate(5);
        return view('category/index', [
            'categories' => $categories,
            'name' => $name
        ]);
    }

    public function create()
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        return view('category/form', [
            'name' => $name
        ]);
    }

    public function posts(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required | max:255'
        ]);
        $name = $request->name;
        BookCategory::create([
            'name' => $name,
        ]);
        return redirect(route('category.index'))->with('success', 'Category berhasil ditambah ');
    }

    public function confirmDelete($categroyId)
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $category = BookCategory::FindOrFail($categroyId);
        return view('category/delete-confirm', [
            'category' => $category,
            'name' => $name
        ]);
    }

    public function delete(Request $request)
    {
        $categoryId = $request->id;
        $category = BookCategory::FindOrFail($categoryId);
        $category->delete();
        return redirect(route('category.index'))->with('success', 'Category Berhasil Dihapus');
    }

    public function edit($categoryId)
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $category = BookCategory::FindOrFail($categoryId);
        return view('category/form-update', [
            'category' => $category,
            'name' => $name
        ]);
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required | max:255',
        ]);

        $categoryId = $request->id;
        $category = BookCategory::FindOrFail($categoryId);
        $category->update([
            'name' => $request->name,
        ]);
        return redirect(route('category.index'))->with('success', 'Category Berhasil Diupdate');
    }
}
