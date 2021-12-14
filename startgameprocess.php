<?php
include 'connection.php';
session_start();

$sqlgameid = "SELECT game_id FROM game";
$exegameid = $pdo->query($sqlgameid);
$id=0;
while($totdata=$exegameid->fetch()){
    $id++;
}

if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
}else{
    header("Location: test.php");
}

if (isset($_SESSION['username'])) {
    $usrname = $_SESSION['username'];
}else{
    $usrname = "No username";
}

$sqlup = "UPDATE game set total_participants=total_participants+? WHERE game_id=?";
$exeup = $pdo->prepare($sqlup);
$exeup->execute([1, $id]);

$sql1 = "INSERT INTO participants(name, cheer, jeer, game) values (?, ?, ?, ?)";
$exe1 = $pdo->prepare($sql1);
$exe1->execute([$usrname, 0, 0, $id]);

header("Location: test.php");
?>