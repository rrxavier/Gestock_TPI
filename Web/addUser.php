<?php
require_once 'inc/Gestock.php';

$username = filter_input(INPUT_POST, "username");
$email = filter_input(INPUT_POST, "userEmail", FILTER_VALIDATE_EMAIL);
$pwd = filter_input(INPUT_POST, "userPassword");
$pwdConfirm = filter_input(INPUT_POST, "userPasswordConfirm");

print_r($_POST);

if($username && $email && $pwd && $pwdConfirm)  // If the user correctly filled all fields.
{
    if(strlen($pwd) >= 8)   // If the password is at least 8 char long.
    {
        if($pwd == $pwdConfirm) // If the passwords match.
        {
            $result = Gestock::getInstance()->insertUser($username, $email, sha1($pwd));
            if(is_a($result, 'bool'))   // If the function returned a bool.
            {
                if($result) // If the SQL query correctly executed.
                    header("Location: login.php?msg=User added !");
                else        // If there is an error.
                    header("Location: login.php?msg=Error, please try again.");
            }
            else
                header("Location: login.php?msg=" . ucfirst($result) . ' aleady in use.');
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