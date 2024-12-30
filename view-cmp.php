<?php
// Include the database connection file
include('db_connection.php');

// Initialize variables for displaying error or company details
$company_details = [];
$error_message = "";

// Check if comp_id is provided in the URL
if (isset($_GET['comp_id'])) {
    $comp_id = $_GET['comp_id'];

    // Fetch the company details based on the comp_id
    $sql = "SELECT * FROM company WHERE comp_id = $comp_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Store the company data in an associative array
        $company_details = $result->fetch_assoc();
    } else {
        // If no company found, show an error message
        $error_message = "Company not found.";
    }
} else {
    // If no comp_id is provided in the URL
    $error_message = "No company ID provided.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="assets/tablogo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>ASL : View Company Details, MB : GM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
        }

        .company-details {
            font-size: 16px;
            color: var(--primary-color);
            padding: 10px;
            border: 1px solid #ccc;
            margin-top: 20px;
        }

        .company-details .detail {
            margin-bottom: 10px;
        }

        .company-details label {
            font-weight: bold;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: rgb(21, 179, 0);
        }
    </style>
</head>
<body>
    <?php include "nav-c.html"; ?>

    <div class="container">
        <h2>Company Details</h2>

        <?php
        if ($error_message != "") {
            // Display error message if no company found or invalid comp_id
            echo "<p style='color: red; text-align: center;'>$error_message</p>";
        } else {
            // Display company details if found
            echo "<div class='company-details'>
                    <div class='detail'><label>Company Name:</label> " . $company_details['name'] . "</div>
                    <div class='detail'><label>Person Name:</label> " . $company_details['person'] . "</div>
                    <div class='detail'><label>Contact Number:</label> " . $company_details['number'] . "</div>
                    <div class='detail'><label>Designation:</label> " . $company_details['designation'] . "</div>
                    <div class='detail'><label>Email ID:</label> " . $company_details['email'] . "</div>
                    <div class='detail'><label>Accreditation:</label> " . $company_details['accreditation'] . "</div>
                    <div class='detail'><label>Turnover:</label> " . $company_details['turnover'] . "</div>
                    <div class='detail'><label>Added Date:</label> " . $company_details['added_date'] . "</div>
                </div>";

            // Provide a back button
            echo "<a href='company_details.php' class='back-button'>Back to Company List</a>";
        }
        ?>
    </div>

    <?php include "footer.html"; ?>
    <script src="themescript.js"></script>
</body>
</html>
