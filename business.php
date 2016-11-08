<?php
session_start();
require_once 'config.php';


if(isset($_POST['action'])){
    switch ($_POST['action']) {
        case "publishNews":
            
            if (!isset($_SESSION['user_id'])){
                $output = "login.php";
            } else {
                $output = "userHome.php";
            }

            echo json_encode($output);
            break;

        case "signup":
            
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $output = array();
            
            $error = '';
            $errorMsg = " ";
            
            $userResult = mysqli_query($dbConn,"SELECT * FROM users WHERE name_txt = '$name'");
            if(mysqli_num_rows($userResult) > 0) {
                $error = 0;
                $errorMsg .= "UserName '$name' already exists<br>";
            } else {
                $error = 1;
            }
            
            $userResult = mysqli_query($dbConn,"SELECT * FROM users WHERE email = '$email'");
            if(mysqli_num_rows($userResult) > 0) {
                $error = 0;
                $errorMsg .= "Email ID '$email' already exists<br>";
            }
            
            if ($error === 1) {
                
                $activation_code = md5($email.time());
                $insertUserSQL = "INSERT INTO users (name_txt, email, activation_code_txt, active_ind, created_dt, updated_dt) VALUES('$name', '$email', '$activation_code', 'NO', now(), now());";
                $insertUserResult = mysqli_query($dbConn, $insertUserSQL);
                
                if ($insertUserResult) {
                    
                    $href_link = "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?')."/activation.php?code=".$activation_code;
                    $subject = 'News publishing portal - Email verification';
                    $headers = "From: its.mathy@gmail.com; \r\n";
                    $headers .= "Reply-To: its.mathy@gmail.com; \r\n";
                    $headers .= "MIME-Version: 1.0; \r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1; \r\n";
                    $message = "<html><body>";
                    $message .= "Dear $name,<br /><br />To verify your account, please click the following link.  <br /><br /> <a href='$href_link' style='text-decoration:none;padding:5px;background-color:red;color:white'>Verify Email</a> <br /><br />If you are unable to do so, reply to this email with the trouble you are facing. <br /><br />Best Regards,<br />News Publishing Portal Admin.<br /><br /><br /><br />Do not forward this email.  The verify link is private.</body></html>";

                    $mail_status = mail($email, $subject, $message,$headers);

                    if($mail_status) {
                        $output[0] = 1;
                    } else {
                        $output[0] = 0;
                        $output[1] = "Registration is complete.  Error sending activation link.  Please report to admin";
                    }
                } else {
                    $output[0] = 0;
                    $output[1] = "Registration is incomplete.  Please report to admin";
                    $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\n Mysqli error while executing $insertUserSQL. \n Error details: ".  mysqli_error($dbConn);
                    $file_pointer = fopen("errorlog.txt", "a");
                    fwrite($file_pointer, $errorlogmessage);			
                    fclose($file_pointer);
                }
            } else {
                $output[0] = 0;
                $output[1] = $errorMsg;
            }
            
            echo json_encode($output);
            break;
            
            
        case "login":
            
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $output = array();
            
            $userSQL = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
            $userResult = mysqli_query($dbConn, $userSQL);
            
            if (mysqli_num_rows($userResult) === 1) {
                $_SESSION = mysqli_fetch_assoc($userResult);
                $output[0] = 1;
            } else {
                $output[0] = 0;
                $output[1] = "Invalid credentials";
            }
            
            echo json_encode($output);
            break;
            
        case "changePassword":
            
            $password = htmlspecialchars($_POST['password']);
            $id = htmlspecialchars($_POST['userid']);
            $output = array();
            
            $updateSQL = "UPDATE users SET password = '$password' WHERE user_id = ".$id;
            $updateRes = mysqli_query($dbConn, $updateSQL);
            
            if ($updateRes) {
                $output[0] = 1;
            } else {
                $output[0] = 0;
                $output[1] = "Error while updating password. Report to admin";
                $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\n Mysqli error while executing $updateSQL. \n Error details: ".  mysqli_error($dbConn);
                $file_pointer = fopen("errorlog.txt", "a");
                fwrite($file_pointer, $errorlogmessage);			
                fclose($file_pointer);
            }
            
            echo json_encode($output);
            break;
            
            
        case "addNews":
            
            $title = mysqli_real_escape_string($dbConn,$_POST['title']);
            $article = mysqli_real_escape_string($dbConn, $_POST['article']);
            $file = htmlspecialchars($_POST['image']);
            $output = array();
            
            $tableResult = mysqli_query($dbConn,"SHOW TABLE STATUS FROM ".$dbDatabase." WHERE name = 'news_repository'");
            $data = mysqli_fetch_assoc($tableResult);
            $next_id = $data['Auto_increment'];
            $file_prefix = "NEWS_ID_".$next_id."_";

            $image_status = validateImage($file, $file_prefix);
            
            if ($image_status['status'] === 'COMPLETE') {
                $insertSQL = "INSERT INTO news_repository (title, image_path, news_content, user_id, created_dt) VALUES ('$title', '".$image_status['fileName']."', '$article',".$_SESSION['user_id'].",now());";;
                $insertResult = mysqli_query($dbConn, $insertSQL);
                
                if ($insertResult) {
                    $output[0] = 1;
                    $output[1] = "News published successfully";
                } else {
                    $output[0] = 0;
                    $output[1] = "Error publishing news.  Report to admin";
                    $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\n Mysqli error while executing $insertSQL. \n Error details: ".  mysqli_error($dbConn);
                    $file_pointer = fopen("errorlog.txt", "a");
                    fwrite($file_pointer, $errorlogmessage);			
                    fclose($file_pointer);
                }
            } else {
                $output[0] = 0;
                $output[1] = $image_status['errorMsg'];
                $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\n Error while validating and uploading image ".$image_status['errorMsg'];
                $file_pointer = fopen("errorlog.txt", "a");
                fwrite($file_pointer, $errorlogmessage);			
                fclose($file_pointer);
            }
            
            echo json_encode($output);
            break;
        
        case "getUserNewsList":
            
            $output = array();
            
            if (isset($_POST['user'])) {
                $condition = "WHERE news_repository.user_id = ".$_SESSION['user_id'];
            } else {
                $condition = ' ';
            }
            
            $userNewsSQL = "SELECT news_repository.news_id as news_id, news_repository.user_id as user_id, news_repository.created_dt as created_dt, title, image_path, news_content, name_txt FROM news_repository INNER JOIN users ON users.user_id = news_repository.user_id $condition ORDER BY created_dt DESC";
            $userNewsResult = mysqli_query($dbConn, $userNewsSQL);
            
            if ($userNewsResult) {
                $result = array();
                while ($news = mysqli_fetch_assoc($userNewsResult)){
                    $result[] = $news;
                }
                $output[0] = 1;
                $output[1] = $result;
            } else {
                $output[0] = 1;
                $output[1] = "Error while listing news.  Report to admin";
                $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\n Error while executing SQL ".$userNewsSQL. "\nMysqli Error: ".  mysqli_error($dbConn);
                $file_pointer = fopen("errorlog.txt", "a");
                fwrite($file_pointer, $errorlogmessage);			
                fclose($file_pointer);
            }
            
            echo json_encode($output);
            break;
            
            
        case "viewNews":
            
            $output = array();
            $id = htmlspecialchars($_POST['id']);
            
            
            $userNewsSQL = "SELECT news_repository.user_id as user_id, news_repository.created_dt as created_dt, title, image_path, news_content, name_txt FROM news_repository INNER JOIN users ON users.user_id = news_repository.user_id WHERE news_id = $id";
            $userNewsResult = mysqli_query($dbConn, $userNewsSQL);
            
            if ($userNewsResult) {
                $result = array();
                while ($news = mysqli_fetch_assoc($userNewsResult)){
                    $result[] = $news;
                }
                $output[0] = 1;
                $output[1] = $result;
            } else {
                $output[0] = 1;
                $output[1] = "Error while listing news.  Report to admin";
                $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\n Error while executing SQL ".$userNewsSQL. "\nMysqli Error: ".  mysqli_error($dbConn);
                $file_pointer = fopen("errorlog.txt", "a");
                fwrite($file_pointer, $errorlogmessage);			
                fclose($file_pointer);
            }
            
            echo json_encode($output);
            break;
            
        default:
            break;
    }
} else {
    $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."---------------\n Action variable not set";
    $file_pointer = fopen("errorlog.txt", "a");
    fwrite($file_pointer, $errorlogmessage);			
    fclose($file_pointer);
}


