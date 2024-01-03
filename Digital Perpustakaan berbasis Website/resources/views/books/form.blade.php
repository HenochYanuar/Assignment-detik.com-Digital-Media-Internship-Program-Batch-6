@extends('layout.main')
@section('title', 'Tambah Buku')
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
    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
        <div class="row">
            <div class="card col-5 mr-2">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $id_user }}">
                    <div class="form-group">
                        <label for="">Kode</label>
                        <input class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}"
                            type="text" name="code" />
                        @error('code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Judul</label>
                        <input class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                            type="text" name="title" />
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="id_category" class="form-control @error('id_category') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Category</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('id_category')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Publisher</label>
                        <select name="id_publisher" class="form-control @error('id_publisher') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Publisher</option>
                            @foreach ($publishers as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('id_publisher')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Cover Buku</label>
                        <input class="form-control @error('cover_image') is-invalid @enderror" value="{{ old('cover_image') }}"
                            type="file" name="cover_image" accept="image/jpeg, image/png, image/jpg" />
                        @error('cover_image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">File Buku</label>
                        <input class="form-control @error('pdf_file') is-invalid @enderror" value="{{ old('pdf_file') }}"
                            type="file" name="pdf_file" accept="application/pdf" />
                        @error('pdf_file')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn btn-success mt-2" type="submit">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>

            <div class="card col-6">
                <div class="card-body">
                    <h2>Pilih Author</h2>
                    <table id="table-author" class="table">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>Nama Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($authors as $author)
                                <tr>
                                    <td><input name="author[]" value="{{ $author->id }}" type="checkbox"></td>
                                    <td>{{ $author->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('js')
    <script>
        $(function() {
            $('#table-author').DataTable();
        });
    </script>
@endpush
