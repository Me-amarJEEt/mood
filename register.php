<?php
include("includes/config.php");

include("includes/classes/Account.php");
include("includes/classes/Constants.php");
$account = new Account($con);


include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php");

function getInputValue($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="assets/js/register.js"></script>
    <link rel="stylesheet" href="assets/css/register.css">
    <title>Welcome to MOOD</title>
</head>

<body>
    <?php 

        if(isset($_POST['registerButton'])) {
            echo '<script>
                    $(document).ready(function() {
                        $("#loginForm").hide();
                        $("#registerForm").show();
                    });
                </script>';
        }
        else {
            echo '<script>
                    $(document).ready(function() {
                        $("#loginForm").show();
                        $("#registerForm").hide();
                    });
                </script>';
        }

    ?>

    <div id="background">
        <div id="loginContainer"> 
            <div id="inputContainer">
                <form id="loginForm" action="register.php" method="POST">
                    <h2>Log into your account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$loginFail); ?>
                        <label for="loginUsername">Username</label>
                        <input id="loginUsername" name="loginUsername" required placeholder="Enter username" autocomplete="off" type="text">
                    </p>
                    <p>
                        <label for="loginPassword">Password</label>
                        <input id="loginPassword" name="loginPassword" required placeholder="Enter Password" type="password">
                    </p>
                    <button id="loginButton" name="loginButton" type="submit">LOG IN</button>
                    <div class="hasAccount">
                        <span id="hideLogin">Don't have a account yet? Signup here.</span>
                    </div>
                </form>

                <form id="registerForm" action="register.php" method="POST">
                    <h2>Create a new account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$userNameDoNotMatch); ?>
                        <?php echo $account->getError(Constants::$userNameTaken); ?>

                        <label for="username">Username</label>
                        <input id="username" name="username" autocomplete="off" required placeholder="Enter username" value="<?php getInputValue('username') ?>" type="text">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$firstNameDoNotMatch); ?>
                        <label for="firstName">First Name</label>
                        <input id="firstName" name="firstName" autocomplete="off" required placeholder="Enter First Name" value="<?php getInputValue('firstName') ?>" type="text">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$lastNameDoNotMatch); ?>
                        <label for="lastName">Last Name</label>
                        <input id="lastName" name="lastName" autocomplete="off" required placeholder="Enter Last Name" value="<?php getInputValue('lastName') ?>" type="text">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$emailSame); ?>
                        <?php echo $account->getError(Constants::$invalidEmail); ?>
                        <?php echo $account->getError(Constants::$emailTaken); ?>
                        <label for="email">Email</label>
                        <input id="email" name="email" autocomplete="off" required placeholder="Enter email" value="<?php getInputValue('email') ?>" type="email">
                    </p>
                    <p>
                        <label for="email2">Confirm Email</label>
                        <input id="email2" name="email2"  autocomplete="off" required placeholder="Confirm Email" value="<?php getInputValue('email2') ?>" type="email">
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$passwordAlphanumeric); ?>
                        <?php echo $account->getError(Constants::$passwordchar); ?>
                        <?php echo $account->getError(Constants::$passwordDoNotMatch); ?>
                        <label for="password">Enter Password</label>
                        <input id="password" name="password" required placeholder="Enter Password" type="password">
                    </p>
                    <p>
                        <label for="password2">Confirm Password</label>
                        <input id="password2" name="password2" required placeholder="Confirm Password" type="password">
                    </p>

                    <button id="registerButton" name="registerButton" type="submit">SIGN UP</button>
                    <div class="hasAccount">
                        <span id="hideRegister">Already have a account? Login</span> here.</span>
                    </div>
                </form>
            </div>
            <div id="loginText">
                <h1>Get great music on MOOD</h1>
                <h2>Listen to all songs for FREE 🎧</h2>
            </div>
        </div>
    </div>
</body>

</html>