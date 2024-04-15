<?php

include "dbconn_login.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Enter Your Phone Number To Reset Password</h5>
                    <form action="actionForgotPassword.php" method="POST">
                        <div class="mb-3">
                            <label for="phoneNum" class="form-label">Phone Number:</label>
                            <input type="number" name="phoneNum" class="form-control" id="phoneNum" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="passwordResetLink">Send Password Reset</button>
						<a href="loginStaff.php" class="btn btn-primary">Back to Login Page</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>