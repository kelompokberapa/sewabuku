<?php
include 'koneksi.php';
?>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibSmart | Perpustakaan Online</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .navbar-brand img {
        max-height: 250px; /* Ubah ukuran sesuai kebutuhan */
        width: auto; /* Pertahankan rasio aspek gambar */
    }
    .navbar-nav .nav-link {
        padding: 10px 15px; /* Ubah padding sesuai kebutuhan */
    }

        footer {
            background: #50b3a2;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: 20px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-title {
            font-size: 1.2rem;
            color: #50b3a2;
        }

        .card-text {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        th {
            background-color: #50b3a2;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <div class="container">
        <img src="img/perpus.png" width="80" height="100" class="d-inline-block align-top" alt="LibSmart Logo">
            Perpustakaan Online
        </img>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="anggota.php">Anggota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="buku.php">Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pengembalian.php">Pengembalian</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Dashboard</h2>
    <?php
    // Total Buku
    $total_buku_query = "SELECT SUM(jumlah) as total FROM buku";
    $total_buku_result = $conn->query($total_buku_query);
    $total_buku = $total_buku_result->fetch_assoc()['total'];

    // Buku yang Dipinjam
    $buku_dipinjam_query = "SELECT COUNT(*) as total FROM peminjaman WHERE tanggal_kembali IS NULL";
    $buku_dipinjam_result = $conn->query($buku_dipinjam_query);
    $buku_dipinjam = $buku_dipinjam_result->fetch_assoc()['total'];

    // Sisa Buku
    $sisa_buku = $total_buku ;
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Buku</h5>
                    <p class="card-text"><?php echo $total_buku; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Buku yang Dipinjam</h5>
                    <p class="card-text"><?php echo $buku_dipinjam; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Sisa Buku</h5>
                    <p class="card-text"><?php echo $sisa_buku; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-info text-white text-center py-3 fixed-bottom">
        <p>LibSmart &copy; 2024</p>
    </footer>

    <!-- Boostrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
