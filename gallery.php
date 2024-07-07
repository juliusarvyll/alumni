<?php 
include 'admin/db_connect.php'; 
?>
    <style>
        body {
            background-color: #f9f9f9;
        }
        .gallery-container {
            margin-top: 6rem;
        }
        .gallery-item {
            margin-bottom: 1.5rem;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .gallery-item:hover {
            transform: scale(1.05);
        }
        .gallery-img img {
            border-radius: 8px;
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .card-body {
            text-align: left;
            padding: 0.5rem;
        }
        .card-title {
            font-size: 1rem;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .card-text {
            font-size: 0.875rem;
            color: #777;
            margin-top: 0.25rem;
        }
        header.masthead {
            min-height: 10vh;
            background-color: #006634;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .masthead h3 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container gallery-container">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            $img = array();
            $fpath = 'admin/assets/uploads/gallery';
            $files = is_dir($fpath) ? scandir($fpath) : array();
            foreach($files as $val){
                if(!in_array($val, array('.','..'))){
                    $n = explode('_', $val);
                    $img[$n[0]] = $val;
                }
            }
            $gallery = $conn->query("SELECT * from gallery order by id desc");
            while($row = $gallery->fetch_assoc()):
            ?>
            <div class="col gallery-item" data-id="<?php echo $row['id'] ?>">
                <div class="card h-100 border-0">
                    <div class="gallery-img">
                        <img src="<?php echo isset($img[$row['id']]) && is_file($fpath.'/'.$img[$row['id']]) ? $fpath.'/'.$img[$row['id']] :'' ?>" alt="">
                    </div>
                    <div class="card-body">
                        <p class="card-title"><?php echo ucwords($row['about']) ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

    <script>
        $('.gallery-item').click(function(){
            location.href = "index.php?page=view_gallery&id="+$(this).attr('data-id');
        });
        $('.gallery-img img').click(function(){
            viewer_modal($(this).attr('src'));
        });
    </script>
</body>
</html>
