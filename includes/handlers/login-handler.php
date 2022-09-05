<?php 
if (isset($_POST['loginButton'])) { 
    //Login button was pressed 
    $loginUsername = sanitizePassword($_POST['loginUsername']);
    $loginPassword = sanitizePassword($_POST['loginPassword']);

    //login function 
    $result = $account->login($loginUsername, $loginPassword);

    if($result == true) {
        $_SESSION['userLoggedIn'] = $loginUsername;
        header('Location:index.php');
    }

}

?>