<?php
require_once 'inc/Gestock.php';

$username = filter_input(INPUT_POST, "username");
$email = filter_input(INPUT_POST, "userEmail", FILTER_VALIDATE_EMAIL);
$pwd = filter_input(INPUT_POST, "userPassword");
$pwdConfirm = filter_input(INPUT_POST, "userPasswordConfirm");

if($username && $email && $pwd && $pwdConfirm)  // If the user correctly filled all fields.
{
    echo '1';
    if(strlen($pwd) >= 8)   // If the password is at least 8 char long.
    {
        echo '2';
        if($pwd == $pwdConfirm) // If the passwords match.
        {
            echo '3';
            $result = Gestock::getInstance()->insertUser($username, $email, sha1($pwd));
            if(isset($result->errorInfo))   // If there is a SQL error.
            {
                echo "lele";
                print_r($result->errorInfo);
                echo $result->errorInfo;
                if($result->errorInfo[0] == 23000)  // Username or Email already in use.
                    header("Location: login.php?msg=" .  ucfirst(explode("_", explode("'", $result->errorInfo[2])[3])[0]) . ' aleady in use.');
            }
            else    // If the query executed without any problem.
                header("Location: login.php?msg=User added !");
        }
        else    // If the passwords don't match.
            header("Location: login.php?msg=Passwords do not match !");
    }
    else    // If the password isn't long enough.
        header("Location: login.php?msg=Password not long enough !");
}
else    // If all fields weren't filled.
    header("Location: login.php?msg=Please fill all fields !");


?>