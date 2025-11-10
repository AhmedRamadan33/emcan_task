@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Products</h1>
        <div>
            <a href="{{ route('products.export') }}" class="btn btn-success me-2">
                <i class="fas fa-download"></i> Export Excel
            </a> <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
        </div>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search products..."
                value="{{ $search }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
            @if ($search)
                <a href="{{ route('products.index') }}" class="btn btn-outline-danger">Clear</a>
            @endif
        </div>
    </form>

    <!-- Products Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr id="product-{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td>{{ $product->category->name ?? 'No Category' }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-sm btn-danger delete-product" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // SweetAlert2 for deleting products
            $('.delete-product').click(function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${productName}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading indicator
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the product.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Perform AJAX delete
                        $.ajax({
                            url: '/products/' + productId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.close();

                                if (response.success) {
                                    // Remove the row from table
                                    $('#product-' + productId).fadeOut(300, function() {
                                        $(this).remove();

                                        // Check if table is empty
                                        if ($('tbody tr').length ===
                                            1) { // Only the empty message row
                                            location.reload();
                                        }
                                    });

                                    // Show success message
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: `Product "${productName}" has been deleted successfully.`,
                                        icon: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK',
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Failed to delete the product. Please try again.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.close();

                                let errorMessage =
                                    'An error occurred while deleting the product.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
