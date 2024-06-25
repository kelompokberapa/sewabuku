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
    <title>Transaksi Peminjaman</title>
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
        <h2>Transaksi Peminjaman</h2>
        <form method="POST" action="peminjaman.php">
            <select name="id_anggota" required>
                <option value="">Pilih Anggota</option>
                <?php while($row = $anggota->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="id_buku" required>
                <option value="">Pilih Buku</option>
                <?php while($row = $buku->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['judul'] . ' (' . $row['jumlah'] . ' tersedia)'; ?></option> <!-- Menampilkan jumlah buku yang tersedia -->
                <?php endwhile; ?>
            </select>
            <input type="submit" name="pinjam" value="Pinjam Buku">
        </form>
        <table>
            <tr>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
            </tr>
            <?php while($row = $peminjaman->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['judul']; ?></td>
                <td><?php echo $row['tanggal_pinjam']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <footer>
        <p>Perpustakaan &copy; 2024</p>
    </footer>
</body>
</html>
