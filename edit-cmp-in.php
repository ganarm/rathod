<?php
// Include the database connection file
include('db_connection.php');

// Variable to store error message if email exists
$email_error = "";
$number_error = "";
$error_message = "";

// Variables to retain form data after submission
$name = $person = $number = $designation = $email = $accreditation = $turnover = "";

// Check if we're editing an existing record
if (isset($_GET['comp_id'])) {
    $comp_id = $_GET['comp_id'];

    // Fetch the company details based on the comp_id
    $sql = "SELECT * FROM company WHERE comp_id = $comp_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $person = $row['person'];
        $number = $row['number'];
        $designation = $row['designation'];
        $email = $row['email'];
        $accreditation = $row['accreditation'];
        $turnover = $row['turnover'];
    } else {
        $error_message = "Company not found.";
    }
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $name = $_POST['name'];
    $person = $_POST['person'];
    $number = $_POST['number'];
    $designation = $_POST['designation'];
    $email = $_POST['email'];
    $accreditation = $_POST['accreditation'];
    $turnover = $_POST['turnover'];

    // Check if the email already exists in the database
    $check_email_sql = "SELECT * FROM company WHERE email = '$email' AND comp_id != $comp_id";
    $result = $conn->query($check_email_sql);

    // Check if the mobile number already exists in the database
    $check_number_sql = "SELECT * FROM company WHERE number = '$number' AND comp_id != $comp_id";
    $result_number = $conn->query($check_number_sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $existing_company_name = $row['name'];
        $email_error = "Email ID already registered to the company: $existing_company_name.";
    } elseif ($result_number->num_rows > 0) {
        $row_number = $result_number->fetch_assoc();
        $existing_company_name_number = $row_number['name'];
        $number_error = "Mobile number already registered to the company: $existing_company_name_number.";
    } else {
        // Update company details
        $sql = "UPDATE company SET name='$name', person='$person', number='$number', designation='$designation', email='$email', accreditation='$accreditation', turnover='$turnover' WHERE comp_id = $comp_id";

        if ($conn->query($sql) === TRUE) {
            $error_message = "Record updated successfully";
            // Clear form data after successful submission
            $name = $person = $number = $designation = $email = $accreditation = $turnover = "";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

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
    <title>ASL : Edit CMPY, MB : GM</title>
    <style>
        /* Global Styles */
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
            border-bottom: 8px solid var(--primary-color);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .form-input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            color: var(--primary-color);
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .submit-btn {
            padding: 10px 15px;
            background-color: var(--primary-color);
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: rgb(21, 179, 0);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .form-group label,
            .form-input {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <?php
    include "nav.html";
    ?>
    <h2>Edit Company</h2>

    <?php
    if ($email_error != "") {
        echo "<script>alert('$email_error');</script>";
    } else if ($number_error != "") {
        echo "<script>alert('$number_error');</script>";
    }
    if ($error_message != "") {
        echo "<script>alert('$error_message');</script>";
    }
    ?>

    <form method="POST" action="">
        <div>
            <label for="name">Company Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div>
            <label for="person">Person Name:</label>
            <input type="text" id="person" name="person" value="<?php echo $person; ?>" required>
        </div>
        <div>
            <label for="number">Contact Number:</label>
            <input type="text" id="number" name="number" value="<?php echo $number; ?>" required>
        </div>
        <div>
            <label for="designation">Designation:</label>
            <input type="text" id="designation" name="designation" value="<?php echo $designation; ?>" required>
        </div>
        <div>
            <label for="email">Email ID:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        </div>
        <div>
            <label for="accreditation">Accreditation:</label>
            <select id="accreditation" name="accreditation" required>
                <option value="">Select Accreditation</option>
                <option value="A" <?php echo ($accreditation == 'A') ? 'selected' : ''; ?>>A</option>
                <option value="B" <?php echo ($accreditation == 'B') ? 'selected' : ''; ?>>B</option>
                <option value="C" <?php echo ($accreditation == 'C') ? 'selected' : ''; ?>>C</option>
                <option value="D" <?php echo ($accreditation == 'D') ? 'selected' : ''; ?>>D</option>
            </select>
        </div>
        <div>
            <label for="turnover">Turnover:</label>
            <input type="text" id="turnover" name="turnover" value="<?php echo $turnover; ?>" required>
        </div>
        <div>
            <button type="submit" name="submit">Update</button>
        </div>
    </form>
</body>
</html>
