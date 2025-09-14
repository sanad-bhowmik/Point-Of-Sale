@extends('layouts.app')

@section('title', 'Expense Names')

@php
    $categories = \Modules\Expense\Entities\ExpenseCategory::all();
@endphp

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item active">Expense Name</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('utils.alerts')
                <div class="card">
                    <div class="card-body">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#expenseNameCreateModal">
                            Add Expense Name <i class="bi bi-plus"></i>
                        </button>

                        <hr>

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="expenseNameCreateModal" tabindex="-1" role="dialog"
        aria-labelledby="expenseNameCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseNameCreateModalLabel">Create Expense Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Expense Name Create Form --}}
                <form action="{{ route('expense-names.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        {{-- Expense Name --}}
                        <div class="form-group">
                            <label for="expense_name">Expense Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="expense_name" required>
                        </div>

                        {{-- Expense Category --}}
                        <div class="form-group">
                            <label for="expense_category_id">Select Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="expense_category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Create <i class="bi bi-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('page_scripts')
    {!! $dataTable->scripts() !!}
@endpush
