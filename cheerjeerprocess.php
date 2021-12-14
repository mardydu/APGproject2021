<?php
include 'connection.php';
session_start();

$total_p=0;

// check if $_POST exist
if( isset($_POST['cheer']) )
{
    $cheer = $_POST['cheer'];
}else{
    $cheer = 0;
}
if( isset($_POST['jeer']) )
{
    $jeer = $_POST['jeer'];
}else{
    $jeer = 0;
}

//to get the last game id
$sqlgameid = "SELECT game_id FROM game";
$exegameid = $pdo->query($sqlgameid);
$id=0;
while($totdata=$exegameid->fetch()){
    $id++;
}

//to get username
if( isset($_SESSION['username']) )
{
    $usrname = $_SESSION['username'];
}else{
    header("Location: index.php");
}

$sql1 = "SELECT * FROM participants WHERE game=$id";
$exe1 = $pdo->query($sql1);
while ($game1 = $exe1->fetch()){
    $total_p+=1;
}

//update cheer and jeer from buffer to database
$sql2 = "UPDATE game set total_cheer=total_cheer+?, total_jeer=total_jeer+?, total_participants=? WHERE game_id=?";
$exe2 = $pdo->prepare($sql2);
$exe2->execute([$cheer, $jeer, $total_p, $id]);

$sql3 = "UPDATE participants set cheer=cheer+?, jeer=jeer+? WHERE name=? AND game=?";
$exe3 = $pdo->prepare($sql3);
$exe3->execute([$cheer, $jeer, $usrname, $id]);

header("Location: test.php");

?>