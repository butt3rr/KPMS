<?php
include "dbconn_login.php"; 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected date range from the form
    $startDate = $_POST["start_date"];
    $endDate = $_POST["end_date"];

    // Construct SQL query with date range filter
    // This is just a placeholder; replace it with your actual SQL query
    $sql = "SELECT * FROM customer WHERE dateTimeParcel BETWEEN '$startDate' AND '$endDate'";

    // Execute the SQL query and fetch filtered data
    // $conn should be your database connection; replace it with your actual connection variable
    $result = mysqli_query($conn, $sql);
    $filteredData = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Data</title>
</head>
<body>
    <h1>Filtered Data</h1>

    <!-- Display filtered data in a table -->
    <table>
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredData as $row) : ?>
                <tr>
                    <td><?php echo $row['trackingNumber']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <!-- Add more columns as needed -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
