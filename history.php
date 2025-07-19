<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <style>
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 40px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>📜 Transaction History</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Amount (₹)</th>
            <th>Date</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM history ORDER BY created_at DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['sender']}</td>
                    <td>{$row['receiver']}</td>
                    <td>₹" . number_format($row['amount'], 2) . "</td>
                    <td>{$row['created_at']}</td>
                </tr>";
        }
        ?>
    </table>
</body>
</html>
