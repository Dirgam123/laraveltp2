<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body">
                <h3 class="text-center mb-4">Update Task List</h3> <!-- Title for clarity -->
                <form action="{{ route('products.updateTaskList', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Task List Field -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">TASK LIST</label>
<textarea class="form-control @error('task_list') is-invalid @enderror" name="task_list" rows="5" placeholder="Enter tasks separated by commas">
    {{ old('task_list', is_array($product->task_list) ? implode(', ', $product->task_list) : $product->task_list) }}
</textarea>
                    
                        @error('task_list')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Buttons for Submit and Back -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">BACK</a>
                        <button type="submit" class="btn btn-primary">UPDATE TASK LIST</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
