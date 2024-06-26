<?php
include 'koneksi.php';

// Handle Add Buku
if (isset($_POST['add'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $rak = $_POST['rak'];
    $jumlah = $_POST['jumlah']; // Tambahkan input untuk jumlah buku
    $sql = "INSERT INTO buku (judul, pengarang, rak, jumlah) VALUES ('$judul', '$pengarang', '$rak', '$jumlah')";
    $conn->query($sql);
}

// Handle Delete Buku
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Hapus peminjaman terkait terlebih dahulu
    $conn->query("DELETE FROM peminjaman WHERE id_buku = $id");

    // Hapus buku
    $sql = "DELETE FROM buku WHERE id = $id";
    $conn->query($sql);
}

$buku = $conn->query("SELECT * FROM buku");
?>

<!DOCTYPE html>
<html>
<head>
    <title>LibSmart - Data Buku</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            min-height: 100vh; /* Pastikan body memiliki tinggi minimal 100vh */
            display: flex;
            flex-direction: column;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 0;
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn {
            border-radius: 0;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        footer {
            background: #50b3a2;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: relative;
            width: 100%;
            margin-top: auto; /* Posisikan footer di bawah konten */
        }

        .table thead th {
            vertical-align: middle;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        .fade-in-row {
            animation: fadeIn 1s ease-in-out;
        }

        .container-main {
            flex: 1; /* Biarkan kontainer utama mengambil ruang yang tersedia */
            padding-bottom: 60px; /* Berikan padding-bottom agar footer tidak menutupi konten */
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

<div class="container container-main mt-5">
    <h3 class="text-center mb-4">Data Buku</h3>
    <div class="form-container mb-4">
        <form method="POST" action="buku.php">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <input type="text" name="judul" class="form-control" placeholder="Judul Buku" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="pengarang" class="form-control" placeholder="Pengarang" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="text" name="rak" class="form-control" placeholder="Rak" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Buku" required> <!-- Input untuk jumlah buku -->
                </div>
                <div class="form-group col-md-2">
                    <input type="submit" name="add" class="btn btn-primary btn-block" value="Tambah Buku">
                </div>
            </div>
        </form>
    </div>
    <div class="table-container fade-in">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Rak</th>
                    <th>Jumlah Tersedia</th> <!-- Kolom baru untuk menampilkan jumlah buku yang tersedia -->
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $buku->fetch_assoc()): ?>
                <tr class="fade-in-row">
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo $row['pengarang']; ?></td>
                    <td><?php echo $row['rak']; ?></td>
                    <td><?php echo $row['jumlah']; ?></td> <!-- Menampilkan jumlah buku yang tersedia -->
                    <td><?php echo $row['status_pinjam'] ? 'Dipinjam' : 'Tersedia'; ?></td>
                    <td>
                        <a href="buku.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="bg-info text-white text-center py-3">
    <p>LibSmart &copy; 2024</p>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
