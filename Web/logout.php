<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            logout.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# Kills the current session.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

session_start();
session_destroy();
header('Location: index.php');
?>