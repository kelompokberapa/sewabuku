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
    <title>Data Buku</title>
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
        <h2>Data Buku</h2>
        <form method="POST" action="buku.php">
            <input type="text" name="judul" placeholder="Judul Buku" required>
            <input type="text" name="pengarang" placeholder="Pengarang" required>
            <input type="text" name="rak" placeholder="Rak" required>
            <input type="number" name="jumlah" placeholder="Jumlah Buku" required> <!-- Input untuk jumlah buku -->
            <input type="submit" name="add" value="Tambah Buku">
        </form>
        <table>
    <tr>
        <th>Judul</th>
        <th>Pengarang</th>
        <th>Rak</th>
        <th>Jumlah Tersedia</th> <!-- Kolom baru untuk menampilkan jumlah buku yang tersedia -->
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php while($row = $buku->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['judul']; ?></td>
        <td><?php echo $row['pengarang']; ?></td>
        <td><?php echo $row['rak']; ?></td>
        <td><?php echo $row['jumlah']; ?></td> <!-- Menampilkan jumlah buku yang tersedia -->
        <td><?php echo $row['status_pinjam'] ? 'Dipinjam' : 'Tersedia'; ?></td>
        <td>
            <a href="buku.php?delete=<?php echo $row['id']; ?>">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

    </div>

    <footer>
        <p>Perpustakaan &copy; 2024</p>
    </footer>
</body>
</html>
