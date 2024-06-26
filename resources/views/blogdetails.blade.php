<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>News Blog</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased ">

        <button type="button" id="loadBlogs" class="btn btn-secondary loadBlogs">Back</button><br/>
        <div class="blogs-details"></div>

        @if (Auth::check())
        <div class="blogs-comments">
            <h1><b>Comments Section:</b></h1>
            <div class="comment-list">
                <table id="items-table">
                    <tbody>
                    </tbody>
                    <br/>
                </table>
                <input type="text" id="new-comment" name="new-comment" placeholder="Add Comment here" class="new-comment">
                <button type="button" id="add-comment" class="btn btn-secondary add-comment">Post</button><br/>
            </div>
            <div class="no-comment"></div>
        </div>
        @endif

        <b>
    </body>
</html>

<script>
    var id = @json($id);

    //Create global function for ajax request to fetch blog details and comments
    function loadBlogAndComments() {
        $('.blog-details-box').remove();
        $.ajax({
            url: '/getBlogDetails/' + id,
            method: 'GET', 
            dataType: 'json', 
            success: function(response) {
                
                //Create html and append to the div
                if(response) {
                        var post = `<div class="blog-details-box">
                            <tr id="blog-title"><b>Title: </b>` + response.blog_title + `</tr><br/>
                            <tr id="blog-text"><b>Content: </b>` + response.blog_text + `</tr><br/>
                            <tr id="blog-author"><b>Author: </b>` + response.author_id + `</tr><br/>
                        </div>`

                        $(".blogs-details").append(post);

                        //Iterate comments and create rows with all comments
                        var tableBody = $('#items-table tbody');
                        tableBody.empty();
                        response.comments.forEach(function (item) {
                            var row = '<tr>' +
                                '<td><b>'+ item.comment_by + '</b>: ' + item.comment_text + '</td></tr>';
                            tableBody.append(row);
                        });

                } else {
                    $(".page-title").html("Details Not Found");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle any errors
                console.error('Error:', textStatus, errorThrown);
            }
        });
    }
    
    $(document).ready(function() {
        //Call global function to load blog details on page load
        loadBlogAndComments();

        //Request to handle new comment
        $("#add-comment").click(function() {
            console.log($("#new-comment").val());

            var formData = {};
            formData['article_id'] =  id;
            formData['comment'] = $("#new-comment").val();

            $.ajax({
                url: '/postComment',
                method: 'POST', 
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle the response
                    if(response == 'success') {
                        //Clear comment field and reload data
                        $("#new-comment").val('');
                        loadBlogAndComments();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                    console.error('Error:', textStatus, errorThrown);
                }
            });
        });

        //Back button to load the blog posts view
        $('#loadBlogs').click(function() {
            window.location.reload();
        })
    });    
</script>
<style>

.page-title {
    text-align : center;
    margin-top: 5%;
    font-size: x-large;
}

.blog-details-box {
  background-color: #fff;
  margin-bottom: 10px;
  padding: 10px;
  width:70%;
  margin-left:17%;
}   

.blogs-comments {
    background-color: #fff;
    margin-bottom: 10px;
    padding: 10px;
    width:70%;
    margin-left:17%;
}

.new-comment {
    width: 50%;
    height: 35px;
}

.add-comment {
    height: 36px;
    margin-bottom: 3px;
}

.loadBlogs {
    margin-left:17.5%;
    margin-bottom: 15px;
}
</style>
