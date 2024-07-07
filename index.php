<!DOCTYPE html>
<html lang="en">
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
        display: flex;
        font-family: 'Arial', sans-serif;
    }
    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #ffffff;
        color: #343a40;
        padding-top: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .sidebar a {
        padding: 10px 20px;
        text-decoration: none;
        font-size: 18px;
        color: #343a40;
        display: block;
        transition: all 0.3s ease;
        border-radius: 4px;
    }
    .sidebar a:hover {
        transform: translateX(10px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .sidebar .navbar-brand {
        font-weight: bold;
        color: #007bff;
        padding: 15px 20px;
        display: block;
        text-align: center;
    }
    .sidebar .navbar-brand img {
        height: 40px;
        margin-right: 10px;
    }
    .sidebar .nav-link {
        display: flex;
        align-items: center;
    }
    .sidebar .nav-link i {
        margin-right: 10px;
    }
    .content {
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
    }
    .dropdown-menu {
        left: -2.5em;
    }
    .modal-content {
        border-radius: 8px;
        overflow: hidden;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
    }
    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
    .btn-primary:hover, .btn-secondary:hover {
        opacity: 0.8;
    }
    .table {
        background-color: #ffffff;
        border: none;
    }
    .table th, .table td {
        border: none;
    }
    .toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1050;
        min-width: 200px;
        background-color: #343a40;
        color: white;
        padding: 1rem;
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    #preloader {
        position: fixed;
        left: 0;
        top: 0;
        z-index: 9999;
        width: 100%;
        height: 100%;
        overflow: visible;
        background: #ffffff;
    }
    footer {
        background: #ffffff;
    }
</style>

<body id="page-top">
    <!-- Sidebar -->
    <div class="sidebar">
        <a class="navbar-brand" href="./">
            <img src="assets/img/logo.png" alt="Logo">
            <?php echo $_SESSION['system']['name'] ?>
        </a>
        <a class="nav-link" href="index.php?page=home"><i class="fa fa-home"></i> Home</a>
        <a class="nav-link" href="index.php?page=alumni_list"><i class="fa fa-users"></i> Alumni</a>
        <a class="nav-link" href="index.php?page=gallery"><i class="fa fa-image"></i> Gallery</a>
        <?php if(isset($_SESSION['login_id'])): ?>
        <a class="nav-link" href="index.php?page=careers"><i class="fa fa-briefcase"></i> Jobs</a>
        <a class="nav-link" href="index.php?page=forum"><i class="fa fa-comments"></i> Forums</a>
        <?php endif; ?>
        <a class="nav-link" href="index.php?page=about"><i class="fa fa-info-circle"></i> About</a>
        <?php if(!isset($_SESSION['login_id'])): ?>
        <a class="nav-link" href="#" id="login"><i class="fa fa-sign-in-alt"></i> Login</a>
        <?php else: ?>
        <div class="dropdown mr-4">
            <a href="#" class="nav-link" id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user"></i> <?php echo $_SESSION['login_name'] ?> <i class="fa fa-angle-down"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="account_settings">
                <a class="dropdown-item" href="index.php?page=my_account" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
                <a class="dropdown-item" href="admin/ajax.php?action=logout2"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

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

    <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white"></div>
    </div>

    <div class="modal fade" id="confirm_modal" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirm" onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uni_modal" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uni_modal_right" role="dialog">
        <div class="modal-dialog modal-full-height modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="fa fa-arrow-right"></span>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewer_modal" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                <img src="" alt="">
    </div>
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

        // Additional scripts...
    });
</script>

<?php $conn->close() ?>

</body>
</html>