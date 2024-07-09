<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<?php
session_start();
include('admin/db_connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

ob_start();
$query = $conn->query("SELECT * FROM system_settings limit 1");
if ($query) {
    $result = $query->fetch_array();
    foreach ($result as $key => $value) {
        if (!is_numeric($key)) {
            $_SESSION['system'][$key] = $value;
        }
    }
} else {
    die("Query failed: " . $conn->error);
}
ob_end_flush();
include('header.php');
?>

<style>
    body {
        background-color: #f8f9fa;
        color: #343a40;
        font-family: 'Arial', sans-serif;
    }
    .greentop {
        width: 100%;
        height: 3.75rem;
        background-color: #005b00;
    }
    .navbar {
        width: 100%;
        background-color: #ffffff;
        color: #343a40;
        padding: 1.4rem 1.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        transition: height 0.3s ease;
        display: flex;
        align-items: center;
    }
    .navbar .navbar-brand {
        position: absolute;
        top: -30px;
        left: 20px;
        display: flex;
        align-items: center;
    }
    .navbar img {
        position: relative;
        margin-left: 10rem;
        top: .2rem;
        width: 20rem;
    }
    .navbar .nav-link {
        padding: 10px 20px;
        text-decoration: none;
        font-size: 18px;
        color: #343a40;
        display: inline-block;
        transition: all 0.3s ease;
        border-radius: 4px;
        background-color: #ffffff;
        margin: 0 10px;
    }
    .navbar-nav-center {
        margin: 0 auto;
        display: flex;
        align-items: center;
    }
    .content {
        padding: 20px;
        margin-top: 60px;
    }
    @media (max-width: 990px) {
        .navbar img {
            margin-left: 1.5rem;
            z-index: 10000;
        }
        .navbar-nav {
            margin-top: 2rem;
        }
        .navbar {
            padding: 2rem 1.5rem;
            height: auto;
        }
    }
    @media (max-width: 576px) {
        .navbar img {
            width: 12rem;
            margin-left: 2rem;
            top: 14px;
        }
        .navbar {
            padding: .8rem;
            height: auto;
        }
        .navbar-nav {
            margin-top: 1.2rem;
        }
        .greentop {
        height: 2rem;
    }
    }
</style>

<body id="page-top">
    <div class="greentop"></div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="./">
            <img src="assets/img/Logo.png" alt="logo">
        </a>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav navbar-nav-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=alumni_list">Alumni</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=gallery">Gallery</a>
                </li>
                <?php if(isset($_SESSION['login_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=careers">Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=forum">Forums</a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=about">About</a>
                </li>
                <?php if(!isset($_SESSION['login_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="login">Login</a>
                </li>
                <?php else: ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="account_settings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['login_name'] ?> <i class="fa fa-angle-down"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="account_settings">
                        <a class="dropdown-item" href="index.php?page=my_account" id="manage_my_account">Manage Account</a>
                        <a class="dropdown-item" href="admin/ajax.php?action=logout2">Logout</a>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="content">
        <?php 
        $page = isset($_GET['page']) ? $_GET['page'] : "home";
        $allowed_pages = ['home', 'alumni_list', 'gallery', 'careers', 'forum', 'about', 'my_account'];
        if (in_array($page, $allowed_pages) && file_exists($page . '.php')) {
            include $page . '.php';
        } else {
            include '404.php';
        }
        ?>
    </div>

    <div id="preloader"></div>

<?php include('footer.php') ?>
<script>
    $(document).ready(function() {
        $('#preloader').fadeOut('slow', function() {
            $(this).remove();
        });

        $('#login').click(function() {
            uni_modal("Login", 'login.php');
        });
    });
</script>

<?php $conn->close() ?>

</body>
</html>