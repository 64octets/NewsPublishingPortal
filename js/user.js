
$(document).ready(function() {

    
    //Execute this ajax call on body load
    $.ajax({
        url: "business.php",
        method: "post",
        dataType: "json",
        data: {"action":"getUserNewsList","user":"currentUser"},
        success: function(output) {
            console.log(output);
            if (output[0] === 1){
                console.log(output[1].length);
                if (output[1].length === 0){
                    $("#newsListTable tbody").empty().append("<tr><td colspan='4'>No news published yet</td></tr>");
                } else {
                    $.each(output[1], function(i,obj) {
                        $("#newsListTable tbody").append("<tr><td width='10%'><img src='"+obj['image_path']+"' class='img-responsive' ></td><td width='70%'><h4><u>"+obj['title']+"</u></h4>"+obj['news_content']+"</td><td width='15%'>"+obj['created_dt']+"</td><td width='5%'><button class='btn btn-danger' id='deleteNews' >X</button> </td></tr>");
                    });
                }
            } else {
                alert(output[1]);
            }
        }, error: function(error) {
            alert("error");
            console.log("error on body load");
            console.log(error);
        }
    });
    
    $("#addNewsForm").on("submit", function() {
        console.log("inside addNewsForm")
        var data = new FormData(this);
        data.append("action","addNews");
        data.append("image","image");
        
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
                    alert(output[1]);
                    window.location.reload();
                } else {
                    $("#modalMsg").html(output[1]);
                }
            }, error: function(error) {
                alert("error");
                console.log("error");
                console.log(error);
            }
        });
    });
});