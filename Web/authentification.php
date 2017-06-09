<?php
require_once 'inc/Gestock.php';
session_start();

$identifier = filter_input(INPUT_POST, "identifier", FILTER_VALIDATE_EMAIL);
$pwd = filter_input(INPUT_POST, "password");

if($pwd)    // If the password is set.
{
    if(!$identifier)   // If the identifier is an username.
    {
        $identifier = filter_input(INPUT_POST, "identifier");
        if($identifier)     // If the identifier is set.
        {
            $result = Gestock::getInstance()->authentifyByUsername($identifier, sha1($pwd));
            if(!(is_a($result, 'PDOException')))    // If there's no PDOException.
            {
                if(isset($result[0]))   // If the combination exists.
                {
                    $_SESSION['user'] = $result[0];
                    print_r($result[0]);
                    header('Location: account.php');
                }
                else                    // If the combination doesn't exist.
                    header("Location: login.php?msg=Wrong username/password combination.");
            }
            else    // If a PDOException has been thrown.
                header("Location: login.php?msg=Error, please try again.");
        }
    }
    else               // If the identifier is an email.
    {
        $result = Gestock::getInstance()->authentifyByEmail($identifier, sha1($pwd));
        if(!(is_a($result, 'PDOException')))    // If a PDO exception has been thrown.
        {
            if(isset($result[0]))   // If the combination exists.
            {
                $_SESSION['user'] = $result[0];
                header('Location: account.php');
            }
            else                    // If the combination doesn't exist.
                header("Location: login.php?msg=Wrong email/password combination.");
        }
        else    // If a PDOException has been thrown.
            header("Location: login.php?msg=Error, please try again.");
    }
}
else    // If the password isn't set.
    header("Location: login.php?msg=Please fill all fields !");

?>