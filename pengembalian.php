<?php
include 'koneksi.php';

// Handle Pengembalian
if (isset($_POST['kembali'])) {
    $id = $_POST['id'];
    $tanggal_kembali = date('Y-m-d');

    // Ambil id buku yang dipinjam
    $id_buku_query = "SELECT id_buku FROM peminjaman WHERE id = $id";
    $id_buku_result = $conn->query($id_buku_query);
    $id_buku_row = $id_buku_result->fetch_assoc();
    $id_buku = $id_buku_row['id_buku'];

    // Update tanggal kembali
    $sql = "UPDATE peminjaman SET tanggal_kembali = '$tanggal_kembali' WHERE id = $id";
    $conn->query($sql);

    // Update status buku menjadi Tersedia
    $conn->query("UPDATE buku SET status_pinjam = 0 WHERE id = $id_buku");

    // Ambil jumlah buku yang dipinjam sebelumnya
    $jumlah_buku_query = "SELECT jumlah FROM buku WHERE id = $id_buku";
    $jumlah_buku_result = $conn->query($jumlah_buku_query);
    $jumlah_buku_row = $jumlah_buku_result->fetch_assoc();
    $jumlah_buku_sebelumnya = $jumlah_buku_row['jumlah'];

    // Tambahkan jumlah buku yang dikembalikan ke jumlah buku yang tersedia
    $jumlah_buku_dikembalikan = 1;
    $jumlah_buku_setelah_dikembalikan = $jumlah_buku_sebelumnya + $jumlah_buku_dikembalikan;

    // Update jumlah buku yang tersedia di database
    $conn->query("UPDATE buku SET jumlah = $jumlah_buku_setelah_dikembalikan WHERE id = $id_buku");
}

// Handle Hapus Riwayat Pengembalian
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    // Hapus data pengembalian berdasarkan ID
    $conn->query("DELETE FROM peminjaman WHERE id = $id");
}

$peminjaman = $conn->query("SELECT peminjaman.id, anggota.nama, buku.judul, peminjaman.tanggal_pinjam FROM peminjaman JOIN anggota ON peminjaman.id_anggota = anggota.id JOIN buku ON peminjaman.id_buku = buku.id WHERE peminjaman.tanggal_kembali IS NULL");
$riwayat_pengembalian = $conn->query("SELECT peminjaman.id, anggota.nama, buku.judul, peminjaman.tanggal_pinjam, peminjaman.tanggal_kembali FROM peminjaman JOIN anggota ON peminjaman.id_anggota = anggota.id JOIN buku ON peminjaman.id_buku = buku.id WHERE peminjaman.tanggal_kembali IS NOT NULL ORDER BY peminjaman.tanggal_kembali DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>LibSmart - Transaksi Pengembalian</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            padding-bottom: 60px; /* untuk memberi ruang bagi footer */
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
            position: fixed;
            width: 100%;
            bottom: 0;
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
    <div class="form-container mb-4">
        <h3 class="text-center mb-4">Transaksi Pengembalian</h3>
        <form method="POST" action="pengembalian.php">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <select name="id" class="form-control" required>
                        <option value="">Pilih Buku yang Dikembalikan</option>
                        <?php while($row = $peminjaman->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['judul']; ?> oleh <?php echo $row['nama']; ?> (<?php echo $row['tanggal_pinjam']; ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <input type="submit" name="kembali" class="btn btn-danger btn-block" value="Kembalikan Buku">
                </div>
            </div>
        </form>
    </div>

    <div class="table-container">
        <h3 class="mb-4">Riwayat Pengembalian</h3>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Aksi</th> <!-- Tambah kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                <?php while($row = $riwayat_pengembalian->fetch_assoc()): ?>
                <tr class="fade-in">
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo $row['tanggal_pinjam']; ?></td>
                    <td><?php echo $row['tanggal_kembali']; ?></td>
                    <td>
                        <form method="POST" action="pengembalian.php" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="hapus" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
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
