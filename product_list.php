<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<h3 style='text-align:center;color:red;'>Access Denied!</h3>";
    exit;
}

include "conn.php";

// Pastikan supplier_id ada
if (!isset($_GET['supplier_id'])) {
    echo "<script>alert('Supplier tidak ditemukan!'); window.location='index.php?page=product';</script>";
    exit;
}

$supplier_id = $_GET['supplier_id'];

// Ambil info supplier
$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id=?");
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();
$supplier = $result->fetch_assoc();
$stmt->close();

if (!$supplier) {
    echo "<script>alert('Supplier tidak ditemukan!'); window.location='index.php?page=product';</script>";
    exit;
}

// === TAMBAH PRODUK ===
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stock = $_POST['stock']; // menggunakan 'stock'

    $stmt = $conn->prepare("INSERT INTO products (supplier_id, nama, harga, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $supplier_id, $nama, $harga, $stock);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Produk berhasil ditambahkan!'); window.location='index.php?page=product_list&supplier_id={$supplier_id}';</script>";
}

// === UPDATE PRODUK ===
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stock = $_POST['stock']; // menggunakan 'stock'

    $stmt = $conn->prepare("UPDATE products SET nama=?, harga=?, stock=? WHERE id=? AND supplier_id=?");
    $stmt->bind_param("siiii", $nama, $harga, $stock, $id, $supplier_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Produk berhasil diupdate!'); window.location='index.php?page=product_list&supplier_id={$supplier_id}';</script>";
}

// === HAPUS PRODUK ===
if (isset($_GET['hapus_produk'])) {
    $id = $_GET['hapus_produk'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id=? AND supplier_id=?");
    $stmt->bind_param("ii", $id, $supplier_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Produk berhasil dihapus!'); window.location='index.php?page=product_list&supplier_id={$supplier_id}';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Produk Supplier - <?= htmlspecialchars($supplier['nama_pt']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f4f6f9; }
.card { border-radius: 12px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); border: none; }
.table thead { background-color: #0d6efd; color: white; }
.btn { border-radius: 8px; }
</style>
</head>
<body>
<div class="container py-4">
<h2 class="text-center mb-4">Produk Supplier: <?= htmlspecialchars($supplier['nama_pt']); ?></h2>

<div class="row">
    <!-- Form Tambah Produk -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Tambah Produk</div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="tambah" class="btn btn-success">+ Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">Daftar Produk</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stock</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM products WHERE supplier_id=? ORDER BY id DESC");
                            $stmt->bind_param("i", $supplier_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($p = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$p['id']}</td>
                                        <td>".htmlspecialchars($p['nama'])."</td>
                                        <td>Rp ".number_format($p['harga'],0,',','.')."</td>
                                        <td>{$p['stock']}</td>
                                        <td>
                                            <a href='index.php?page=product_list&supplier_id={$supplier_id}&edit={$p['id']}' class='btn btn-warning btn-sm'>✏️</a>
                                            <a href='index.php?page=product_list&supplier_id={$supplier_id}&hapus_produk={$p['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus produk ini?')\">🗑️</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Belum ada produk</td></tr>";
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Form edit produk
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=? AND supplier_id=?");
    $stmt->bind_param("ii", $edit_id, $supplier_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_product = $result->fetch_assoc();
    $stmt->close();
?>
<div class="card mt-4">
    <div class="card-header bg-warning text-dark">Edit Produk</div>
    <div class="card-body">
        <form method="POST" class="row g-3">
            <input type="hidden" name="id" value="<?= $edit_product['id']; ?>">
            <div class="col-12">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($edit_product['nama']); ?>" required>
            </div>
            <div class="col-6">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= $edit_product['harga']; ?>" required>
            </div>
            <div class="col-6">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="<?= $edit_product['stock']; ?>" required>
            </div>
            <div class="col-12 text-end">
                <button type="submit" name="update" class="btn btn-warning">💾 Update</button>
                <a href="index.php?page=product_list&supplier_id=<?= $supplier_id; ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php } ?>

</div>
</body>
</html>
