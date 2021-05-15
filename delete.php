<?php
require 'database.php';

$get_id = $_GET['id'];
$statement = $db->prepare("DELETE FROM user_details WHERE user_id = ?");

if ($statement->execute([$get_id])) {
    $page = $_GET['page'];
    header("Location: index.php?page=$page");
}

?>