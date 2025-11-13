<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "conn.php"; // koneksi database
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Simple PHP Auth System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* --- Tampilan produk seperti kotak Shopee --- */
        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 25px;
            padding: 10px;
        }

        .product-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .product-card h4 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #333;
        }

        .product-card p {
            color: #777;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .product-card .price {
            font-weight: bold;
            color: #e67e22;
            font-size: 16px;
        }

        .product-card .stock {
            font-weight: bold;
            font-size: 14px;
        }

        .stock-available {
            color: green;
        }

        .stock-empty {
            color: red;
        }

        nav {
            background: #0d6efd;
            color: white;
            padding: 10px;
            border-radius: 8px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 8px;
            font-weight: 500;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a> | 

    <?php if (!isset($_SESSION['user'])) { ?>
        <a href="index.php?page=login">Login</a> |
        <a href="index.php?page=register">Register</a>
    <?php } else { ?>
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="index.php?page=dashboard">Dashboard</a> |
            <a href="index.php?page=product">Master Product</a> |
            <a href="index.php?page=user">Master User</a> |
            <a href="index.php?page=supplier">Master Supplier</a> |
        <?php } else { ?>
            <a href="index.php?page=data">Data</a> |
        <?php } ?>
        <a href="logout.php">Logout (<?= $_SESSION['user']; ?>)</a>
    <?php } ?>
</nav>

<hr>

<?php
// ============= ROUTER =============
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case "login": include "login.php"; break;
        case "register": include "register.php"; break;
        case "dashboard": include "dashboard.php"; break;
        case "product": include "product.php"; break;
        case "user": include "user.php"; break;
        case "supplier": include "supplier.php"; break;
        case "data": include "data.php"; break;
        case "edit_user": include "edit_user.php"; break;
        case "product_list": 
            include "product_list.php"; break;
        default: echo "<h2>404 - Page not found!</h2>"; break;
    }
} 
// ============= HALAMAN UTAMA =============
else {
    echo "<h2>Welcome 👋</h2>";
    
    if (!isset($_SESSION['user'])) {
        echo "<p>Silakan login atau register untuk masuk ke sistem ✅</p>";
    } else {
        echo "<p>Halo <b>".$_SESSION['user']."</b>, kamu login sebagai <b>".$_SESSION['role']."</b>.</p>";
        echo "<p>Silakan pilih menu di atas 😊</p>";
    }

    echo "<h2>Daftar Produk Tersedia</h2>";

    // Query produk dari supplier
    $query = "
        SELECT p.id, p.nama AS nama_produk, p.harga, p.stock, s.nama_pt, s.id AS supplier_id
        FROM products p 
        LEFT JOIN suppliers s ON s.id = p.supplier_id
        ORDER BY p.id DESC
    ";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<div class='product-container'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $link_supplier = $row['supplier_id'] ? "index.php?page=product_list&supplier_id={$row['supplier_id']}" : "#";
            $stock_class = $row['stock'] > 0 ? "stock-available" : "stock-empty";
            $stock_text = $row['stock'] > 0 ? $row['stock'] : "Habis";

            echo "
                <div class='product-card'>
                    <h4>".htmlspecialchars($row['nama_produk'])."</h4>
                    <p>Supplier: ".($row['supplier_id'] ? "<a href='{$link_supplier}'>".htmlspecialchars($row['nama_pt'])."</a>" : "Tidak ada")."</p>
                    <div class='price'>Rp ".number_format($row['harga'], 0, ',', '.')."</div>
                    <p class='stock {$stock_class}'>Stock: {$stock_text}</p>
                </div>
            ";
        }
        echo "</div>";
    } else {
        echo "<p>Tidak ada produk yang tersedia saat ini.</p>";
    }
}
?>

</body>
</html>
