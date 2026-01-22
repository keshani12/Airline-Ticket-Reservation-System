<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $date = $_POST["date"];

    // Handle file upload
    $receiptPath = null;
    if (!empty($_FILES["receipt"]["tmp_name"])) {
        $receipt = $_FILES["receipt"]["tmp_name"];
        $receiptName = $_FILES["receipt"]["name"];
        $receiptPath = "receipts/" . $receiptName;

        if (move_uploaded_file($receipt, $receiptPath)) {
            $receiptPath = $conn->real_escape_string($receiptPath);

            $sql = "INSERT INTO payment (email, date, receipt) VALUES ('$email', '$date', '$receiptPath')";
            $conn->query($sql);
        } else {
            // Failed to upload the file, handle the error
            echo "Error uploading the receipt.";
        }
    }
}

// Read
$sql = "SELECT * FROM payment";
$result = $conn->query($sql);

// Update
if (isset($_GET["edit"])) {
    $id = $_GET["edit"];
    $sql = "SELECT * FROM payment WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = $_POST["id"];
    $email = $_POST["email"];
    $date = $_POST["date"];

    // Handle file upload
    $receiptData = null;
    if (!empty($_FILES["receipt"]["tmp_name"])) {
        $receipt = $_FILES["receipt"]["tmp_name"];
        $receiptData = file_get_contents($receipt);
        $receiptData = $conn->real_escape_string($receiptData);
    }

    $sql = "UPDATE payment SET email='$email', date='$date', receipt='$receiptData' WHERE id=$id";
    $conn->query($sql);
	
	header("Location: index.php");
    exit;
}

// Delete
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM payment WHERE id=$id";
    $conn->query($sql);
	
	  header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Payment.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="main-container">
        <header class="header">
            <h1>WINGS Airlines</h1>
            <nav class="navbar">
                <a href="#">Home</a>
                <a href="#">Tours</a>
                <a href="#">Offers</a>
                <a href="#">Help</a>
                <a href="#">Login</a>
            </nav>
        </header>
        
        <div class="trip-details">
            <div class="leftSide">
                <div class="Cash-deposit">
                    <h2>Cash Deposit Information</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <?php if (isset($row)): ?>
                        <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                        <?php endif; ?>
                        <label>Deposit Date:</label>
                        <input type="date" name="date" value="<?php echo isset($row) ? $row["date"] : ""; ?>">
                        <br><br>
                        <label>Email:</label>
                        <input type="email" name="email" required placeholder="Enter e-mail Address" value="<?php echo isset($row) ? $row["email"] : ""; ?>">
                        <br><br>
                        <label>Upload Receipt:</label>
                        <input type="file" name="receipt" accept=".pdf">
                        <br><br>
                        <input type="text" placeholder="Additional Comment">
                        <br><br>
                        <input type="submit" class="submit-button" value="Submit">
                    </form>
                </div>
				<div>
				<table>
                <tr>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Receipt</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["date"]; ?></td>
                    <td><a href="view_receipt.php?id=<?php echo $row["id"]; ?>">View Receipt</a></td>
                    <td>
                        <a href="index.php?edit=<?php echo $row["id"]; ?>">Edit</a>
                        <a href="index.php?delete=<?php echo $row["id"]; ?>">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
				</div>
            </div>

            <div class="RightSide">
                <div class="WINGS">
                    <h2><span>WINGS</span> Airlines</h2>
                    <h3>Payment instructions & conditions</h3>
                    <p>Please ensure timely payment for your WINGS Airlines bookings using secure online payment methods or by visiting our authorized ticketing offices.</p>
                    <ul class="dotted-list">
                        <li>Choose from a variety of payment options for your convenience.</li>
                        <li>Follow the payment instructions provided during the booking process.</li>
                        <li>Keep your payment details secure and confidential.</li>
                        <li>Verify the accuracy of the payment amount before confirming.</li>
                        <li>Contact our customer support for any payment-related inquiries or assistance.</li>
                    </ul>
                </div>
            </div>
			
			
			
        </div>
        
       
    </div>
</body>
 <div>
            
        </div>
</html>
