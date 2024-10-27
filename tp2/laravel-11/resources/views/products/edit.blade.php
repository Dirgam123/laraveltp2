<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Product - SantriKoding.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <h3 class="mb-4">Edit Product</h3>
                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- IMAGE -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">IMAGE</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                @if ($product->image)
                                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->title }}" class="img-thumbnail mt-2" width="150">
                                @endif
                                @error('image')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- DEADLINE -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">DEADLINE</label>
                                <input type="date" class="form-control @error('deadline') is-invalid @enderror" name="deadline" value="{{ old('deadline', $product->deadline) }}">
                                @error('deadline')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- TITLE -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">TITLE</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $product->title) }}" placeholder="Enter Product Title">
                                @error('title')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">DESCRIPTION</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="5" placeholder="Enter Product Description">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- STATUS -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">STATUS</label>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status">
                                            <option value="" disabled>Select Status</option>
                                            <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="Progress" {{ old('status', $product->status) == 'Progress' ? 'selected' : '' }}>Progress</option>
                                            <option value="Delayed" {{ old('status', $product->status) == 'Delayed' ? 'selected' : '' }}>Delayed</option>
                                            <option value="Done" {{ old('status', $product->status) == 'Done' ? 'selected' : '' }}>Done</option>
                                        </select>
                                        @error('status')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- TASK LIST -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">TASK LIST</label>
                                        <textarea class="form-control @error('task_list') is-invalid @enderror" name="task_list" rows="3" placeholder="Enter Task List, separated by commas">{{ old('task_list', is_array($product->task_list) ? implode(', ', $product->task_list) : $product->task_list) }}</textarea>
                                        @error('task_list')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-md btn-primary me-3">UPDATE</button>
                            <a href="{{ route('products.index') }}" class="btn btn-md btn-secondary">BACK</a>

                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
</body>
</html>
