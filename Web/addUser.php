<?php

require_once 'inc/Gestock.php';

$username = filter_input(INPUT_POST, "username");
$email = filter_input(INPUT_POST, "userEmail", FILTER_VALIDATE_EMAIL);
$pwd = filter_input(INPUT_POST, "userPassword");
$pwdConfirm = filter_input(INPUT_POST, "userPasswordConfirm");

if($username && $email && $pwd && $pwdConfirm)
{
    echo '1';
    if(strlen($pwd) >= 8)
    {
        echo '2';
        if($pwd == $pwdConfirm)
        {
            echo '3';
            $result = Gestock::getInstance()->insertUser($username, $email, sha1($pwd));
            if(isset($result->errorInfo))
            {
                echo "lele";
                print_r($result->errorInfo);
                echo $result->errorInfo;
                if($result->errorInfo[0] == 23000)  // Username or Email already in use.
                    header("Location: login.php?msg=" .  ucfirst(explode("_", explode("'", $result->errorInfo[2])[3])[0]) . ' aleady in use.');
            }
            else
                header("Location: login.php?msg=User added !");
        }
        else
            header("Location: login.php?msg=Passwords do not match !");
    }
    else
        header("Location: login.php?msg=Password not long enough !");
}
else
    header("Location: login.php?msg=Please fill all fields !");


?>