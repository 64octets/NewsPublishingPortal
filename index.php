<?php 
session_start();

if (isset ($_GET['logout'])) {
    if ($_GET['logout'] === "1") {
        session_destroy();
    }
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
        <script type="text/javascript" src="js/custom.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2 class="text-center">Public News Publishing Portal</h2><br>
                    <button class="btn btn-primary pull-right" id="publishNewsBtn">Publish Your News</button>
                </div>
            </div>
            <br>
            <div class="row">
                <h3>Recent News...</h3>
                <div class="col-md-10 col-md-offset-1 alert alert-warning">
                    <table id="newsListTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>News</th>
                                <th>Published By</th>
                                <th>Published On</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="viewNewsModal" role="dialog" aria-labelledby="addNewsModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="model-title"><h4>This article</h4></div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3" id="image"></div>
                            <div class="col-md-9" id="title"></div>
                        </div><br>
                        <div class="row" style="margin:5px">
                            <div class="col-md-12" id="article" style="border: 1px solid black;height:300px; padding:10px"></div>
                        </div>
                        <div class="row well">
                            <div class="col-md-6" id="publishedBy"></div>
                            <div class="col-md-6" id="publishedOn"></div>
                        </div><br>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>
