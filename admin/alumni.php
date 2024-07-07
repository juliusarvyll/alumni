<?php include('db_connect.php'); ?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <b>List of Alumni</b>
                    <!-- <span class="float-right"><a class="btn btn-primary btn-sm" href="index.php?page=manage_alumni" id="new_alumni">
                        <i class="fa fa-plus"></i> New Entry
                    </a></span> -->
                </div>
                <div class="card-body">
                    <table class="table table-condensed table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Avatar</th>
                                <th class="">Name</th>
                                <th class="">Course Graduated</th>
                                <th class="">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $alumni = $conn->query("SELECT a.*, c.course, CONCAT(a.lastname, ', ', a.firstname, ' ', a.middlename) as name FROM alumnus_bio a INNER JOIN courses c ON c.id = a.course_id ORDER BY CONCAT(a.lastname, ', ', a.firstname, ' ', a.middlename) ASC");
                            while ($row = $alumni->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center">
                                    <div class="avatar">
                                        <img src="assets/uploads/<?php echo $row['avatar']; ?>" alt="">
                                    </div>
                                </td>
                                <td>
                                    <p><b><?php echo ucwords($row['name']); ?></b></p>
                                </td>
                                <td>
                                    <p><b><?php echo $row['course']; ?></b></p>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 1): ?>
                                        <span class="badge badge-primary">Verified</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Not Verified</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary view_alumni" type="button" data-id="<?php echo $row['id']; ?>">View</button>
                                    <button class="btn btn-sm btn-danger delete_alumni" type="button" data-id="<?php echo $row['id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset;
    }
    .avatar {
        display: flex;
        border-radius: 100%;
        width: 100px;
        height: 100px;
        align-items: center;
        justify-content: center;
        border: 3px solid;
        padding: 5px;
    }
    .avatar img {
        max-width: calc(100%);
        max-height: calc(100%);
        border-radius: 100%;
    }
</style>

<script>
    $(document).ready(function() {
        $('table').dataTable();
    });

    $('.view_alumni').click(function() {
        uni_modal("Bio", "view_alumni.php?id=" + $(this).data('id'), 'mid-large');
    });

    $('.delete_alumni').click(function() {
        _conf("Are you sure to delete this alumni?", "delete_alumni", [$(this).data('id')]);
    });

    function delete_alumni(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_alumni',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
