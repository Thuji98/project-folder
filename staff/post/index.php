<?php
    require $_SERVER['DOCUMENT_ROOT']."/staff/header.php";
?>

<!DOCTYPE html>
<html> 
    <head>
        <title>Posts</title>
        <link rel="stylesheet" href="/staff/css_codes/post.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://kit.fontawesome.com/b17fa3a18c.js" crossorigin="anonymous"></script>
        <script src="/govpost/govpost.js"></script>

        <script src='/common/autocomplete/auto_complete.js'></script>
        <link rel='stylesheet' type='text/css' href='/common/autocomplete/auto.css'>
        <!-- <script src="/common/post/post.js"></script>
        <script src='/common/post/create_post.js'></script> -->

    </head>
    <body>
    
<script>
    btnPress(4);
</script>

    <div id="post_title">
        Posts
    </div>

    <div id='new_post'>
        <form method=post action='/common/documents/govpost.php' enctype="multipart/form-data" autocomplete="off">
            <div class='post_heading'> heading:</div>
            <input type='text' name='heading' id='heading'>
            <div class='post_content'> Content:</div>
            <textarea id='post_text_area' name='content' rows=3 cols=5></textarea>
            <div class='post_content'> Event:</div>
            <div style="position: relative;margin:8px;font-size:15px;">
                <input type="hidden" name="event">
                <input id='tag_input_field' type='text' placeholder='Tag an event' spellcheck='false' aria-autocomplete='none'>
            </div>
            <div id='image_container'>
                <img id='preview' />
            </div>
            <button type='submit' id='submit' name='post' value='post'>POST</button>
            <div for=upload_file id=upload_file class="post_btn" onclick=upload()>Upload photo</div>
            <input type=file name=upload_file accept="image/*" id=hidden_upload_file style="display:none" onchange="loadFile(event)">
            <!--div id=tag_button class="post_btn" onclick='add_tag()'><i class="fa fa-plus-square-o"></i> Tag</div-->
        </form>
    </div>



    <div id="content">
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT']."/staff/footer.php" ?>
    <script>
        autocomplete_ready(document.getElementById("tag_input_field"), 'events', 'ready', 'set_id');
        var post = new GovPost('staff');
        post.get_post();

        function upload(){
            document.getElementById('hidden_upload_file').click();
        }

        var loadFile = function(event){
            var output = document.getElementById('preview');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function(){
                URL.revokeObjectURL(output.src)
                setHeight();
            }
        };
        function setHeight(img){
            
            document.getElementById('image_container').style.margin = "10px";
            //document.getElementById('image_container').style.height = img.height;
        }
    </script>