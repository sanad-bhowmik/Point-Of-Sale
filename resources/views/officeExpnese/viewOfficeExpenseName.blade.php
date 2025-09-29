@extends('layouts.app')

@section('title', 'Office Expense Categories')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Office Expense Categories</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Office Expense Categories</h5>
                <div>
                    @include('utils.alerts')
                    <a href="/office-expense/name">
                        <button type="submit" class="btn btn-primary">
                            Create Category <i class="bi bi-check"></i>
                        </button>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->category_name }}</td>
                            <td>{{ $category->category_description ?? '-' }}</td>
                            <td>{{ $category->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
