@extends('layouts.app')

@section('title', 'Sizes')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Sizes</li>
</ol>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Sizes List</h5>
                        <a href="{{ route('product.size.create') }}" class="btn btn-primary btn-sm">
                            + Add Size
                        </a>
                    </div>

                    <table class="table table-bordered table-hover">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Sizes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>
                                    @foreach($product->sizes as $size)
                                    <span class="badge bg-secondary mb-1">{{ $size->size }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->sizes as $size)
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-info mb-1" data-bs-toggle="modal" data-bs-target="#editSizeModal{{ $size->id }}" style="font-size: 14px;" title="Click to Edit">
                                        Edit {{ $size->size }}
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('product.size.destroy', $size->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this size?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-1" style="font-size: 14px;" title="Click to Delete">
                                            Delete {{ $size->size }}
                                        </button>
                                    </form>

                                    <!-- Edit Size Modal -->
                                    <div class="modal fade" id="editSizeModal{{ $size->id }}" tabindex="-1" aria-labelledby="editSizeLabel{{ $size->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editSizeLabel{{ $size->id }}">Edit Size</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border: none;background-color: white;">✖</button>
                                                </div>
                                                <form action="{{ route('product.size.update', $size->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Product Name</label>
                                                            <input type="text" class="form-control" value="{{ $product->product_name }}" disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Size</label>
                                                            <input type="text" class="form-control" name="size" value="{{ $size->size }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No products found.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
@endsection
