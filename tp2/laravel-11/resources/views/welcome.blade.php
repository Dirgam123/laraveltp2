<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WELCOME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-4">WELCOME TO PROJECT MANAGEMENT</h3>
                    <h5 class="text-center">Aplikasi Manajemen Proyek</h5>
                    <hr>
                </div>
                
                <div class="text-center">
                    <p>Kelola proyek dan tugas Anda dengan mudah. Tambahkan proyek baru, perbarui tugas, dan pantau kemajuan Anda.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-md btn-primary mt-3">Mulai Kelola Proyek</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
