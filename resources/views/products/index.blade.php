<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .card {
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .btn-custom {
            border-radius: 5px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        
        .product-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .pagination {
            margin-top: 2rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-box-open me-2"></i>
                Product Management
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Product List</h4>
                            <a href="{{ route('products.create') }}" class="btn btn-primary btn-custom">
                                <i class="fas fa-plus me-2"></i>Add New Product
                            </a>
                        </div>

                        <!-- Search and Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search products...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                    <img src="{{ Storage::disk('public')->url('storage/products/'.$product->image) }}" 
                                                        class="product-img" 
                                                        alt="{{ $product->title }}">
                                            </td>
                                            <td class="align-middle">{{ $product->title }}</td>
                                            <td class="align-middle">{{ "Rp " . number_format($product->price,2,',','.') }}</td>
                                            <td class="align-middle">
                                                <span class="badge bg-{{ $product->stock > 10 ? 'success' : 'warning' }}">
                                                    {{ $product->stock }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="action-buttons">
                                                    <a href="{{ route('products.show', $product->id) }}" 
                                                       class="btn btn-info btn-sm" 
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('products.edit', $product->id) }}" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form onsubmit="return confirm('Are you sure you want to delete this product?');" 
                                                          action="{{ route('products.destroy', $product->id) }}" 
                                                          method="POST" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-danger btn-sm" 
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No products available</h5>
                                                    <p class="text-muted">Start by adding your first product</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end'
            });
        @elseif(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end'
            });
        @endif
    </script>
</body>
</html>