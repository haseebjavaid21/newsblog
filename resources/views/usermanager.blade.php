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
            <h1 class="page-title-create">User Management</h1><br/>

            <div class="user-section page-title-create"></div>

            <table id="items-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically added here -->
                </tbody>
            </table>
            <br/>
            <br/>
            <form id="ajax-form" enctype="multipart/form-data">

                <div id="success-alert" class="alert alert-primary" role="alert">Data Saved Successfully</div>
                <!-- Text field -->
                <label for="name">Name:</label>
                <input type="text" id="name" name="name"><br/><br/>

                <!-- Text area -->
                <label for="email">Email:</label>
                <input type="text" id="email" name="email"><br/><br/>

                <!-- Image file input -->
                <!-- <label for="password">Password:</label>
                <input type="text" id="password" name="passowrd"><br/><br/> -->

                <label>Role:</label>
                <label for="option1"><input type="checkbox" id="option1" name="role" value="comments"> Comments Only</label>
                <label for="option2"><input type="checkbox" id="option2" name="role" value="fullaccess"> Full Accesss</label><br/>

                    <!-- Submit button -->
                <button type="submit" id="save_data" class="btn btn-secondary">Save</button>
                <button type="button" id="cancel" class="btn btn-secondary">Cancel</button>
            </form>
        </div>

    </body>
</html>

<script>
    var selectedUserId = null;

    //Global function to load all data
    function loadAllData() {
        $.ajax({
            url: '/getAllUsers',
            method: 'GET', 
            success: function(response) {

                if(response.length > 0) {
                    var tableBody = $('#items-table tbody');
                    tableBody.empty();
                    $("#items-table").show();
                    
                    //Iterate response from backend and create table rows
                    response.forEach(function (item) {
                        var row = '<tr>' +
                            '<td>' + item.name + '</td>' +
                            '<td>' + item.email + '</td>' +
                            '<td>' + item.role + '</td>' +
                            '<td><button class="edit-button" name="user-edit" id="userbtn-' + item.id + '">Edit</button></td>' +
                            '</tr>';
                        tableBody.append(row);
                    });

                    //Bind function to row buttons
                    $('button[name="user-edit"]').click(function() {
                        var userId = $(this).attr('id').split('-')[1];

                        selectedUserId = userId;

                        //Reuqest to get the data of the selected user row
                        $.ajax({
                            url: '/getUser/' + userId,
                            method: 'GET', 
                            success: function(response) {
                                // Handle the response
                                response = JSON.parse(response);
                                $('#name').val(response.name);
                                $('#email').val(response.email);

                                if (response.role == 'comments') {
                                    $('#option1').prop('checked', true);
                                } else if (response.role == 'fullaccess') {
                                    $('#option2').prop('checked',true);
                                }
                                
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                // Handle any errors
                                console.error('Error:', textStatus, errorThrown);
                            }
                        });

                        $('#ajax-form').show();
                    });
                }  else {
                    $("#items-table").hide();
                    $(".user-section").html("No Data Found");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle any errors
                console.error('Error:', textStatus, errorThrown);
            }
        });
    }
    $(document).ready(function() {
        
        $('#success-alert').hide();
        $('#ajax-form').hide();
        $("#items-table").hide();

        //Loading all data on page reload
        loadAllData();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Form submission handler
        $('#ajax-form').on('submit', function (e) {
            e.preventDefault();

            var formData = {};
            formData['name'] = $('#name').val();
            formData['email'] = $('#email').val();
            formData['password'] = $('#password').val();
            formData['role'] = $("[name='role']:checked").val();

            //Request to send user data for update
            $.ajax({
                url: '/updateUser/' + selectedUserId,
                method: 'POST', 
                data: formData,
                success: function(response) {
                    if(response == 'success') {
                        $('#success-alert').show();
                    }
                    $('#ajax-form').hide();
                    loadAllData();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                    console.error('Error:', textStatus, errorThrown);
                }
            });
        });

        $("#cancel").click(function() {
            $('#ajax-form').hide();
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

table {
    width: 80%;
    margin: 0 auto;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    border: 2px solid #ddd;
    text-align: center;
}

th {
    background-color: #f2f2f2;
}

.edit-button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.edit-button:hover {
    background-color: #45a049;
}

</style>
