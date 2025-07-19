<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customers List</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      padding: 30px;
      text-align: center;
    }
    h2 {
      color: #333;
    }
    table {
      margin: 20px auto;
      border-collapse: collapse;
      width: 90%;
      max-width: 800px;
    }
    th, td {
      border: 1px solid #999;
      padding: 12px;
      font-size: 16px;
    }
    th {
      background-color: #444;
      color: white;
    }
    td {
      background-color: #fff;
    }
    tr:hover {
      background-color: #f0f0f0;
    }
    a.home {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #0066cc;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <h2>👥 Customer List</h2>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Balance (₹)</th>
    </tr>
    <?php
      $sql = "SELECT * FROM customer";
      $result = mysqli_query($conn, $sql);

      if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>{$row['id']}</td>";
          echo "<td>{$row['name']}</td>";
          echo "<td>{$row['email']}</td>";
         echo "<td>₹{$row['amount']}</td>"; // if your column is 'amount'

          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='4'>No customers found.</td></tr>";
      }
    ?>
  </table>

  <a class="home" href="index.html">← Back to Home</a>

</body>
</html>
