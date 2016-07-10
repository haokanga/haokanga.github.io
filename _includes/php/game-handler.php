<?php
require_once("mysql-lib.php");
session_start();

$secretKey = [SECRETKEY];

if (isset($_SESSION['playerID'])) {
    $playerID = $_SESSION['playerID'];
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $conn = db_connect();

        if (isset($_GET["command"]) && isset($_GET["gameID"]) && isset($_GET["secretKey"])) {
            if ($_GET["secretKey"] == $secretKey) {
                $gameID = $_GET["gameID"];

                if ($_GET["command"] == "retrieve") {
                    // retrieve highscore
                    $scoreArray = getPlayerGameScores($conn, $gameID, $playerID);
                    echo join(',', $scoreArray);

                } else if ($_GET["command"] == "upload") {
                    // update highscore
                    if (isset($_GET["score"])) {
                        $score = $_GET["score"];
                        updatePlayerGameScores($conn, $gameID, $playerID, $score);
                    }
                }
            }
        }

        db_close($conn);
    }
} else {
    // guest mode, user can still play game but no highscore will get stored
}
?>