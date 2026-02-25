<?php
session_start();

if(isset($_POST['otp'])){
    $user_otp = $_POST['otp'];

    if($user_otp == $_SESSION['otp']){
        $_SESSION['phone'] = $_SESSION['phone'];
        header("Location: dashboard.php");
        exit();
    }else{
        $_SESSION['error'] = "Invalid OTP!";
        header("Location: index.php");
        exit();
    }
}else{
    header("Location: index.php");
    exit();
}
?>
