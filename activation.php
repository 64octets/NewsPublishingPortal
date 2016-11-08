<?php

require_once 'config.php';
$message = "";

if ((!empty($_GET['code'])) && (isset($_GET['code']))) {
    $code = mysqli_real_escape_string($dbConn,$_GET['code']);

    //check if activation code exists in user table
    $result = mysqli_query($dbConn, "SELECT user_id FROM users WHERE activation_code_txt = '".$code. "'");
    
        
    if(mysqli_num_rows($result) > 0) {
        //get user_id from publisher table 
        $row = mysqli_fetch_assoc($result);
        
        $checkStatus = mysqli_query($dbConn, "SELECT user_id FROM users WHERE activation_code_txt = '".$code. "' AND active_ind = 'NO'");
        
        if(mysqli_num_rows($checkStatus) == 1) {
            
            //update user table
            $updateSQL = "UPDATE users SET active_ind = 'YES',updated_dt = now() WHERE activation_code_txt = '$code' ;";
            $updateStatus = mysqli_query($dbConn, $updateSQL);
            
            if ($updateStatus) {
                $error = 1;
                $message =  "Congratulations!  Your account is activated now.  Please set a password";
            } else {
                $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\nMysqli error: ".  mysqli_error($dbConn)." \n While executing ".$updateSQL."\n------------------------";
                $file_pointer = fopen("errorlog.txt", "a");
                fwrite($file_pointer, $errorlogmessage);			
                fclose($file_pointer);
                $message =  "Error in activating your account.  Report to admin";
            }
        } else {
            $error = 1;
            $message =  "Your account is already active. Please set a password";
        }
    } else {
        $error = 0;
        $message = "Wrong activation code.  Report to admin";
    }
} else {
    $error = 0;
    $message = "Sorry, you shouldn't be here";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>News Publishing Portal</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="css/custom.css" rel="stylesheet" type="text/css"/>
        <script src="js/custom.js" type="text/javascript"></script>
    </head>
    
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2 class="text-center">Public News Publishing Portal</h2><br>
                </div>
            </div>
            <br>
            <div class="alert alert-info"><?php echo $message?></div>
            <?php if ($error === 1) {?>
            <div class="row">
                <div class="col-md-4 col-md-offset-4 well">
                    <h3>Change Password</h3><br>
                    <form id="change_password_form">
                        <input type="hidden" name="userid" value="<?php echo $row['user_id']?>" />
                        <div class="row">
                            <div class="col-md-5">
                                <p>Password</p>
                            </div>
                            <div class="col-md-7">
                                <input type="password" name="password" required class="form-control" />
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-4">
                                <a href="login.php" class="btn btn-primary pull-left">Login</a>
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary pull-right ">Update</button>
                            </div>
                        </div>
                        <div id="change_password_error"></div>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>
        
    </body>
</html>
