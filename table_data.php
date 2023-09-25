<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table_dat</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <style>
    table {
        position: absolute;
        transform: translate(-50%, -50%);
        left: 50%;
        top: 50%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 0 40px;
    }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody id="messageTableBody">
        </tbody>
    </table>
    <script>
     $(document).ready(function() {
       
    function refreshTable() {
        $.ajax({
            type: "POST",
            url: "refresh.php",
            dataType: "json",
            success: function(data){
                $('#messageTableBody').empty();
                $.each(data, function(index, value) {
                    $('#messageTableBody').append('<tr><td>' + value
                        .name + '</td><td>' + value.email + '</td><td>' + value
                        .message + '</td><td>' + value.date +
                        '</td></tr>')
                });
            }
            }
        )

        }
        setInterval(refreshTable, 1000);
    });
    </script>

</body>

</html>