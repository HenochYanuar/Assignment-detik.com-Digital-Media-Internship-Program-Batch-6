<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\User;
use App\Models\BookAuthor;
use App\Models\BookFile;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Export\ExportBooks;
use App\Models\BookCategory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

// use Barryvdh\DomPDF\PDF as DomPDFPDF;

class BookController extends BaseController
{
    /**
     * Fungsi untuk menampilkan semua data books
     */
    public function index()
    {
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;

        if ($firstUser->role == 'user'){
            $books = Book::query()
            ->with(['publisher', 'category', 'authors'])
            ->where('id_user', $firstUser->id)
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('title', 'like', $searchTerm)
                ->orWhere('code', 'like', $searchTerm)
                ->orWhereHas('category', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('publisher', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('authors', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                });
            })->paginate(10);
        } else {
            $books = Book::query()
            ->with(['publisher', 'category', 'authors'])
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('title', 'like', $searchTerm)
                ->orWhere('code', 'like', $searchTerm)
                ->orWhereHas('category', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('publisher', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('authors', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                });
            })->paginate(10);
        }
        session()->flashInput(request()->input());
        return view('books/index', [
            'books' => $books,
            'name' => $name
        ]);
    }

    public function print()
    {
        $books = Book::query()
            ->with(['publisher', 'authors'])
            ->when(request('search'), function ($query) {
                $searchTerm = '%' . request('search') . '%';
                $query->where('title', 'like', $searchTerm)
                    ->orWhere('code', 'like', $searchTerm)
                    ->orWhereHas('publisher', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('authors', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm);
                    });
            })->get();
        $filename = "books_" . date('Y-m-d-H-i-s') . ".pdf";
        $pdf = Pdf::loadView('books/print', ['books' => $books]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream($filename);
    }

    public function printDetail($bookId)
    {
        $book = Book::findOrFail($bookId);
        $filname = "book_" . $book->code . "_" . date('Y-m-d H:i:s') . ".pdf";
        $pdf = Pdf::loadView('books/printDetail', ['book' => $book]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream($filname);
    }

    public function excel()
    {
        return Excel::download(new ExportBooks, 'books.xlsx');
    }

    /**
     * Function untuk menampilkan form tambah buku
     */
    public function create()
    {
        // $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $id_user = $firstUser->id;

        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = BookCategory::all();
        return view('books/form', [
            'publishers' => $publishers,
            'authors' => $authors,
            'categories' => $categories,
            'name' => $name,
            'id_user' => $id_user
        ]);
    }

    /**
     * Function untuk memproses data buku ke database
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'code' => 'required | max:4 | unique:books,code',
                'title' => 'required | max:255',
                'id_category' => 'required',
                'id_publisher' => 'required',
                'cover_image' => 'required|file|mimes:jpeg,png,jpg|max:2048',
                'pdf_file' => 'required|file|mimes:pdf|max:10000',
            ]);

            $cover = $request->file('cover_image');
            $pdf = $request->file('pdf_file');

            $destinationCoverPath = public_path('/assets/dist/img/cover');
            $destinationPdfPath = public_path('/assets/dist/pdf');

            $cover_path = '/assets/dist/img/cover/' . $cover->hashName();
            $cover_mime = $cover->getClientMimeType();
            $cover_image = file_get_contents($cover);
            $cover->move($destinationCoverPath, $cover->hashName());
            $pdf_path = '/assets/dist/pdf/' . $pdf->hashName();
            $pdf_mime = $pdf->getClientMimeType();
            $pdf_file = file_get_contents($pdf);
            $pdf->move($destinationPdfPath, $pdf->hashName());
            $file = BookFile::create([
                'cover_path' => $cover_path,
                'cover_mime' => $cover_mime,
                'cover_image' => $cover_image,
                'pdf_path' => $pdf_path,
                'pdf_mime' => $pdf_mime,
                'pdf_file' => $pdf_file
            ]);

            $code = $request->code;
            $title = $request->title;
            $id_category = $request->id_category;
            $id_publisher = $request->id_publisher;
            $id_user = $request->id_user;
            $book = Book::create([
                'code' => $code,
                'title' => $title,
                'id_file' => $file->id,
                'id_category' => $id_category,
                'id_publisher' => $id_publisher,
                'id_user' => $id_user,
            ]);
            foreach ($request->author as $authorId) {
                BookAuthor::create([
                    'id_book' => $book->id,
                    'id_author' => $authorId,
                ]);
            }
            DB::commit();
            return redirect(route('books.index'))->with('success', 'Buku berhasil ditambah ');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect(route('books.index'))->with('errors', 'Buku Gagal ditambah');
        }
    }

    public function confirmDelete($bookId)
    {
        #ambill data dari Id
        // $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $book = Book::findOrFail($bookId);
        return view('books/delete-confirm', [
            'book' => $book,
            'name' => $name
        ]);
    }

    public function delete(Request $request)
    {
        $bookId = $request->id;
        $fileId = $request->id_file;
        $book = Book::findOrFail($bookId);
        $file = BookFile::findOrFail($fileId);
        $book->delete();
        $file->delete();
        return redirect(route('books.index'))->with('success', 'Buku Berhasil Dihapus');
    }

    public function edit($bookId)
    {
        #ambil data buku by Id
        // $this->superadminOnly();
        $token = session('token');
        $loggedInUser = User::where('token', $token)->get();
        $firstUser = $loggedInUser->first();
        $name = $firstUser->name;
        $name = $loggedInUser[0]->name;
        $id_user = $firstUser->id;
        $book = Book::findOrFail($bookId);
        $publishers = Publisher::all();
        $categories = BookCategory::all();
        return view('books/form-update', [
            'book' => $book,
            'publishers' => $publishers,
            'categories' => $categories,
            'name' => $name,
            'id_user'=>$id_user
        ]);
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required | max:255',
            'id_category' => 'required',
            'id_publisher' => 'required',
            'cover_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf',
        ]);

        $cover = $request->file('cover_image');
        $pdf = $request->file('pdf_file');

        $bookFile_id = $request->id_file;

        if ($cover != null) {
            $destinationCoverPath = public_path('/assets/dist/img/cover');

            $cover_path = '/assets/dist/img/cover/' . $cover->hashName();
            $cover_mime = $cover->getClientMimeType();
            $cover_image = file_get_contents($cover);
            $cover->move($destinationCoverPath, $cover->hashName());

            $file = BookFile::findOrFail($bookFile_id)->update([
                'cover_path' => $cover_path,
                'cover_mime' => $cover_mime,
                'cover_image' => $cover_image
            ]);
        }

        if ($pdf != null) {
            $destinationPdfPath = public_path('/assets/dist/pdf');
            $pdf_path = '/assets/dist/pdf/' . $pdf->hashName();
            $pdf_mime = $pdf->getClientMimeType();
            $pdf_file = file_get_contents($pdf);
            $pdf->move($destinationPdfPath, $pdf->hashName());

            $file = BookFile::findOrFail($bookFile_id)->update([
                'pdf_path' => $pdf_path,
                'pdf_mime' => $pdf_mime,
                'pdf_file' => $pdf_file
            ]);
        }

        $bookId = $request->id;
        $book = Book::findOrFail($bookId)->update([
            'id_user' => $request->id_user,
            'id_file' => $request->id_file,
            'title' => $request->title,
            'id_category' => $request->id_category,
            'id_publisher' => $request->id_publisher
        ]);
        return redirect(route('books.index'))->with('success', 'Buku Berhasil Diubah');
    }
}
