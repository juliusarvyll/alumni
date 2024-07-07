<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .login-container form {
            margin-bottom: 20px;
        }
        .login-container button {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <h2 class="text-center">Login</h2>
        <form id="login-frm">
            <div class="form-group">
                <label for="username">Email</label>
                <input type="email" id="username" name="username" required class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="text-center">Don't have an account? <a href="index.php?page=signup">Create New Account</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#login-frm').submit(function(e){
            e.preventDefault()
            $('#login-frm button[type="submit"]').attr('disabled',true).html('Logging in...');
            if($(this).find('.alert-danger').length > 0 )
                $(this).find('.alert-danger').remove();
            $.ajax({
                url:'admin/ajax.php?action=login2',
                method:'POST',
                data:$(this).serialize(),
                error:err=>{
                    console.log(err)
                    $('#login-frm button[type="submit"]').removeAttr('disabled').html('Login');
                },
                success:function(resp){
                    if(resp == 1){
                        location.href ='<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php?page=home' ?>';
                    }else if(resp == 2){
                        $('#login-frm').prepend('<div class="alert alert-danger">Your account is not yet verified.</div>')
                        $('#login-frm button[type="submit"]').removeAttr('disabled').html('Login');
                    }else{
                        $('#login-frm').prepend('<div class="alert alert-danger">Email or password is incorrect.</div>')
                        $('#login-frm button[type="submit"]').removeAttr('disabled').html('Login');
                    }
                }
            })
        })
    </script>
</body>
</html>
