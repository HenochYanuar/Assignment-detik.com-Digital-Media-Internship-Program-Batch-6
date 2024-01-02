<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Export\ExportAuthors;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AuthorController extends BaseController
{
    public function index()
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $authors = Author::query()
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('name', 'like', $searchTerm);
            })->paginate(5);
        return view('author/index', [
            'authors' => $authors,
            'name' => $name
        ]);
    }

    public function print()
    {
        $authors = Author::all();
        $filename = "authors_" . date('Y-m-d-H-i-s') . ".pdf";
        $pdf = Pdf::loadView('author/print', ['authors' => $authors]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream($filename);
    }

    public function excel()
    {
        return Excel::download(new ExportAuthors, 'authors.xlsx');
    }

    public function create()
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        return view('author/form', [
            'name' => $name
        ]);
    }

    public function posts(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required | max:255'
        ]);

        $name = $request->name;
        Author::create([
            'name' => $name
        ]);
        return redirect(route('authors.index'))->with('success', 'Author Berhasil Ditambah');
    }

    public function confirmDelete($authorId)
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $author = Author::FindOrFail($authorId);
        return view('/author/delete-confirm', [
            'author' => $author,
            'name' => $name
        ]);
    }

    public function delete(Request $request)
    {
        $authorId = $request->id;
        $author = Author::FindOrFail($authorId);
        $author->delete();
        return redirect(route('authors.index'))->with('success', 'Author Berhasi Dihapus');
    }

    public function edit($authorId)
    {
        $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $author = Author::FindOrFail($authorId);
        return view('/author/form-update', [
            'author' => $author,
            'name' => $name
        ]);
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required | max:255',
        ]);

        $authorId = $request->id;
        $author = Author::FindOrFail($authorId)->update([
            'name' => $request->name
        ]);
        return redirect(route('authors.index'))->with('success', 'Author Berhasi Diupdate');
    }
}
