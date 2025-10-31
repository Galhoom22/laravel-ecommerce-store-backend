@extends('layouts.app')

@section('content')
    <!-- Header -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Manage Categories</h1>
            <p>Admin Dashboard - Category Management</p>
        </div>
    </div>

    <!-- Success Message -->
    <div class="container py-3">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Create Button -->
    <div class="container">
        <div class="row mb-3">
            <div class="col text-end">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                    + Add New Category
                </a>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="container pb-5">
        <div class="row">
            <div class="col">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-sm btn-primary">Edit</a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
