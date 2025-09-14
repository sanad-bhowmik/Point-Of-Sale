@extends('layouts.app')

@section('title', 'Edit Expense Name')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expense-names.index') }}">Expense name</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @include('utils.alerts')
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('expense-names.update', $expenseName->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            {{-- Expense Name --}}
                            <div class="form-group mb-3">
                                <label for="expense_name">Expense Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('expense_name') is-invalid @enderror"
                                    id="expense_name" name="expense_name"
                                    value="{{ old('expense_name', $expenseName->expense_name) }}" required>
                                @error('expense_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Expense Category --}}
                            <div class="form-group mb-3">
                                <label for="expense_category_id">Select Category <span class="text-danger">*</span></label>
                                <select class="form-control @error('expense_category_id') is-invalid @enderror"
                                    id="expense_category_id" name="expense_category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('expense_category_id', $expenseName->expense_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('expense_category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Update <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
