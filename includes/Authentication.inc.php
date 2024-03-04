<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $Email = $_GET["Email"];
    $Password = $_GET["Password"];

    try {
        require_once "dbh.inc.php";

        $query= "SELECT * FROM  users WHERE Email=:Email AND Password=:Password";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Password', $Password);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $Name = $user['Name'];
            header("location:../Main.html");
            exit();
        } else {
            header("location:../failed.html");
            exit();
        }
    } catch (PDOException $e) {
        die("Query failed:" . $e->getMessage());
    }
} else {
    header("location:../failed.html");
    exit();
}

