<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body text-center">
                        <img src="{{ asset('/storage/products/' . $product->image) }}" alt="{{ $product->title }}" class="rounded" style="width: 100%; height: auto;">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <h3 class="card-title">{{ $product->title }}</h3>
                        <hr/>
                        <p><strong>Status:</strong> {{ $product->status }}</p>
                        <p><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($product->deadline)->format('F j, Y') }}</p>
                        <p><strong>Description:</strong></p>
                        <div>{!! nl2br(e($product->description)) !!}</div>
                        <hr/>
                        <p><strong>Task List:</strong></p>
                        <ul>
    @php
        // Convert task_list string to an array
        $taskList = is_string($product->task_list) ? explode(',', $product->task_list) : $product->task_list;
    @endphp

    @forelse ($taskList as $task)
        <li>{{ trim($task) }}</li> 
    @empty
        <li>No tasks available.</li>
    @endforelse
</ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('products.index') }}" class="btn btn-primary">Back to Projects</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
