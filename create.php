<?php
// Mengecek apakah form telah dikirim dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = substr(preg_replace('/[^0-9]/', '', $_POST["phone"]), 0, 13);

    // Membuat koneksi ke database
    $conn = new mysqli("localhost", "root", "", "crud_db");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Menyusun query untuk memasukkan data ke tabel 
    $sql = "INSERT INTO pendaftar (name, email, phone) VALUES ('$name', '$email', '$phone')";

    // Mengeksekusi query dan mengecek apakah berhasil
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Redirect ke halaman utama jika berhasil
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Menampilkan pesan kesalahan jika gagal
    }

    // Menutup koneksi
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengguna</title>
    <style>
        /* Mengatur gaya umum untuk body */
        body {
            font-family: Arial, sans-serif;
            background-color: #fbe9e7; /* Ubah background menjadi warna peach */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Mengatur gaya container form */
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        /* Mengatur label input dan spasi antar elemen */
        form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ff7043; /* Ganti border menjadi oranye */
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Mengatur gaya tombol submit */
        form button {
            width: 100%;
            padding: 10px;
            background-color: #ff5722; /* Ganti warna tombol menjadi oranye */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Mengatur gaya tombol submit saat di-hover */
        form button:hover {
            background-color: #e64a19; /* Ganti warna hover menjadi oranye lebih gelap */
        }

        /* Mengatur gaya teks label */
        form label {
            font-weight: bold;
            color: #bf360c; /* Ganti warna label menjadi oranye gelap */
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        Nama: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Telepon: <input type="text" name="phone" required><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
