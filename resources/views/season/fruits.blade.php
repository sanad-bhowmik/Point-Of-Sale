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
                        <a href="{{ route('seasonalfruit.create') }}" class="btn btn-primary btn-sm">
                            + Add Seasonal Fruit
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            <!-- ✅ Global Search Input -->
                            <div class="d-flex align-items-center" style="width: 230px; position: relative;">
                                <input
                                    type="text"
                                    id="globalSearch"
                                    class="form-control pe-5"
                                    placeholder="Search in table..."
                                    style="border-radius: 8px;">
                                <span
                                    id="clearSearch"
                                    class="text-muted"
                                    style="position: absolute;right: 12px;cursor: pointer;display: none;font-size: 18px;font-weight: bold;top: 50%;transform: translateY(-50%); "
                                    title="Clear">
                                    &times;
                                </span>
                            </div>


                        </div>
                    </div>

                    <table class="table table-bordered table-hover" id="fruitTable">
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
                                <td>{{ \Carbon\Carbon::parse($fruit->from_month)->format('M') }} – {{ \Carbon\Carbon::parse($fruit->to_month)->format('M') }}</td>
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
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
    // ✅ Toastr setup
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

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif

    // ✅ Global Search Logic
    const searchInput = document.getElementById("globalSearch");
    const clearSearch = document.getElementById("clearSearch");

    searchInput.addEventListener("keyup", function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll("#fruitTable tbody tr");
        let hasVisible = false;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const match = text.includes(searchValue);
            row.style.display = match ? "" : "none";
            if (match) hasVisible = true;
        });

        clearSearch.style.display = searchValue ? "inline" : "none";
    });

    clearSearch.addEventListener("click", function() {
        searchInput.value = "";
        const rows = document.querySelectorAll("#fruitTable tbody tr");
        rows.forEach(row => row.style.display = "");
        this.style.display = "none";
    });
</script>
@endsection
