@extends('layouts.app')

@section('title', 'Seasonal Fruits')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Seasonal Fruits</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Seasonal Fruits List</h5>
                        <a href="{{ route('seasonalfruit.create') }}" class="btn btn-primary btn-sm">
                            + Add Seasonal Fruit
                        </a>
                    </div>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fruit Name</th>
                                <th>Season (From – To)</th>
                                <th>Image</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fruits as $index => $fruit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fruit->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($fruit->from_month)->format('M') }}
                                    – {{ \Carbon\Carbon::parse($fruit->to_month)->format('M') }}
                                </td>
                                <td>
                                    @if($fruit->img)
                                    <img src="{{ asset($fruit->img) }}" alt="{{ $fruit->name }}" width="60" height="60" class="rounded">
                                    @else
                                    <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $fruit->remarks ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('seasonalfruit.destroy', $fruit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this fruit?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No seasonal fruits found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toastr CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    @if(session('success'))
    toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
    toastr.error("{{ session('error') }}");
    @endif

    @if($errors - > any())
    @foreach($errors - > all() as $error)
    toastr.error("{{ $error }}");
    @endforeach
    @endif
</script>
@endsection
