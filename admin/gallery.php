<?php include('db_connect.php'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- FORM Panel -->
        <div class="col-lg-4 mb-4">
            <form action="" id="manage-gallery">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Upload
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="img" class="control-label">Image</label>
                            <input type="file" class="form-control-file" name="img" id="img" onchange="displayImg(this,$(this))">
                        </div>
                        <div class="form-group text-center">
                            <img src="" alt="Image Preview" id="cimg" class="img-fluid border">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Short Description</label>
                            <textarea class="form-control" name="about" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary">Save</button>
                        <button class="btn btn-secondary" type="button" onclick="$('#manage-gallery').get(0).reset()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- FORM Panel -->

        <!-- Table Panel -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <b>Gallery List</b>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">IMG</th>
                                <th class="text-center">Gallery</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $img = array();
                            $fpath = 'assets/uploads/gallery';
                            $files = is_dir($fpath) ? scandir($fpath) : array();
                            foreach($files as $val){
                                if(!in_array($val, array('.','..'))){
                                    $n = explode('_',$val);
                                    $img[$n[0]] = $val;
                                }
                            }
                            $gallery = $conn->query("SELECT * FROM gallery order by id asc");
                            while($row = $gallery->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++ ?></td>
                                <td class="text-center">
                                    <img src="<?php echo isset($img[$row['id']]) && is_file($fpath.'/'.$img[$row['id']]) ? $fpath.'/'.$img[$row['id']] : '' ?>" class="gimg img-thumbnail" alt="">
                                </td>
                                <td><?php echo $row['about'] ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary edit_gallery" type="button" data-id="<?php echo $row['id'] ?>" data-about="<?php echo $row['about'] ?>" data-src="<?php echo isset($img[$row['id']]) && is_file($fpath.'/'.$img[$row['id']]) ? $fpath.'/'.$img[$row['id']] : '' ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete_gallery" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Table Panel -->
    </div>
</div>

<style>
    #cimg {
        max-height: 250px;
        width: auto;
    }
    .gimg {
        max-height: 100px;
        width: auto;
    }
</style>

<script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#manage-gallery').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php?action=save_gallery',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert("Data successfully added");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else if (resp == 2) {
                    alert("Data successfully updated");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    });

    $('.edit_gallery').click(function() {
        var form = $('#manage-gallery');
        form.get(0).reset();
        form.find("[name='id']").val($(this).data('id'));
        form.find("[name='about']").val($(this).data('about'));
        form.find("#cimg").attr('src', $(this).data('src'));
    });

    $('.delete_gallery').click(function() {
        if (confirm("Are you sure to delete this data?")) {
            var id = $(this).data('id');
            $.ajax({
                url: 'ajax.php?action=delete_gallery',
                method: 'POST',
                data: { id: id },
                success: function(resp) {
                    if (resp == 1) {
                        alert("Data successfully deleted");
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                }
            });
        }
    });

    $('table').dataTable();
</script>
