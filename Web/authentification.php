<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            authentification.php
# Date :                09.06.17
#--------------------------------------------------------------------------
# This file is used to log the user in if the username/password OR the email/password combination is correct.
#
# Version 1.0 :         09.06.17
#--------------------------------------------------------------------------

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
            checkIfCombinationExists($result);
        }
    }
    else    // If the identifier is an email.
    {
        $result = Gestock::getInstance()->authentifyByEmail($identifier, sha1($pwd));
        checkIfCombinationExists($result);
    }
}
else    // If the password isn't set.
    header("Location: login.php?msg=Please fill all fields !");

/**
 * Checks if the combination exsits. If it does, redirects to the user's account page, if it doesn't, returns to "login.php" with an error message.
 * @param type $result
 */
function checkIfCombinationExists($result)
{
    if($result)     // If the combination exists.
    {
        $_SESSION['user'] = $result[0];
        print_r($result[0]);
        header('Location: account.php'); 
    }
    else    // It the combination doesn't exist.
        header("Location: login.php?msg=Wrong username/password combination.");
}

?>