function validateImage($file,$prefix) {
    
    $target_dir = "images/";
    $target_file = $target_dir . $prefix . basename($_FILES[$file]["name"]);
    
    $validUpload = 1;
    $errorMessage = '';
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    if(file_exists($target_file)) {
        $validUpload = 0;
        $errorMessage .= "File name already exists.<br>";
    }
    
    $check = getimagesize($_FILES[$file]["tmp_name"]);
    if ($check !== false) {
        $validUpload = 1;
    } else {
        $validUpload = 0;
        $errorMessage .= "Fake Image.<br>";
    }
    if(strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "gif" ) {
        $validUpload = 0;
        $errorMessage .= "Only JPG, PNG, JPEG, GIF files are allowed.<br>";
        $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."--------------Invalid Image type is -".$imageFileType."-";
        $file_pointer = fopen("errorlog.txt", "a");
        fwrite($file_pointer, $errorlogmessage);			
        fclose($file_pointer);
    }
    
    // Check file size
    if($_FILES[$file]["size"] > 5242880) {
        $validUpload = 0;
        $errorMessage .= "File size above 5MB is not allowed.<br>";
        $errorlogmessage = "\n------------------------".date('m/d/Y h:i:s a', time())."--------------File size greater than allowed size that is ".$_FILES[$file]["size"];
        $file_pointer = fopen("errorlog.txt", "a");
        fwrite($file_pointer, $errorlogmessage);			
        fclose($file_pointer);
    }

    if($validUpload == 1) {
        if(move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
            $validUpload = 1;
        } else {
            $errorMessage = "Error uploading file.<br>";
            $validUpload = 0;
        }
    } 
    
    if($validUpload == 1) {
        $imageUpload["status"] = "COMPLETE";
        $imageUpload["fileName"] = $target_file;
    } else {
        $imageUpload["status"] = "ERROR";
        $imageUpload["errorMsg"] = $errorMessage; 
    }
    
    return $imageUpload;
}
