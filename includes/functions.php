<?php

include "../includes/db_connection.php";

function executeQuery($sql, $params, $pdo = null)
{
    if ($pdo === null) {
        // Assuming $pdo is a global variable representing your database connection
        global $pdo;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}
