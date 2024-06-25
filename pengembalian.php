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



$peminjaman = $conn->query("SELECT peminjaman.id, anggota.nama, buku.judul, peminjaman.tanggal_pinjam FROM peminjaman JOIN anggota ON peminjaman.id_anggota = anggota.id JOIN buku ON peminjaman.id_buku = buku.id WHERE peminjaman.tanggal_kembali IS NULL");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Pengembalian</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Perpustakaan</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="anggota.php">Anggota</a></li>
                    <li><a href="buku.php">Buku</a></li>
                    <li><a href="peminjaman.php">Peminjaman</a></li>
                    <li><a href="pengembalian.php">Pengembalian</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Transaksi Pengembalian</h2>
        <form method="POST" action="pengembalian.php">
            <select name="id" required>
                <option value="">Pilih Buku yang Dikembalikan</option>
                <?php while($row = $peminjaman->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['judul']; ?> oleh <?php echo $row['nama']; ?> (<?php echo $row['tanggal_pinjam']; ?>)</option>
                <?php endwhile; ?>
            </select>
            <input type="submit" name="kembali" value="Kembalikan Buku">
        </form>
    </div>

    <footer>
        <p>Perpustakaan &copy; 2024</p>
    </footer>
</body>
</html>
