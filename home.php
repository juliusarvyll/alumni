<?php 
include 'admin/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="path/to/your/bootstrap.css">
    <link rel="stylesheet" href="path/to/your/font-awesome.css">
    <style>
        .container {
            background-color: white;
        }
        .text-white {
            color: white !important;
        }
        .event-list {
            margin-bottom: 20px;
        }
        .banner img {
            max-width: 100%;
            height: auto;
        }
        .read_more {
            margin-top: 10px;
        }
    </style>
</head>
<body>
        <div class="container-fluid ">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end mb-4 page-title">
                    <h3 class="text-black">Welcome to <?php echo $_SESSION['system']['name']; ?></h3>                    
            </div>
        </div>
    <div class="container mt-3 pt-2">
        <h4 class="text-center text-white">Upcoming Events</h4>
        <?php
        $event = $conn->query("SELECT * FROM events WHERE date_format(schedule, '%Y-%m-%d') >= '".date('Y-m-d')."' ORDER BY unix_timestamp(schedule) ASC");
        while($row = $event->fetch_assoc()):
            $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
            unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
            $desc = strtr(html_entity_decode($row['content']), $trans);
            $desc = str_replace(array("<li>", "</li>"), array("", ","), $desc);
        ?>
        <div class="card event-list" data-id="<?php echo $row['id'] ?>">
            <div class='banner'>
                <?php if(!empty($row['banner'])): ?>
                    <img src="admin/assets/uploads/<?php echo($row['banner']) ?>" alt="">
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row align-items-center justify-content-center text-center h-100">
                    <div class="">
                        <h3><b class="filter-txt"><?php echo ucwords($row['title']) ?></b></h3>
                        <div><small><p><b><i class="fa fa-calendar"></i> <?php echo date("F d, Y h:i A", strtotime($row['schedule'])) ?></b></p></small></div>
                        <hr>
                        <larger class="truncate filter-txt"><?php echo strip_tags($desc) ?></larger>
                        <br>
                        <hr class="divider" style="max-width: calc(80%)">
                        <button class="btn btn-primary float-right read_more" data-id="<?php echo $row['id'] ?>">Read More</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <script src="path/to/your/jquery.js"></script>
    <script src="path/to/your/bootstrap.js"></script>
    <script>
        $('.read_more').click(function() {
            location.href = "index.php?page=view_event&id=" + $(this).attr('data-id');
        });
        $('.banner img').click(function() {
            viewer_modal($(this).attr('src'));
        });
        $('#filter').keyup(function(e) {
            var filter = $(this).val();
            $('.card.event-list .filter-txt').each(function() {
                var txto = $(this).html();
                txt = txto;
                if ((txt.toLowerCase()).includes((filter.toLowerCase())) == true) {
                    $(this).closest('.card').toggle(true);
                } else {
                    $(this).closest('.card').toggle(false);
                }
            });
        });
    </script>
</body>
</html>
