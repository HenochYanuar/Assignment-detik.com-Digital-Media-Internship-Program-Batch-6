@extends('layout.main')
@section('title', 'Tambah Kategori')
@section('username', $name)
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('category.posts') }}">
                @csrf
                <div class="form-group">
                    <label for="">Nama Kategori</label>
                    <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                     type="text" name="name" />
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button class="btn btn-success mt-2" type="submit">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
@endsection
