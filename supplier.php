<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<h3>Access Denied!</h3>";
    exit;
}

include "conn.php";

// === TAMBAH SUPPLIER ===
if (isset($_POST['tambah'])) {
    $nama_pt = $_POST['nama_pt'];
    $alamat = $_POST['alamat'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $telepon = $_POST['telepon'];

    mysqli_query($conn, "INSERT INTO suppliers (nama_pt, alamat, kota, provinsi, telepon) 
                         VALUES ('$nama_pt', '$alamat', '$kota', '$provinsi', '$telepon')");
    echo "<script>alert('Supplier berhasil ditambahkan!'); window.location='index.php?page=supplier';</script>";
}

// === UPDATE SUPPLIER ===
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_pt = $_POST['nama_pt'];
    $alamat = $_POST['alamat'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $telepon = $_POST['telepon'];

    mysqli_query($conn, "UPDATE suppliers 
                         SET nama_pt='$nama_pt', alamat='$alamat', kota='$kota', provinsi='$provinsi', telepon='$telepon' 
                         WHERE id='$id'");
    echo "<script>alert('Supplier berhasil diupdate!'); window.location='index.php?page=supplier';</script>";
}

// === HAPUS SUPPLIER ===
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM suppliers WHERE id='$id'");
    echo "<script>alert('Supplier berhasil dihapus!'); window.location='index.php?page=supplier';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Master Supplier</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f4f6f9; }
.card { border-radius: 12px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); border: none; }
.table thead { background-color: #0d6efd; color: white; }
.btn { border-radius: 8px; }
h2 { font-weight: bold; color: #0d6efd; }
</style>
</head>
<body>

<div class="container-fluid py-4">
    <h2 class="mb-4">📦 Master Supplier</h2>

    <div class="row">
        <!-- Kolom kiri: Form -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Tambah Supplier</div>
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama PT</label>
                            <input type="text" name="nama_pt" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" name="tambah" class="btn btn-success px-4">+ Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM EDIT SUPPLIER -->
            <?php
            if (isset($_GET['edit'])) {
                $id = $_GET['edit'];
                $edit = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM suppliers WHERE id='$id'"));
            ?>
            <div class="card">
                <div class="card-header bg-warning text-dark">Edit Supplier</div>
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <input type="hidden" name="id" value="<?= $edit['id']; ?>">
                        <div class="col-12">
                            <label class="form-label">Nama PT</label>
                            <input type="text" name="nama_pt" class="form-control" value="<?= $edit['nama_pt']; ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="<?= $edit['alamat']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota" class="form-control" value="<?= $edit['kota']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" value="<?= $edit['provinsi']; ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?= $edit['telepon']; ?>" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" name="update" class="btn btn-warning">💾 Update</button>
                            <a href="index.php?page=supplier" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Kolom kanan: Daftar Supplier -->
        <div class="col-md-8">
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
                                $data = mysqli_query($conn, "SELECT * FROM suppliers ORDER BY id DESC");
                                if (mysqli_num_rows($data) > 0) {
                                    while ($d = mysqli_fetch_array($data)) {
                                        echo "
                                        <tr>
                                            <td>{$d['id']}</td>
                                            <td>{$d['nama_pt']}</td>
                                            <td>{$d['alamat']}</td>
                                            <td>{$d['kota']}</td>
                                            <td>{$d['provinsi']}</td>
                                            <td>{$d['telepon']}</td>
                                            <td>
                                                <a href='index.php?page=supplier&edit={$d['id']}' class='btn btn-warning btn-sm'>✏️</a>
                                                <a href='index.php?page=supplier&hapus={$d['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus data ini?')\">🗑️</a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Belum ada data supplier</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
