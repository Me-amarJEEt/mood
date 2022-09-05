<?php

class Account {
    private $con;
    private $errorArray;

    public function __construct($con) {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function login($un, $p1) {
        $p1 = md5($p1);
        $query = mysqli_query($this->con, "SELECT * FROM users WHERE userName = '$un' AND pass = '$p1'");

        if(mysqli_num_rows($query)==1) {
            return true;
        }
        else {
            array_push($this->errorArray, Constants::$loginFail);
        }
    }
    
    public function register($un, $fn, $ln, $em, $em2, $p1, $p2) {
        $this->validateUserName($un);
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmail($em, $em2);
        $this->validatePassword($p1, $p2);

        if(empty($this->errorArray)) {
            return $this->insertUserDetails($un, $fn, $ln, $em, $p1);
        }
        else {
            return false;
        }
    }

    public function getError($error) {
        if(!in_array($error, $this->errorArray)) {
            $error = "";
        }
        return "<span class='errorMessage'>$error</span>";
    }

    private function insertUserDetails($un, $fn, $ln, $em, $p1) {
        $encryptedP1 = md5($p1);
        $profilePic = "assets/images/profile-pics/amarjeet.png";
        $date = date("Y-m-d");

        $result = mysqli_query($this->con, "INSERT INTO users(userName, firstName, lastName, email, pass, signUpDate, profilePic) VALUES ('$un', '$fn', '$ln', '$em', '$encryptedP1', '$date', '$profilePic')");
        return $result;
    }
 
    private function validateUserName($un) {
        if (strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constants::$userNameDoNotMatch);
            return;
        }
        $userNameCheck = mysqli_query($this->con, "SELECT userName FROM users WHERE userName = '$un'");
        if(mysqli_num_rows($userNameCheck) != 0) {
            array_push($this->errorArray, Constants::$userNameTaken);
            return;
        }
    }

    private function validateFirstName($fn){
        if (strlen($fn) > 25 || strlen($fn) < 2 ) {
            array_push($this->errorArray, Constants::$firstNameDoNotMatch);
            return;
        }
    }

    private function validateLastName($ln){
        if (strlen($ln) > 25 || strlen($ln) < 2 ) {
            array_push($this->errorArray, Constants::$lastNameDoNotMatch);
            return;
        }
    }

    private function validateEmail($em, $em2){
        if($em != $em2){
            array_push($this->errorArray, Constants::$emailSame);
            return;
        }


        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray, Constants::$invalidEmail);
            return;
        }

        $emailCheck = mysqli_query($this->con, "SELECT email FROM users WHERE email = '$em'");
        if(mysqli_num_rows($emailCheck) != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
            return;
        }
    }
    private function validatePassword($p1, $p2){
        if($p1 != $p2){
            array_push($this->errorArray, Constants::$passwordDoNotMatch);
            return;
        }

        if(preg_match('/[^A-Za-z0-9]/', $p1)){
            array_push($this->errorArray, Constants::$passwordAlphanumeric);
            return;
        }

        if (strlen($p1) > 25 || strlen($p1) < 5) {
            array_push($this->errorArray, Constants::$passwordchar);
            return;
        }

    }
}
