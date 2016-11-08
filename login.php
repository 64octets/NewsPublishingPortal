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
                    <a href="index.php" class="pull-right">Back to Home</a>
                </div>
            </div>
            <br>
            <div class="row">
                <h4 class="text-center">New user? Register to publish your news | Already a member, Login to publish your news</h4><br>
                <div class="col-md-offset-1 col-md-3 well">
                    <h3 class="text-center">Login</h3><br>
                    <form role="form" id="loginForm">
                        <input type="email" class="form-control" name="email" required placeholder="Email Address" /><br>
                        <input type="password" class="form-control" name="password" required placeholder="Password" /><br>
                        <div id="login_error"></div>
                        <button type="submit" class="btn btn-primary pull-right">Login</button>
                    </form>
                </div>
                <div class="col-md-2 vertical-hr">
                    &nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>
                </div>
                <div class="col-md-offset-2 col-md-3 well">
                    <h3 class="text-center">Register</h3><br>
                    <form role="form" id="signupForm">
                        <input type="text" class="form-control" name="name" required placeholder="User Name" /><br>
                        <input type="email" class="form-control" name="email" required placeholder="Email Address" /><br>
                        <div id="signup_error"></div>
                        <button type="submit" class="btn btn-primary pull-right">Sign Up</button>
                    </form>
                </div>
            </div>
        </div>
        
    </body>
</html>
