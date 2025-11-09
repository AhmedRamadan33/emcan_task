@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Product Details</h4>
                    <div>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        @if($product->has_image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="img-fluid rounded shadow" style="max-height: 400px;">
                            <p class="text-muted mt-2">Product Image</p>
                        @else
                            <img src="{{ asset('images/default-product.jpg') }}" alt="Default Product Image" 
                                 class="img-fluid rounded shadow" style="max-height: 400px;">
                            <p class="text-muted mt-2">Default Product Image</p>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Basic Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">ID:</th>
                                <td>{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>SKU:</th>
                                <td>{{ $product->sku ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td>{{ $product->category->name ?? 'No Category' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Pricing & Inventory</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Price:</th>
                                <td>${{ number_format($product->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Quantity:</th>
                                <td>{{ $product->quantity }}</td>
                            </tr>
                            <tr>
                                <th>Image Status:</th>
                                <td>
                                    @if($product->has_image)
                                        <span class="badge bg-success">Image Available</span>
                                    @else
                                        <span class="badge bg-secondary">No Image</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Description</h5>
                        <div class="border p-3 rounded">
                            {{ $product->description ?? 'No description available.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection