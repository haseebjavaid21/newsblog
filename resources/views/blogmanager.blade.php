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

        <div class="blogs-create">
        <h1 class="page-title-create">Lets Create a new Blog</h1><br/>

        <form id="ajax-form" enctype="multipart/form-data">

            <div id="success-alert" class="alert alert-primary" role="alert">Data Saved Successfully</div>

            <label for="blog_title">Title:</label>
            <input type="text" id="blog_title" name="blog_title"><br/><br/>

            <label for="blog_cotent">Content:</label>
            <textarea id="blog_content" name="blog_content" rows="4" col="10"></textarea><br/><br/>

            <label for="blog_image">Upload Image:</label>
            <input type="file" id="blog_image" name="blog_image" accept="image/*"><br/><br/>

            <button type="submit" id="save_blog" class="btn btn-secondary">Save</button>
            <button type="button" id="clear_fields" class="btn btn-secondary">Clear</button>
        </form>
        </div>

    </body>
</html>

<script>
    $(document).ready(function() {
        //Hide Success Alert
        $('#success-alert').hide();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //From Submit Handler
        $('#ajax-form').on('submit', function (e) {
            e.preventDefault();

            var formData = {};
            formData['title'] = $('#blog_title').val();
            formData['content'] = $('#blog_content').val();
            //formData['file'] = $('#blog_image').val();

            //Request to send new blog post data to backend
            $.ajax({
                url: '/createNewBlog'   ,
                method: 'POST', 
                data: formData,
                success: function(response) {
                    // Handle the response
                    if(response == 'success') {
                        $('#success-alert').show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                    console.error('Error:', textStatus, errorThrown);
                }
            });
        });

        //Clear all fields if user clicks on clear button
        $("#clear_fields").click(function() {
            $('#blog_title').val('');
            $('#blog_content').val('');
            $('#blog_image').val(null);
            $('#success-alert').hide();
        });
    });    
</script>
<style>

.page-title-create {
    text-align : center;
    margin-top: 1%;
    font-size: x-large;
}

.blog-details-box {
  background-color: #fff;
  margin-bottom: 10px;
  padding: 10px;
  width:70%;
  margin-left:17%;
}

form {
    width: 50%;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="file"] {
    margin-bottom: 10px;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    border: none;
    border-radius: 4px;
    color: white;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}
</style>
