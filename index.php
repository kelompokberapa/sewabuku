<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>LibSmart</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
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
        <a class="navbar-brand" id="branding" href="index.php">LibSmart</a>
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
    $total_buku_query = "SELECT COUNT(*) as total FROM buku";
    $total_buku_result = $conn->query($total_buku_query);
    $total_buku = $total_buku_result->fetch_assoc()['total'];

    // Buku yang Dipinjam
    $buku_dipinjam_query = "SELECT COUNT(*) as total FROM buku WHERE status_pinjam = 1";
    $buku_dipinjam_result = $conn->query($buku_dipinjam_query);
    $buku_dipinjam = $buku_dipinjam_result->fetch_assoc()['total'];

    // Sisa Buku
    $sisa_buku = $total_buku - $buku_dipinjam;
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
