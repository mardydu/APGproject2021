<?php
include 'connection.php';
session_start();
$sql = "INSERT INTO game(total_cheer, total_jeer, total_participants) values (?,?,?)";
$exec = $pdo->prepare($sql);
$exec->execute([0, 0, 0]);
$_SESSION['last_id'] = $pdo->lastInsertId();

header("Location: startgameadmin.php");

?>