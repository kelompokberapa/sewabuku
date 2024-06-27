<?php
include 'koneksi.php';

// Handle Add Buku
if (isset($_POST['add'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $rak = $_POST['rak'];
    $jumlah = $_POST['jumlah'];
    $sql = "INSERT INTO buku (judul, pengarang, rak, jumlah) VALUES ('$judul', '$pengarang', '$rak', '$jumlah')";
    $conn->query($sql);
}

// Handle Edit Buku
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $rak = $_POST['rak'];
    $jumlah = $_POST['jumlah'];
    $sql = "UPDATE buku SET judul='$judul', pengarang='$pengarang', rak='$rak', jumlah='$jumlah' WHERE id=$id";
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

// Get Data Buku for Edit
$buku_to_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM buku WHERE id=$id");
    $buku_to_edit = $result->fetch_assoc();
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
            min-height: 100vh;
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
            margin-top: auto;
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
            flex: 1;
            padding-bottom: 60px;
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
            <input type="hidden" name="id" value="<?php echo $buku_to_edit ? $buku_to_edit['id'] : ''; ?>">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <input type="text" name="judul" class="form-control" placeholder="Judul Buku" value="<?php echo $buku_to_edit ? $buku_to_edit['judul'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="pengarang" class="form-control" placeholder="Pengarang" value="<?php echo $buku_to_edit ? $buku_to_edit['pengarang'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="number" name="rak" class="form-control" placeholder="Rak" value="<?php echo $buku_to_edit ? $buku_to_edit['rak'] : ''; ?>" required min="1" max="10">
                </div>
                <div class="form-group col-md-2">
                    <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Buku" value="<?php echo $buku_to_edit ? $buku_to_edit['jumlah'] : ''; ?>" required min="1" max="20">
                </div>
                <div class="form-group col-md-2">
                    <input type="submit" name="<?php echo $buku_to_edit ? 'edit' : 'add'; ?>" class="btn btn-primary btn-block" value="<?php echo $buku_to_edit ? 'Edit Buku' : 'Tambah Buku'; ?>">
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
                    <th>Jumlah Tersedia</th>
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
                    <td><?php echo $row['jumlah']; ?></td>
                    <td><?php echo $row['jumlah'] > 0 ? 'Tersedia' : 'Dipinjam'; ?></td>
                    <td>
                        <a href="buku.php?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
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
