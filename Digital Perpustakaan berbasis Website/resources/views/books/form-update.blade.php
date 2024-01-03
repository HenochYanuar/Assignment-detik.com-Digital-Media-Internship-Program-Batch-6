@extends('layout.main')
@section('title', 'Update Data Buku')
@section('username', $name)
@section('content')
{{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{route('books.update')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $book->id }}">
            <input type="hidden" name="id_file" value="{{ $book->id_file }}">
            <input type="" name="id_user" value="{{ $id_user }}">
            <div class="form-group">
                <label for="">Kode</label>
                <input class="form-control" type="text" value="{{ $book->code }}" name="code" required readonly disabled />
            </div>
            <div class="form-group">
                <label for="">Judul</label>
                <input class="form-control @error('title') is-invalid @enderror" type="text" value="{{ $book->title }}" name="title"  />
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Category</label>
                <select name="id_category" class="form-control @error('id_category') is-invalid @enderror">
                    <option value="" disabled selected>Pilih Category</option>
                    @foreach ($categories as $c)
                        <option {{ $c -> id == $book->id_category ? 'selected' : '' }} value="{{ $c->id }}">
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                @error('id_category')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Publisher</label>
                <select name="id_publisher" class="form-control @error('id_publisher') is-invalid @enderror">
                    @foreach ($publishers as $p)
                        <option {{ $p -> id == $book->id_publisher ? 'selected' : '' }} value="{{ $p -> id }}">
                            {{ $p -> name }}
                        </option>
                    @endforeach
                </select>
                @error('id_publisher')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Cover Buku</label> <br>
                <img style="width: 200px" src="{{ $book->book_file->cover_path }}" alt=""><br> <br>
                <input class="form-control @error('cover_image') is-invalid @enderror" value="{{ old('cover_image') }}"
                    type="file" name="cover_image" accept="image/jpeg, image/png, image/jpg" />
                @error('cover_image')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">File Buku</label> <br>
                <a class="btn btn-outline-primary" href="{{ $book->book_file->pdf_path }}" target="_blank">Link Buku</a> <br>
                <input class="form-control @error('pdf_file') is-invalid @enderror" value="{{ old('pdf_file') }}"
                    type="file" name="pdf_file" accept="application/pdf" />
                @error('pdf_file')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <button class="btn btn-warning" type="button" onclick="location.href='{{ route('books.index') }}'">
                <i class="fas fa-caret-left"></i> Kembali
            </button>
            <button class="btn btn-success" type="submit">
                <i class="fa fa-save"></i> Update
            </button>
        </form>
    </div>
</div>

@endsection
    
