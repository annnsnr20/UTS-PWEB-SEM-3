<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<h3 style='text-align:center;color:red;'>Access Denied!</h3>";
    exit;
}

include "conn.php";

// Hapus supplier
if (isset($_GET['hapus_supplier'])) {
    $id = $_GET['hapus_supplier'];
    $stmt = $conn->prepare("DELETE FROM suppliers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Supplier berhasil dihapus!'); window.location='index.php?page=product';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Master Product - Supplier</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f4f6f9; }
.card { border-radius: 12px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); border: none; }
.table thead { background-color: #28a745; color: white; }
h2 { color: #28a745; font-weight: bold; }
.btn { border-radius: 8px; }
</style>
</head>
<body>
<div class="container py-4">
    <h2 class="text-center mb-4">🛒 Master Product</h2>

    <div class="card">
        <div class="card-header bg-success text-white">Daftar Supplier</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama PT</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Provinsi</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = $conn->query("SELECT * FROM suppliers ORDER BY id DESC");
                        if ($data->num_rows > 0) {
                            while ($d = $data->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$d['id']}</td>
                                    <td><a href='index.php?page=product_list&supplier_id={$d['id']}'>".htmlspecialchars($d['nama_pt'])."</a></td>
                                    <td>".htmlspecialchars($d['alamat'])."</td>
                                    <td>".htmlspecialchars($d['kota'])."</td>
                                    <td>".htmlspecialchars($d['provinsi'])."</td>
                                    <td>".htmlspecialchars($d['telepon'])."</td>
                                    <td>
                                        <a href='index.php?page=product&hapus_supplier={$d['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus supplier ini?')\">🗑️</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Belum ada supplier</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
