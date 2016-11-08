<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
        <script src="js/user.js" type="text/javascript"></script>
    </head>
    
    <body>
        <div class="container">
            <div class="row">
                <p class="pull-right">Welcome <?php echo $_SESSION['name_txt']?>!<br /><a href="index.php?logout=1">Logout</a><br /></p>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2 class="text-center">Public News Publishing Portal</h2><br>
                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addNewsModal" >Publish Your News</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 alert alert-info">
                    <table id="newsListTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>News</th>
                                <th>Published On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    
                </div>
            </div>
            
            
        </div>
        <div class="modal fade" id="addNewsModal" role="dialog" aria-labelledby="addNewsModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="model-title"><h3>Add News</h3></div>
                    </div>
                    <div class="modal-body">
                        <form role="form" id="addNewsForm" class="smart-form" method="POST" enctype=multipart/form-data>
                            <div class="row">
                                <div class="col-md-3">Title * </div>
                                <div class="col-md-9"><input type="text" name="title" class="form-control" autofocus="autofocus" required /></div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-3">Image * </div>
                                <div class="col-md-9"><input type="file" name="image" required="required" ><small>Size < 5MB; Only jpg, gif, png files</small></div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-3">Article * </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"><textarea required="required" name="article" class="form-control" rows="10"></textarea></div>
                            </div><br>
                            <input type="submit" class="btn btn-primary btn-primary pull-right" value="Publish Now">&nbsp;
                            <div id="modalMsg" class="text-danger"></div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        
        
    </body>
</html>
