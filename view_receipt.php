<?php
include "config.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM payment WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    // Check if the receipt file exists
    $receiptPath = $row["receipt"];
    if (file_exists($receiptPath)) {
        // Display the receipt as a PDF
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=receipt.pdf");
        readfile($receiptPath);
        exit();
    } else {
        echo "Receipt file not found.";
    }
}
?>
