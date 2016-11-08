$(document).ready(function(){

    //Execute this ajax call on body load
    $.ajax({
        url: "business.php",
        method: "post",
        dataType: "json",
        data: {"action":"getUserNewsList"},
        success: function(output) {
            console.log(output);
            if (output[0] === 1){
                console.log(output[1].length);
                if (output[1].length === 0){
                    $("#newsListTable tbody").empty().append("<tr><td colspan='5'>No news published yet</td></tr>");
                } else {
                    var i = 0;
                    $.each(output[1], function(i,obj) {
                        $("#newsListTable tbody").append("<tr><td style='display:none'>"+obj['news_id']+"</td><td width='10%' height='10'><img src='"+obj['image_path']+"' class='img-responsive' ></td><td width='60%'><h4><u>"+obj['title']+"</u></h4>"+obj['news_content'].slice(0,100)+"...<button class='btn btn-warning pull-right' id='readMoreBtn' data-toggle='modal' data-target='#viewNewsModal'>Read More</button></td><td width='5%'>"+obj['name_txt']+"</td><td width='10%'>"+obj['created_dt']+"</td></tr>");
                        i++; if (i >=10) return false;
                    });
                }
            } else {
                alert(output[1]);
            }
        }, error: function(error) {
            alert("error");
            console.log(error);
        }
    });
    
    
    $("body").on("click","#publishNewsBtn",function(){
        
        $.ajax({
            url: "business.php",
            method: "post",
            dataType: "json",
            data: {"action": "publishNews"},
            success: function(output){
                console.log(output);
                window.location.href=output;
            }, error: function(error) {
                alert("Error");
                console.log(error);
            }
        });
    });
    
    $("#signupForm").on("submit",function(e){
        e.preventDefault();
        $("#signup_error").html("");
        var data = new FormData(this);
        data.append("action","signup");
        
        $.ajax({
            url: "business.php",
            method: "post",
            dataType: "json",
            data: data,
            processData: false,
            contentType: false,
            success: function(output) {
                console.log(output);
                if (output[0] === 1){
                    alert("Please check you email and activate your account.");
                    window.location.href="index.php";
                } else {
                    $("#signup_error").html(output[1]);
                }
            }, error: function(error) {
                alert("Error");
                console.log(error);
            }
        });
    });
    
    
    $("#loginForm").on("submit",function(e){
        e.preventDefault();
        $("#login_error").html("");
        var data = new FormData(this);
        data.append("action","login");
        
        $.ajax({
            url: "business.php",
            method: "post",
            dataType: "json",
            data: data,
            processData: false,
            contentType: false,
            success: function(output) {
                console.log(output);
                if (output[0] === 1){
                    window.location.href="userHome.php";
                } else {
                    $("#login_error").html(output[1]);
                }
            }, error: function(error) {
                alert("Error");
                console.log(error);
            }
        });
    });
    
    
    $("#change_password_form").on("submit", function(e){
        e.preventDefault();
        $("#change_password_error").html("");
        var data = new FormData(this);
        data.append("action","changePassword");
        
        $.ajax({
            url: "business.php",
            method: "post",
            dataType: "json",
            data: data,
            processData: false,
            contentType: false,
            success: function(output) {
                console.log(output);
                if (output[0] === 1){
                    alert("Password updated.  Please login with your credentials");
                    window.location.href="login.php";
                } else {
                    $("#change_password_error").html(output[1]);
                }
            }, error: function(error) {
                alert("Error");
                console.log(error);
            }
        });
    });
    
    
    $(document).on('click',"td #readMoreBtn",function(e) {
        e.preventDefault();
        var id = $(this).closest('tr').find("td:eq(0)").text();
        console.log($(this).closest('tr').text());
        console.log("news id is "+id);
        
        $.ajax({
            url: "business.php",
            method: "post",
            dataType: "json",
            data: {"action":"viewNews","id":id},
            success: function(output) {
                console.log(output);
                if (output[0] === 1){
                    $("#image").html("<img src='"+output[1][0]['image_path']+"' class='img-thumbnail' width='200' height='200'>");
                    $("#title").html("<h2>"+output[1][0]['title']+"</h2>");
                    $("#article").html(output[1][0]['news_content']);
                    $("#publishedBy").html("<p class='pull-left'>Reported by: "+output[1][0]['name_txt']+"</p>");
                    $("#publishedOn").html("<p class='pull-right'>Reported On: "+output[1][0]['created_dt']+"</p>");
                    $("#viewNewsModal").modal('show');
                } 
            }, error: function(error) {
                alert("Error");
                console.log(error);
            }  
        })
    });

});