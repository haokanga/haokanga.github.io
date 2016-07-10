<?php
/* db connection*/
function db_connect()
{

    $conn = null;

    $serverName = "localhost";
    $username = [USERNAME];
    $password = [PASSWORD];

    $conn = new PDO("mysql:host=$serverName; dbname=[DBNAME]; charset=utf8", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;

}

function db_close(PDO $conn)
{
    $conn = null;
}

/* db connection*/

/* game */
function getGame(PDO $conn, $gameID)
{
    return getRecord($conn, $gameID, "Game");
}

function getPlayerGameScores(PDO $conn, $gameID, $playerID)
{
    $levels = getGame($conn, $gameID)->Levels;
    $scoreArray = array_fill(0, $levels, 0);

    for ($level = 1; $level <= $levels; $level++) {
        $retrieveScorePreSql = "SELECT COUNT(*) FROM Game_Record WHERE `GameID` = ? AND `PlayerID` = ? AND `Level` = ?";
        $retrieveScorePreQuery = $conn->prepare($retrieveScorePreSql);
        $retrieveScorePreQuery->execute(array($gameID, $playerID, $level));
        if ($retrieveScorePreQuery->fetchColumn() > 0) {
            $retrieveScoreSql = "SELECT GameID,PlayerID,`Level`,Score FROM Game_Record WHERE `GameID` = ? AND `PlayerID` = ? AND `Level` = ?";
            $retrieveScoreQuery = $conn->prepare($retrieveScoreSql);
            $retrieveScoreQuery->execute(array($gameID, $playerID, $level));
            $retrieveScoreResult = $retrieveScoreQuery->fetch(PDO::FETCH_OBJ);
            $scoreArray[$level - 1] = $retrieveScoreResult->Score;
        } else {
            $scoreArray[$level - 1] = 0;
        }
    }

    return $scoreArray;
}

function updatePlayerGameScores(PDO $conn, $gameID, $playerID, $score)
{
    $historyHighScore = getPlayerGameScores($conn, $gameID, $playerID);
    $updateSql = "INSERT INTO Game_Record(GameID,PlayerID,`Level`,Score)
                     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE Score = ?";
    $updateSql = $conn->prepare($updateSql);
    for ($level = 1; $level <= count($score); $level++) {
        //Override stored score only if in-game score exceeds highscore in database
        if ($score[$level - 1] > $historyHighScore[$level - 1]) {
            $updateSql->execute(array($gameID, $playerID, $level, $score[$level - 1], $score[$level - 1]));
        }
    }
}
/* game */

/* helper func */
function getRecord(PDO $conn, $recordID, $tableName, array $joinTables = null)
{
    $tablePK = $tableName . "ID";

    $tableSql = "SELECT COUNT(*)
				 FROM $tableName
				 WHERE $tablePK = ?";
    $tableQuery = $conn->prepare($tableSql);
    $tableQuery->execute(array($recordID));
    if ($tableQuery->fetchColumn() != 1) {
        throw new Exception("Fail to get record from $tableName where ID = $recordID");
    }

    $tableSql = "SELECT * FROM $tableName";
    if ($joinTables != null) {
        foreach ($joinTables as $joinTable) {
            $tableSql .= " NATURAL JOIN " . $joinTable;
        }
    }
    $tableSql .= " WHERE $tablePK = ?";
    $tableQuery = $conn->prepare($tableSql);
    $tableQuery->execute(array($recordID));
    $tableResult = $tableQuery->fetch(PDO::FETCH_OBJ);
    return $tableResult;
}
/* helper func */
?>