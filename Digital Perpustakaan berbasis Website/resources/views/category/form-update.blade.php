@extends('layout.main')
@section('title', 'Update Data Kategori')
@section('username', $name)
@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{route('category.update')}}">
            @csrf
            <input type="hidden" name="id" value="{{ $category->id }}">
            <div class="form-group">
                <label for="">Nama Category</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" value="{{ $category->name }}" name="name"  />
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <button class="btn btn-warning" type="button" onclick="location.href='{{ route('category.index') }}'">
                <i class="fas fa-caret-left"></i> Kembali
            </button>
            <button class="btn btn-success" type="submit">
                <i class="fa fa-save"></i> Update
            </button>
        </form>
    </div>
</div>

@endsection
    
