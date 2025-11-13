<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

// Cek hanya admin yang boleh mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<h3>Akses ditolak ❌</h3>";
    echo "<p>Halaman ini hanya untuk admin.</p>";
    exit;
}

// Ambil data user
$query = "SELECT id, username, email FROM users ORDER BY id ASC";
$result = $conn->query($query);

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id='$id'");
    echo "<script>alert('User berhasil dihapus'); window.location='index.php?page=user';</script>";
}
?>

<style>
.table-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
    width: 60%;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 10px;
    text-align: left;
}
th {
    background: #f2f2f2;
}
a.action-link {
    margin-right: 10px;
    color: blue;
    text-decoration: none;
}
a.action-link:hover {
    text-decoration: underline;
}
</style>

<div class="table-box">
    <h3>Master User</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['username']; ?></td>
            <td><?= $row['email']; ?></td>
            <td>
                <a class="action-link" href="index.php?page=edit_user&id=<?= $row['id']; ?>">Edit</a>
                <a class="action-link" href="index.php?page=user&delete=<?= $row['id']; ?>" onclick="return confirm('Hapus user ini?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
