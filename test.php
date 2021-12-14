<!DOCTYPE html>

<?php
include 'connection.php';
session_start();

//to get to play the last game
$sqlgameid = "SELECT game_id FROM game";
$exegameid = $pdo->query($sqlgameid);
$id = 0;
while ($totdata = $exegameid->fetch()) {
    $id++;
}


//to get username with session
if (isset($_SESSION['username'])) {
    $usrname = $_SESSION['username'];
} else {
    $usrname = "No username! Please fill in your username in the apgtest.marcelwira.com first!";
    header("Location: index.php");
}

//to read table game from database
$sql0 = "SELECT * FROM game WHERE game_id=$id";
$exe0 = $pdo->query($sql0);
$game = $exe0->fetch();

$nameuser = false;

if ($game['total_participants'] == 0) {
    header("Location: index.php");
} else {
    $sqlcheckp = "SELECT name FROM participants WHERE game=$id";
    $execheckp = $pdo->query($sqlcheckp);
    while ($namep = $execheckp->fetch()) {
        if ($namep['name'] == $usrname) {
            $nameuser = true;
        }
    }
    if ($nameuser==false){
        header("Location: index.php");
    }
}

//update a txt file
$myfile = fopen("readData.txt", "w") or die("Unable to open file!");
$datacheer = $game['total_cheer'] . "\n";
fwrite($myfile, $datacheer);
$datajeer = $game['total_jeer'] . "\n";
fwrite($myfile, $datajeer);
$totparticipants = $game['total_participants'];
fwrite($myfile, $totparticipants);
fclose($myfile);

?>

<head>
    <title>Playtest APG</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://extension-files.twitch.tv/helper/v1/twitch-ext.min.js"></script>
</head>

<body>
    <div class="container">
        <p> Game <?= $game['game_id'] ?>
        <p> Total Participants: <?= $game['total_participants'] ?></p>

        <!-- embed twitch video -->
        <script src="https://player.twitch.tv/js/embed/v1.js"></script>
        <div id="<player div ID>"></div>
        <script type="text/javascript">
            var options = {
                width: 720,
                height: 480,
                channel: "kartikalfc",
                //video: "<video ID>",
                //collection: "<collection ID>",
                // only needed if your site is also embedded on embed.example.com and othersite.example.com
                // parent: ["embed.example.com", "othersite.example.com"]
            };
            var player = new Twitch.Player("<player div ID>", options);
            player.setVolume(0.5);

            var nickName = document.getElementById("nickname-input").value;
        </script>


        <p><?= $usrname ?></p>
        <p>Cheer: <?= $game['total_cheer'] ?></p>
        <p>Jeer: <?= $game['total_jeer'] ?></p>
        <div class="row">
            <div class="col-8 col-md-6">
                <form action="cheerjeerprocess.php" method="post" name="formCheerJeer" id="formCheerJeer">
                    <div class="row">

                        <div class="col-6 col-md-6">
                            <input type="hidden" name="cheer" id="cheer" value="" />
                            <input class="btn btn-primary btn-lg btn-block" type="button" id="cheerBtn" onclick="sendCheer()" value="Cheer" />
                        </div>
                        <div class="col-6 col-md-6">
                            <input type="hidden" name="jeer" id="jeer" value="" />
                            <input class="btn btn-danger btn-lg btn-block" type="button" id="jeerBtn" onclick="sendJeer()" value="Jeer" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </br>
        </br>

        <!-- leaderboard section -->
        <div class="row">
            <table class="table table-bordered table-hover">
                <br>
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nickname</th>
                        <th>Cheer</th>
                        <th>Jeer</th>

                    </tr>
                </thead>
                <?php

                $sqlp = "SELECT * FROM participants WHERE game=$id";
                $exep = $pdo->query($sqlp);
                $no = 0;

                while ($leaderboard = $exep->fetch()) {
                    $no++;

                ?>
                    <tbody>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $leaderboard["name"]; ?></td>
                            <td><?= $leaderboard["cheer"];   ?></td>
                            <td><?= $leaderboard["jeer"];   ?></td>
                        </tr>
                    </tbody>
                <?php
                }
                ?>
            </table>
        </div>

    </div>

    <!-- buffer function on cheer and jeer -->
    <script>
        var cheer = 0;
        var jeer = 0;

        function sendCheer() {
            cheer += 1;
            setTimeout(function() {
                document.formCheerJeer.cheer.value = cheer;
                document.formCheerJeer.jeer.value = jeer;
                //delay submit
                document.forms["formCheerJeer"].submit();
            }, 3000);
        }

        function sendJeer() {
            jeer += 1;
            setTimeout(function() {
                document.formCheerJeer.cheer.value = cheer;
                document.formCheerJeer.jeer.value = jeer;
                //delay submit
                document.forms["formCheerJeer"].submit();
            }, 3000);
        }
    </script>
</body>

</html>