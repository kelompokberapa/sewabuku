<?php
include 'koneksi.php';

// Handle Peminjaman
if (isset($_POST['pinjam'])) {
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tanggal_pinjam = date('Y-m-d');
    
    // Periksa apakah buku tersedia
    $check_buku_query = "SELECT jumlah FROM buku WHERE id = $id_buku";
    $check_buku_result = $conn->query($check_buku_query);
    $jumlah_buku = $check_buku_result->fetch_assoc()['jumlah'];
    
    if ($jumlah_buku > 0) {
        $sql = "INSERT INTO peminjaman (id_anggota, id_buku, tanggal_pinjam) VALUES ('$id_anggota', '$id_buku', '$tanggal_pinjam')";
        $conn->query($sql);
        $conn->query("UPDATE buku SET status_pinjam = 1 WHERE id = $id_buku");
        $conn->query("UPDATE buku SET jumlah = jumlah - 1 WHERE id = $id_buku"); // Kurangi jumlah buku yang tersedia
    } else {
        echo "Maaf, buku tidak tersedia untuk dipinjam.";
    }
}

// Handle Pengembalian
if (isset($_POST['kembali'])) {
    $id = $_POST['id'];
    $tanggal_kembali = date('Y-m-d');
    $sql = "UPDATE peminjaman SET tanggal_kembali = '$tanggal_kembali' WHERE id = $id";
    $conn->query($sql);
    $conn->query("UPDATE buku SET status_pinjam = 0 WHERE id = (SELECT id_buku FROM peminjaman WHERE id = $id)");
    $conn->query("UPDATE buku SET jumlah = jumlah + 1 WHERE id = (SELECT id_buku FROM peminjaman WHERE id = $id)"); // Tambahkan jumlah buku yang tersedia setelah pengembalian
}

$anggota = $conn->query("SELECT * FROM anggota");
$buku = $conn->query("SELECT * FROM buku WHERE status_pinjam = 0 AND jumlah > 0"); // Hanya tampilkan buku yang tersedia dan memiliki jumlah lebih dari 0

$peminjaman = $conn->query("SELECT peminjaman.id, anggota.nama, buku.judul, peminjaman.tanggal_pinjam FROM peminjaman JOIN anggota ON peminjaman.id_anggota = anggota.id JOIN buku ON peminjaman.id_buku = buku.id WHERE peminjaman.tanggal_kembali IS NULL");
?>

<!DOCTYPE html>
<html>
<head>
    <title>LibSmart - Transaksi Peminjamangu</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

<div class="container mt-4">
    <h3 class="text-center mb-4">Transaksi Peminjaman</h3>
    <div class="form-container mb-4">
        <form method="POST" action="peminjaman.php">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <select name="id_anggota" class="form-control" required>
                        <option value="">Pilih Anggota</option>
                        <?php while($row = $anggota->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <select name="id_buku" class="form-control" required>
                        <option value="">Pilih Buku</option>
                        <?php while($row = $buku->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['judul'] . ' (' . $row['jumlah'] . ' tersedia)'; ?></option> <!-- Menampilkan jumlah buku yang tersedia -->
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <input type="submit" name="pinjam" class="btn btn-primary btn-block" value="Pinjam Buku">
                </div>
            </div>
        </form>
    </div>
    <div class="table-container">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $peminjaman->fetch_assoc()): ?>
                <tr class="fade-in">
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo $row['tanggal_pinjam']; ?></td>
                    <td>
                        <form method="POST" action="peminjaman.php">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="kembali" class="btn btn-success btn-sm">Kembalikan</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="bg-info text-white text-center py-3 fixed-bottom">
    <p>LibSmart &copy; 2024</p>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
