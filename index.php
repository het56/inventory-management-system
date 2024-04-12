<?php
include_once 'C:\xampp\htdocs\stock_system\php_action\db_connect.php';

session_start();

if(isset($_SESSION['user_id'])) {
    header('location:http://localhost/stock_system/dashboard.php');
}

$errors = array();

if($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
   
    if (empty($username) || empty($password) || empty($captcha)) { 
        if(empty($username)) {
            $errors[] = "Username is required";
        }

        if(empty($password)) {
            $errors[] = "Password is required";
        }

        if(empty($captcha)) {
            $errors[] = "Captcha is required";
        }
    } else {
        // Validate captcha
        if($_SESSION['captcha'] != $captcha) {
            $errors[] = "Incorrect Captcha";
        } else {
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = $connect->query($sql);

            if($result->num_rows == 1) {
                $password = md5($password);
                $mainSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
                $mainResult = $connect->query($mainSql);

                if($mainResult->num_rows == 1) {
                    $value = $mainResult->fetch_assoc(); 
                    $user_id = $value['user_id'];
                    $_SESSION['user_id'] = $user_id;
                    header('location:http://localhost/stock_system/dashboard.php');
                } else {
                    $errors[] = "Incorrect Password";
                }
            } else {
                $errors[] = "Username does not exist";
            }
        }
    }
}

// Generate random numeric captcha
$captcha = mt_rand(1000, 9999);
$_SESSION['captcha'] = $captcha;
?>

<!DOCTYPE html>
<html>
<head>
<title>Stock Management System</title>
<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="/stock_system/assets/bootstrap/css/bootstrap.min.css"> <!-- bootstrap theme -->
<link rel="stylesheet" type="text/css" href="/stock_system/assets/bootstrap/css/bootstrap-theme.min.css"> <!-- font awesome -->
<link rel="stylesheet" type="text/css" href="/stock_system/assets/font-awesome/css/fontawesome.min.css"> <!-- custom css -->
<link rel="stylesheet" type="text/css" href="/stock_system/custom/css/custom.css">
<!-- jquery -->
<script type="text/javascript" src="/stock_system/assets/jquery/jquery.min.js"></script> <!-- jqueryui -->
<link rel="stylesheet" type="text/css" href="/stock_system/assets/jquery-ui/jquery-ui.min.css"> 
<script type="text/javascript" src="/stock_system/assets/jquery-ui/jquery-ui.min.js"></script> <!-- bootstrap js -->
<script type="text/javascript" src="/stock_system/assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row vertical">
        <div class="col-md-5 col-md-offset-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3> 
                </div> 
                <div class="panel-body"> 
                    <div class="messages">
                        <?php 
                        if($errors) {
                            foreach ($errors as $key => $value) {
                                echo '<div class="alert alert-warning" role="alert"><i class="glyphicon glyphicon-exclamation-sign"></i>'.$value.'</div>';
                            }
                        } 
                        ?>
                    </div>
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="loginForm">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="captcha" class="col-sm-2 control-label">Captcha</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Captcha" required>
                            </div>
                            <div class="col-sm-5">
                                <span id="captchaText"><?php echo $captcha; ?></span>
                                <button type="button" class="btn btn-default" id="refreshCaptcha">Refresh</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default"> <i class="glyphicon glyphicon-log-in"></i> Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
</div>
<script type="text/javascript" src="script.js"></script>
</body>
</html>
