<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if email and OTP are set in the session
    if (isset($_SESSION["user_email"]) && isset($_GET["OTP1"]) && isset($_GET["OTP2"]) && isset($_GET["OTP3"]) && isset($_GET["OTP4"])) {
        $Email = $_SESSION["user_email"];
        $OTP = $_GET["OTP1"] . $_GET["OTP2"] . $_GET["OTP3"] . $_GET["OTP4"]; // Concatenate OTP from all four boxes

        try {
            require_once "dbh.inc.php";

            // Check if the entered OTP is correct for the corresponding email
            $query = "SELECT * FROM users WHERE Email = :Email AND OTP = :OTP";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':OTP', $OTP);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                // OTP is correct
                // Perform any additional actions you need
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $Name = $user['Name'];
                header("location:../Main.html");
                exit();
            } else {
                // OTP is incorrect or email not found
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
} else {
    header("location:../failed.html");
    exit();
}
