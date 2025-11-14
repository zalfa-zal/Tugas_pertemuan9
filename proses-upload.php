<?php
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Mengecek apakah file ada
    if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {

        $file_tmp_path = $_FILES['file_pdf']['tmp_name'];
        $file_name = $_FILES['file_pdf']['name'];
        $file_size = $_FILES['file_pdf']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi ukuran
        if ($file_size > 10485760) { // 10 MB
            echo "<h2>Upload gagal! Ukuran file maksimal 10MB.</h2>";
            exit();
        }

        $new_file_name = 'zalfa' . '-' . time() . '_' . $file_name;
        $upload_dir = 'zalfa/'; 
        $dest_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_path, $dest_path)) {

            // Simpan ke database
            $sql = "INSERT INTO files (path, name) VALUES (?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $dest_path, $file_name);

            if (mysqli_stmt_execute($stmt)) {
                echo "<h2>File berhasil diunggah!</h2>";
            } else {
                echo "<h2>File berhasil diunggah, tapi gagal menyimpan ke database.</h2>";
            }

            mysqli_stmt_close($stmt);

        } else {
            echo "<h2>Gagal memindahkan file ke folder uploads.</h2>";
        }

    } else {
        echo "<h2>Tidak ada file yang dipilih atau terjadi error saat upload.</h2>";
    }

}
?>
