<?php
include 'koneksi.php';

// Handle Add Anggota
if (isset($_POST['add'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $sql = "INSERT INTO anggota (nim, nama, alamat) VALUES ('$nim', '$nama', '$alamat')";
    $conn->query($sql);
}

// Handle Delete Anggota
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM anggota WHERE id = $id";
    $conn->query($sql);
}

$anggota = $conn->query("SELECT * FROM anggota");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Anggota</title>
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
        <h2>Data Anggota</h2>
        <form method="POST" action="anggota.php">
            <input type="text" name="nim" placeholder="NIM" required>
            <input type="text" name="nama" placeholder="Nama" required>
            <textarea name="alamat" placeholder="Alamat" required></textarea>
            <input type="submit" name="add" value="Tambah Anggota">
        </form>
        <table>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = $anggota->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['nim']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td>
                    <a href="anggota.php?delete=<?php echo $row['id']; ?>">Hapus</a>
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
