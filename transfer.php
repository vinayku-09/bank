<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Transfer Money</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            background-color: #f2f2f2;
            padding: 40px;
        }
        form {
            background: white;
            display: inline-block;
            padding: 30px;
            border-radius: 10px;
        }
        input, select {
            padding: 10px;
            margin: 10px;
            width: 250px;
        }
        button {
            padding: 10px 20px;
            background: green;
            color: white;
            border: none;
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>💸 Transfer Money</h2>

<form method="POST">
    <select name="from" required>
        <option disabled selected>-- From Customer --</option>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM customer");
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<option value='{$row['id']}'>{$row['name']} (₹" . number_format($row['amount'], 2) . ")</option>";
        }
        ?>
    </select><br>

    <select name="to" required>
        <option disabled selected>-- To Customer --</option>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM customer");
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<option value='{$row['id']}'>{$row['name']} (₹" . number_format($row['amount'], 2) . ")</option>";
        }
        ?>
    </select><br>

    <input type="number" name="amount" placeholder="Amount in ₹" min="1" step="0.01" required><br>
    <button type="submit" name="transfer">Transfer</button>
</form>

<?php
if (isset($_POST['transfer'])) {
    $from = (int) $_POST['from'];
    $to = (int) $_POST['to'];
    $amount = floatval($_POST['amount']);

    if ($from === $to) {
        echo "<p class='message' style='color:red;'>❌ Cannot transfer to the same account.</p>";
    } elseif ($amount <= 0) {
        echo "<p class='message' style='color:red;'>❌ Please enter a valid amount.</p>";
    } else {
        $fromRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customer WHERE id=$from"));
        $toRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customer WHERE id=$to"));

        if (!$fromRow || !$toRow) {
            echo "<p class='message' style='color:red;'>❌ Invalid customer selected.</p>";
        } elseif ($fromRow['amount'] < $amount) {
            echo "<p class='message' style='color:red;'>❌ Insufficient balance!</p>";
        } else {
            // Start transaction
            mysqli_begin_transaction($conn);

            $deduct = mysqli_query($conn, "UPDATE customer SET amount = amount - $amount WHERE id = $from");
            $add = mysqli_query($conn, "UPDATE customer SET amount = amount + $amount WHERE id = $to");
            $insertHistory = mysqli_query($conn, "INSERT INTO history (sender, receiver, amount) VALUES (
                '{$fromRow['name']}', '{$toRow['name']}', $amount)");

            if ($deduct && $add && $insertHistory) {
                mysqli_commit($conn);
                echo "<p class='message' style='color:green;'>✅ Transfer successful!</p>";
            } else {
                mysqli_rollback($conn);
                echo "<p class='message' style='color:red;'>❌ Transaction failed. Please try again.</p>";
                echo "<p style='color:red;'>Debug Info:<br>";
                echo "Error 1: " . mysqli_error($conn) . "<br>";
                echo "Error 2: " . mysqli_error($conn) . "<br>";
                echo "Error 3: " . mysqli_error($conn) . "</p>";
            }
        }
    }
}
?>

<br><a href="index.html">← Back to Home</a>

</body>
</html>
