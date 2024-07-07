<nav id="sidebar" class="navbar navbar-expand-md navbar-white bg-white flex-column">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav flex-column ">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=gallery">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=courses">Course List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=alumni">Alumni List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=jobs">Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=events">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=forums">Forum</a>
                </li>
                <?php if($_SESSION['login_type'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=users">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=site_settings">System Settings</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
	$('.nav_collapse').click(function(){
        console.log($(this).attr('href'))
        $($(this).attr('href')).collapse()
    })
    $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
