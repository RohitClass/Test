<?php
include "config.php";

$run_query = "SELECT * FROM login2";
$data= mysqli_query($conn,$run_query) or die("select Faild");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>



    <div class="container">
        <!-- <button class="open-button" onclick="openForm()">Add Data</button> -->
        <div class="form-popup" id="myForm">
            <section class="container">
                <div class="login-container">
                    <div class="circle circle-one"></div>
                    <div class="form-container">
                        <h1 class="opacity">Student Data</h1>
                        <form id="form-1" method="post">
                            <input type="text" placeholder="NAME" name="name" required />
                            <input type="text" placeholder="COLLEGE" name="college" required />
                            <input type="text" placeholder="CITY" name="city" required />
                            <input type="text" placeholder="STATE" name="state" required />
                            <button class="opacity" type="submit" id="button" name="button">SUBMIT</button>
                        </form>

                    </div>
                    <div class="circle circle-two"></div>
                </div>
                <div class="theme-btn-container"></div>
            </section>
        </div>

        <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
        </script>

        <h2 id="id"></h2>

        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Name</th>
                    <th scope="col">College</th>
                    <th scope="col">city</th>
                    <th scope="col">state</th>
                    <th scope="col">Delete</th>
                    <th scope="col">Edit</th>
                </tr>
            </thead>
            <tbody id="table_body">
                <?php if(mysqli_num_rows($data)>0){ ?>
                <?php while($row = mysqli_fetch_assoc($data)){ ?>
                <tr>
                    <td scope="row" id="id"><?php  echo $row["id"]; ?></td>
                    <td id="name"><?php  echo $row["Name"]; ?></td>
                    <td id="college"><?php  echo $row["college"]; ?></td>
                    <td id="city"><?php  echo $row["city"]; ?></td>
                    <td id="state"><?php  echo $row["state"]; ?></td>
                    <td><button class="delete_button" data-id="<?= $row["id"] ?>">Delete</button></td>
                    <td><button type="button" class="btn btn-primary edit_button" data-toggle="modal"
                            data-target="#exampleModal" data-id="<?= $row["id"] ?>">Edit</button></td>


                </tr>
                <?php  } ?>
                <?php }else{ ?>
                <style>
                .table.table-dark {
                    display: none;
                }
                </style>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">


                <section id="hide-container" class="container">
                    <div class="login-container">
                        <div class="circle circle-one"></div>
                        <div class="form-container">
                            <h1 class="opacity">Student Data</h1>
                            <form id="form-2" method="post">
                                <input type="text" Value="" name="name" id="updateName" />
                                <input type="hidden" Value="" name="id" id="updatid" />
                                <input type="text" Value="" name="college" id="updatecollege" />
                                <input type="text" Value="" name="city" id="updatecity" />
                                <input type="text" Value="" name="state" id="updatestate" />
                                <button class="opacity" type="submit" id="Update_button" name="button">SUBMIT</button>
                            </form>

                        </div>
                        <div class="circle circle-two"></div>
                    </div>
                    <div class="theme-btn-container"></div>
                </section>
            </div>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        $("#form-1").submit(function(event) {
            event.preventDefault();
            var data = $('#form-1').serialize();
            // console.log(data,"===============================");
            $.ajax({
                type: "POST",
                url: "submit.php",
                dataType: "json",
                data: data,
            }).done(function(data) {
                if (data != '') {
                    $('.table.table-dark').show()
                } else {
                    $('.table.table-dark').hide()
                }
                $('#form-1')[0].reset();

                $('#table_body').empty();

                $.each(data, function(index, value) {
                    $('#table_body').append('<tr><td>' + value.id + '</td><td>' + value
                        .Name + '</td><td>' + value.college + '</td><td>' + value
                        .city + '</td><td>' + value.state +
                        '</td><td><button class="delete_button" data-id="' + value
                        .id + '">Delete</button></td><td><button data-id="' + value
                        .id +
                        '" type="button" class="btn btn-primary edit_button" data-toggle="modal" data-target="#exampleModal">Edit</button></td></tr>'
                        )
                });

            });
        });
    });


    $(document).ready(function() {
        $("div").delegate(".delete_button", "click", function(event) {
            event.preventDefault();
            var data_id = $(this).data('id');
            // console.log(data_id,"--------------------------------");
            $.ajax({
                type: "POST",
                url: "delete.php",
                dataType: "json",
                data: {
                    id: data_id
                },
            }).done(function(data) {
                if (data != '') {
                    $('.table.table-dark').show()
                } else {
                    $('.table.table-dark').hide()
                }
                $('form')[0].reset();

                $('#table_body').empty();

                $.each(data, function(index, value) {
                    $('#table_body').append('<tr><td>' + value.id + '</td><td>' + value
                        .Name + '</td><td>' + value.college + '</td><td>' + value
                        .city + '</td><td>' + value.state +
                        '</td><td><button class="delete_button" data-id="' + value
                        .id + '">Delete</button></td><td><button data-id="' + value
                        .id +
                        '" type="button" class="btn btn-primary edit_button" data-toggle="modal" data-target="#exampleModal">Edit</button></td></tr>'
                        )
                });
            });
        });
    });


    

    $(document).ready(function() {
        $("div").delegate(".edit_button", "click", function(event) {
            event.preventDefault();
            var data_id = $(this).data('id');
            // console.log(data_id,"--------------------------------");
            $.ajax({
                type: "POST",
                url: "value.php",
                dataType: "json",
                data: {
                    id: data_id
                },
            }).done(function(data) {
                $('#form-2')[0].reset();

                // $('#table_body').empty();

                $.each(data, function(index, value) {
                    $('#updateName').val(value.Name);
                    $('#updatecollege').val(value.college);
                    $('#updatecity').val(value.city);
                    $('#updatestate').val(value.state);
                    $("#updatid").val(value.id);

                });
            });
        });
    });



    // -----update------

    $(document).ready(function() {
        $("div").delegate("#Update_button", "click", function(event) {
            event.preventDefault();
            var data = $('#form-2').serialize();
            // console.log(data_id,"--------------------------------");
            $.ajax({
                type: "POST",
                url: "edit.php",
                dataType: "json",
                data: data,
            }).done(function(data) {
                $('#form-2')[0].reset();

                $('#table_body').empty();

                $.each(data, function(index, value) {
                    $('#table_body').append('<tr><td>' + value.id + '</td><td>' + value
                        .Name + '</td><td>' + value.college + '</td><td>' + value
                        .city + '</td><td>' + value.state +
                        '</td><td><button class="delete_button" data-id="' + value
                        .id + '">Delete</button></td><td><button data-id="' + value
                        .id +
                        '" type="button" class="btn btn-primary edit_button" data-toggle="modal" data-target="#exampleModal">Edit</button></td></tr>'
                        )
                    if (data = value) {
                        $('#hide-container').hide()
                    } else {
                        $('hide-container').show()
                    }
                });
            });
        });
    });
    </script>

</body>

</html>