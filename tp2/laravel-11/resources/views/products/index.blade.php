<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PROJECT MANAGEMENT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-4">DIRGAM FAHLEVI</h3>
                    <h5 class="text-center"><a href="https://github.com/Dirgam123">github</a></h5>
                    <hr>
                </div>
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" name="search" class="form-control" placeholder="Search Products" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                        <a href="{{ route('products.create') }}" class="btn btn-md btn-success mb-3">ADD PRODUCT</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">IMAGE</th>
                                    <th scope="col">TITLE</th>
                                    <th scope="col">Detail Pekerjaan</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">PROGRESS</th>
                                    <th scope="col" style="width: 20%">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ asset('/storage/products/'.$product->image) }}" class="rounded" style="width: 150px">
                                        </td>
                                        <td>{{ $product->title }}</td>
                                        <td>
                                            <ul>
@php
    // Check if task_list is a valid JSON string
    $taskList = is_string($product->task_list) ? json_decode($product->task_list, true) : $product->task_list;

    // Ensure $taskList is an array
    if (!is_array($taskList)) {
        $taskList = [];
    }
@endphp
                                                @if (empty($taskList) || (is_array($taskList) && count($taskList) === 0))
                                                    <li>No tasks available</li>
                                                @else
                                                    @foreach ($taskList as $task)
                                                        <li>{{ trim($task) }}</li> <!-- Use trim to remove any extra spaces -->
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </td>
                                        <td>{{ $product->status }}</td>
                                        <td>
                                            <!-- Bar Chart for Progress -->
                                            <canvas id="progressChart{{ $product->id }}" width="100" height="30"></canvas>
                                        </td>
                                        <td class="text-center">
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                                <a href="{{ route('products.newtask', $product->id) }}" class="btn btn-sm btn-warning">+TASK</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Data Products belum Tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @foreach($products as $product)
            var ctx = document.getElementById('progressChart{{ $product->id }}').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Progress'],
                    datasets: [{
                        label: 'Completion',
                        data: [{{ $product->progress }}],
                        backgroundColor: '{{ $product->progress < 50 ? 'rgba(255, 99, 132, 0.5)' : 'rgba(75, 192, 192, 0.5)' }}',
                        borderColor: '{{ $product->progress < 50 ? 'rgba(255, 99, 132, 1)' : 'rgba(75, 192, 192, 1)' }}',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        @endforeach
    </script>
</body>
</html>
