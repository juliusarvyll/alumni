<?php include('db_connect.php'); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- FORM Panel -->
        <div class="col-lg-4 mb-4">
            <form action="" id="manage-course">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Course Form
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label class="control-label">Course Name</label>
                            <input type="text" class="form-control" name="course">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary">Save</button>
                        <button class="btn btn-secondary" type="button" onclick="$('#manage-course').get(0).reset()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- FORM Panel -->

        <!-- Table Panel -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <b>Course List</b>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Course</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $course = $conn->query("SELECT * FROM courses order by id asc");
                            while($row = $course->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++ ?></td>
                                <td><?php echo $row['course'] ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary edit_course" type="button" data-id="<?php echo $row['id'] ?>" data-course="<?php echo $row['course'] ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete_course" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
    td {
        vertical-align: middle !important;
    }
</style>

<script>
    $('#manage-course').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php?action=save_course',
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

    $('.edit_course').click(function() {
        var form = $('#manage-course');
        form.get(0).reset();
        form.find("[name='id']").val($(this).data('id'));
        form.find("[name='course']").val($(this).data('course'));
    });

    $('.delete_course').click(function() {
        if (confirm("Are you sure to delete this course?")) {
            var id = $(this).data('id');
            $.ajax({
                url: 'ajax.php?action=delete_course',
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
