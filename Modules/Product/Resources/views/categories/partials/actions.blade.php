<a href="{{ route('product-categories.edit', $data->id) }}"
   class="btn btn-info btn-sm"
   data-bs-toggle="tooltip"
   data-bs-placement="top"
   title="Edit Category">
    <i class="bi bi-pencil"></i>
</a>

<button id="delete"
    class="btn btn-danger btn-sm"
    data-bs-toggle="tooltip"
    data-bs-placement="top"
    title="Delete Category"
    onclick="
        event.preventDefault();
        if (confirm('Are you sure? It will delete the data permanently!')) {
            document.getElementById('destroy{{ $data->id }}').submit();
        }
    ">
    <i class="bi bi-trash"></i>
    <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('product-categories.destroy', $data->id) }}" method="POST">
        @csrf
        @method('delete')
    </form>
</button>

<script>
    // Initialize all tooltips after the page loads
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
