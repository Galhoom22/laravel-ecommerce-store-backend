@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Edit Category</h1>
        </div>
    </div>

    <div class="container py-5">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="col-md-8 m-auto">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control"
                    value="{{ old('slug', $category->slug) }}" required>
                @error('slug')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Parent Category -->
            <div class="mb-3">
                <label for="parent_id" class="form-label">Parent Category</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">None (Top Level)</option>
                    @foreach ($categories as $cat)
                        @if ($cat->id !== $category->id)
                            <option value="{{ $cat->id }}"
                                {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Active</label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="text-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
@endsection
