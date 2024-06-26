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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased ">
        <div class="">
            <div class="header-bar">
                <img src="{{ asset('images/logo.PNG') }}" class="logo">
            </div>

            <div>
                @if (Auth::check())>
                    <a href="#" onclick="return loadChildView('blogmanager');" class="header-right blog-manage" > Blog Management</a>
                    <a href="#" onclick="return loadChildView('usermanager')" class="header-right users-manage" > Users</a>
                    <a href="{{ route('logout') }}" class="header-right login" > Logout</a>
                @else
                    <a href="{{ route('login') }}" class="header-right login" > Login</a>
                    <a href="{{ route('register') }}" class="header-right register" > Register</a>
                @endif
            </div>

            <h2 class="page-title"><b>Your Source for all the Daily News</b></h2>
        </div>
        <br/>
        <br/>
        <div class="blogs-list">
            
        </div>

        <b>
    </body>
</html>

<script>
    function loadChildView(viewName) {
        $(".blogs-list").load(viewName);
    }

    $(document).ready(function() {
        
        //Request to load all blogs
        $.ajax({
            url: '/getAllBlogs',
            method: 'GET', 
            dataType: 'json', 
            success: function(response) {
                if(response.length > 0) {
                    //Dynamically populate entries
                    for(var i = 0 ; i < response.length ; i++) {
                        var post = `<div class="blog-box" id="blog-` + response[i].id +`">
                            <tr id="blog-title"><b>` + response[i].blog_title + `</b></tr><br/>
                            <tr id="blog-text">` + response[i].blog_text + `</tr>
                        </div>`

                        $(".blogs-list").append(post);
                    }
                } else {
                    $(".page-title").html("No Article Found");
                }
                
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });

        //To load specific blog on clik
        $("body").on("click", "div.blog-box", function(e) {
            $(".blogs-list").load("/details/" + $(this).attr('id').split("-")[1])
        });

    });    
</script>
<style>
.header-bar {
    background-color: #F5F5F5;
    height: 50px !important;
}

.logo {
    width : 14%;
    margin-left:7px;
}

.header-right {
    float:right;
    margin-top : -35px;
}

.register {
    margin-right:8%
}

.login {
    margin-right:3%
}

.page-title {
    text-align : center;
    margin-top: 5%;
    font-size: x-large;
}

.blog-box {
  background-color: #fff;
  border: 3px solid #ddd;
  margin-bottom: 10px;
  padding: 10px;
  width:70%;
  margin-left:17%;
  height: auto
}

.blog-manage {
    margin-right:14%
}

.users-manage {
    margin-right:9%
}

</style>
