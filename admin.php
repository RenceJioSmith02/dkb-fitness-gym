<?php
require "db/db.php";
$mydb = new myDB();

include_once 'partials/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gym MS | Admin</title>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/sweetalert2.min.js"></script>

    <link rel="icon" href="assets/images/system_image/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

    <div class="container flex flex-col jus-start al-center">
        <!-- Header -->
        <?php include_once 'partials/header.php' ?>

        <div class="content landingpage flex jus-center al-center" style="height: 100%;">
            <div class="form-container flex flex-col al-center jus-center">
                <h2>Reset Password</h2>
                <br>
                <form action="db/request.php" method="post" class="reset-form-group flex flex-col jus-center al-start">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username">
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="********">
                    </div>
                    <div class="input-group">
                        <label for="confirm">Confirm Password</label>
                        <input type="password" id="confirm" placeholder="********">
                    </div>
                    <br>
                    <button type="submit" class="btn-primary">Reset</button>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        // =============================
        // Process of reset paswword
        // =============================
        $(document).on("submit", ".reset-form-group", function (e) {
            e.preventDefault();

            let username = $("#username").val().trim();
            let password = $("#password").val();
            let confirm = $("#confirm").val();

            if (!username || !password || !confirm) {
                return alert("Please fill out all fields.");
            }

            if (password !== confirm) {
                return alert("Passwords do not match.");
            }

            $.ajax({
                url: "db/request.php",
                type: "POST",
                data: {
                    action: "reset_password",
                    username: username,
                    password: password
                },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message
                        }).then(() => {
                            window.location.href = "index.php"; // redirect to login
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("Something went wrong, please try again.");
                }
            });
        });


    </script>
</body>

</html>