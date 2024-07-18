<?php
session_start(); // Start session to persist user login status

include_once 'includes/conn.php';

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            // Check if the user is active
            if ($row["status"] === "Active") {
                // Verify the password (assuming it's not hashed yet)
                if (password_verify($password, $row['hashed_password']) || $password == $row['hashed_password']) {
                    // Password is correct, set session variables
                    $_SESSION["user_id"] = $row["user_id"];
                    $_SESSION["email"] = $row["email"];
                    $_SESSION["name"] = $row["name"];
                    $_SESSION["department"] = $row["department"];

                    // Redirect users based on their departments
                    switch ($row["department"]) {
                        case 'Data clerk':
                            header("Location: Dataclerk/home.php");
                            break;
                        case 'Doctor':
                            header("Location: User/Dashboard.php");
                            break;
                        case 'Emergency_dept':
                            header("Location: Emergency/emergency.php");
                            break;
                        case 'Nurse':
                            header("Location: User/Dashboard.php");
                            break;
                        case 'Admin':
                            header("Location: Admin/Admin-panel.php");
                            break;
                        case 'Laboratory':
                            header("Location: lab/lab dashboard.php");
                            break;
                        case 'Xray':
                            header("Location: xray/xray_dashboard.php");
                            break;
                        case 'Pharmacy':
                            header("Location: pharmacy/pharmacy dashboard.php");
                            break;
                        case 'Account':
                            header("Location: accounts/payment.php");
                            break;
                        default:
                            header("Location: Auth.php");
                    }
                    exit();
                } else {
                    $error_message = "Invalid password";
                }
            } else {
                // User is inactive
                $error_message = "User is no longer active";
            }
        } else {
            $error_message = "User not found";
        }
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>265 OutPatient</title>
    <link rel="stylesheet" type="text/css" href="style/login.css">
</head>

<body style="width: 100%; height:auto;">
    <div class="overlay">
        <form action="Auth.php" method="post">
            <div class="imgcontainer">
                <img src="Assets/cardiogram.png" alt="Avatar" class="avatar">
                <p class="imgwords" style="font-size: 25px;"><span style="font-style: italic; color: #f0a732">265</span>OPMS</p>
            </div>

            <div class="container">
                <label for="uname" class="form-words"><b>Email</b></label>
                <input type="text" placeholder="Enter Your Email Address" name="email" required>

                <label for="psw" class="form-words"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <!-- Display error message if exists -->
                <?php if (!empty($error_message)) : ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <button type="submit" name="submit">Login</button>
                <a href="forgetpassword.php">
                    <p class="form-words">Forgot Password?</p>
                </a>
            </div>
        </form>
    </div>
</body>

</html>
