<?php
include 'koneksi.php';

// Handle Add Anggota
if (isset($_POST['add'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $sql = "INSERT INTO anggota (nim, nama, alamat) VALUES ('$nim', '$nama', '$alamat')";
    $conn->query($sql);
    header("Location: anggota.php"); // Redirect to refresh the page after adding a member
    exit();
}

// Handle Delete Anggota
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM anggota WHERE id = $id";
    $conn->query($sql);
    header("Location: anggota.php"); // Redirect to refresh the page after deleting a member
    exit();
}

$anggota = $conn->query("SELECT * FROM anggota");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibSmart - Data Anggota</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            flex: 1; /* Memastikan konten tabel memanjang jika terlalu banyak */
            overflow-y: auto; /* Mengaktifkan scroll jika tabel terlalu panjang */
            padding-bottom: 60px; /* Menambahkan ruang di bawah konten */
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn {
            border-radius: 0;
        }

        .btn-danger {
            background-color: #dc3545;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        footer {
            background: #50b3a2;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            flex-shrink: 0; /* Memastikan footer tetap di bawah */
            width: 100%;
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

<div class="container mt-5">
    <h3 class="text-center mb-4">Data Anggota</h3>
    <div class="form-container mb-4">
        <form method="POST" action="anggota.php">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <input type="number" name="nim" class="form-control" placeholder="NIM" required>
                </div>
                <div class="form-group col-md-4">
                    <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                </div>
                <div class="form-group col-md-5">
                    <input name="alamat" class="form-control" placeholder="Alamat" required></>
                </div>
                <div class="form-group col-md-12">
                    <input type="submit" name="add" class="btn btn-primary btn-block" value="Tambah Anggota">
                </div>
            </div>
        </form>
    </div>
    <div class="table-container">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $anggota->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['nim']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['alamat']; ?></td>
                    <td>
                        <a href="anggota.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm delete-btn">Hapus</a>
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
