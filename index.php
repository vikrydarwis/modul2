<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "crud_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel untuk pencarian
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Pagination setup
$limit = 5;  // Jumlah data per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total halaman
$sqlCount = "SELECT COUNT(*) as total FROM pendaftar WHERE name LIKE '%$search%'";
$resultCount = $conn->query($sqlCount);
$totalData = $resultCount->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

// Query untuk menampilkan data dengan filter pencarian dan batas pagination
$sql = "SELECT * FROM pendaftar WHERE name LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Tambah data pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sqlInsert = "INSERT INTO  (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if ($conn->query($sqlInsert) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sqlInsert . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD dengan Modal Tambah Pengguna</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        /* Gaya untuk seluruh halaman */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2; /* Latar belakang abu-abu muda */
        }

        h2 {
            color: #d84315; /* Warna judul menggunakan oranye gelap */
        }

        /* Gaya untuk form pencarian */
        .form-inline input, .form-inline button {
            border-radius: 4px;
        }

        .form-inline input {
            background-color: #fff3e0; /* Latar belakang input pencarian */
            border: 1px solid #ff5722; /* Border input berwarna oranye */
        }

        .form-inline button {
            background-color: #ff5722; /* Tombol pencarian berwarna oranye */
            color: white;
        }

        /* Gaya untuk tabel */
        table {
            background-color: #ffffff; /* Latar belakang tabel putih */
            margin-top: 20px;
        }

        .thead-dark {
            background-color: #ff7043; /* Warna header tabel oranye gelap */
            color: white;
        }

        table th, table td {
            text-align: center;
            vertical-align: middle;
        }

        table th {
            padding: 12px 15px;
        }

        table td {
            padding: 10px;
        }

        /* Gaya untuk tombol-tombol di dalam tabel */
        .btn-warning {
            background-color: #ff9800; /* Tombol Edit menggunakan oranye */
            border-color: #ff9800;
        }

        .btn-danger {
            background-color: #e53935; /* Tombol Hapus menggunakan merah */
            border-color: #e53935;
        }

        .btn-warning:hover, .btn-danger:hover {
            opacity: 0.8;
        }

        /* Gaya untuk modal */
        .modal-content {
            background-color: #fff3e0; /* Latar belakang modal */
        }

        .modal-header {
            background-color: #ff7043; /* Warna header modal */
            color: white;
        }

        .modal-footer {
            background-color: #ffccbc; /* Latar belakang footer modal */
        }

        .form-control {
            border-radius: 4px;
            background-color: #fff3e0; /* Latar belakang input dalam modal */
            border: 1px solid #ff5722; /* Border input berwarna oranye */
        }

        .btn-primary {
            background-color: #ff5722; /* Tombol simpan menggunakan oranye */
            border-color: #ff5722;
        }

        .btn-secondary {
            background-color: #bdbdbd; /* Tombol batal menggunakan abu-abu */
        }
    </style>
</head>
<body class="container mt-5">

<h2 class="mb-4">Daftar Pengguna</h2>

<!-- Tombol Tambah Pengguna -->
<button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addUserModal">
    Tambah Pengguna
</button>

<!-- Form Pencarian -->
<form method="GET" action="" class="form-inline mb-3">
    <input type="text" name="search" value="<?php echo $search; ?>" class="form-control mr-2" placeholder="Cari nama...">
    <button type="submit" class="btn btn-primary">Cari</button>
</form>

<!-- Tabel Data Pengguna -->
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td>
                <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Pagination -->
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php } ?>
    </ul>
</nav>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="name">Nama:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telepon:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
