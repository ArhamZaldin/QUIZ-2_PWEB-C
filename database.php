<?php
    $DBHOSTNAME = 'mysql:host=localhost; dbname=userAccounts';
    $userDB = 'root';
    $passDB = '';

    try {
        $db = new PDO($DBHOSTNAME, $userDB, $passDB);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
?>