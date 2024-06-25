<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perpustakaan</title>
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
        <h2>Dashboard</h2>
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
        <p>Total Buku: <?php echo $total_buku; ?></p>
        <p>Buku yang Dipinjam: <?php echo $buku_dipinjam; ?></p>
        <p>Sisa Buku: <?php echo $sisa_buku; ?></p>
    </div>

    <footer>
        <p>Perpustakaan &copy; 2024</p>
    </footer>
</body>
</html>
