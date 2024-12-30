<?php
// Include the database connection file
include('db_connection.php');

// Variables for error messages
$email_error = "";
$number_error = "";
$error_message = "";

// Variables to retain form data after submission
$name = $person = $number = $designation = $email = $accreditation = $turnover = "";

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

    // Check if we're editing or adding
    if (isset($_POST['comp_id']) && $_POST['comp_id'] != "") {
        // We're updating the record, so skip the email and number checks
        $comp_id = $_POST['comp_id'];
        // Update company details
        $sql = "UPDATE company SET name='$name', person='$person', number='$number', designation='$designation', email='$email', accreditation='$accreditation', turnover='$turnover' WHERE comp_id = '$comp_id'";
        
        if ($conn->query($sql) === TRUE) {
            $error_message = "Record updated successfully";
            // Clear form data after successful submission
            $name = $person = $number = $designation = $email = $accreditation = $turnover = "";
        } else {
            $error_message = "Error updating record: " . $conn->error;
        }
    } else {
        // We're inserting a new record, so check for email and number conflicts
        $check_email_sql = "SELECT * FROM company WHERE email = '$email'";
        $result = $conn->query($check_email_sql);

        $check_number_sql = "SELECT * FROM company WHERE number = '$number'";
        $result_number = $conn->query($check_number_sql);

        if ($result->num_rows > 0) {
            // If email exists, fetch the company name
            $row = $result->fetch_assoc();
            $existing_company_name = $row['name']; // Fetch company name
            // Email already exists, display an error message
            $email_error = "Email ID already registered to the company: $existing_company_name.";
        } elseif ($result_number->num_rows > 0) {
            // If mobile number exists, fetch the company name
            $row_number = $result_number->fetch_assoc();
            $existing_company_name_number = $row_number['name']; // Fetch company name
            // Mobile number already exists, display an error message
            $number_error = "Mobile number already registered to the company: $existing_company_name_number.";
        } else {
            // If no conflicts, insert the new company data
            $sql = "INSERT INTO company (name, person, number, designation, email, accreditation, turnover) 
                    VALUES ('$name', '$person', '$number', '$designation', '$email', '$accreditation', '$turnover')";

            if ($conn->query($sql) === TRUE) {
                $error_message = "New record added successfully";
                // Clear form data after successful submission
                $name = $person = $number = $designation = $email = $accreditation = $turnover = "";
            } else {
                $error_message = "Error inserting record: " . $conn->error;
            }
        }
    }
}

// Check if we're editing an existing record (fetch data for the form)
if (isset($_GET['comp_id'])) {
    $comp_id = $_GET['comp_id'];

    // Fetch the company details based on the comp_id
    $sql = "SELECT * FROM company WHERE comp_id = '$comp_id'";
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
    <title>ASL : CMPY IN, MB : GM</title>
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
    include "nav-c.html";
    ?>
    <div class="container">
        <a href="company_details.php" style="float:right;text-decoration:none;"class="submit-btn">View Data</a>
        <h2>Company Registration Form</h2>

        <?php
        if ($email_error != "") {
            echo "<script>alert('$email_error');</script>";
        } else if ($number_error != "") {
            echo "<script>alert('$number_error');</script>";
        }
        if ($error_message != "") {
            echo "<script>
                        alert('$error_message');
                        window.location.href = 'company_details.php';
                    </script>";
        }
        ?>

        <form method="POST" action="" onsubmit="return validateForm()" class="form-container">
            <input type="hidden" name="comp_id" value="<?php echo isset($_GET['comp_id']) ? $_GET['comp_id'] : ''; ?>">

            <div class="form-group">
                <label for="name">Company Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="form-input">
            </div>

            <div class="form-group">
                <label for="person">Person Name:</label>
                <input type="text" id="person" name="person" value="<?php echo $person; ?>" class="form-input">
            </div>

            <div class="form-group">
                <label for="number">Contact Number:</label>
                <input type="text" id="number" name="number" value="<?php echo $number; ?>" class="form-input">
            </div>

            <div class="form-group">
                <label for="designation">Designation:</label>
                <input type="text" id="designation" name="designation" value="<?php echo $designation; ?>" class="form-input">
            </div>

            <div class="form-group">
                <label for="email">Email ID:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="form-input">
            </div>

            <div class="form-group">
                <label for="accreditation">Accreditation:</label>
                <select id="accreditation" name="accreditation" class="form-input">
                    <option value="">Select Accreditation</option>
                    <option value="A" <?php echo ($accreditation == 'A') ? 'selected' : ''; ?>>A</option>
                    <option value="B" <?php echo ($accreditation == 'B') ? 'selected' : ''; ?>>B</option>
                    <option value="C" <?php echo ($accreditation == 'C') ? 'selected' : ''; ?>>C</option>
                    <option value="D" <?php echo ($accreditation == 'D') ? 'selected' : ''; ?>>D</option>
                </select>
            </div>

            <div class="form-group">
                <label for="turnover">Turnover:</label>
                <input type="text" id="turnover" name="turnover" value="<?php echo $turnover; ?>" class="form-input">
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="submit-btn">Submit</button>
            </div>
        </form>
    </div>

    <?php
    include "footer.html";
    ?>
    <script src="themescript.js"></script>
</body>
</html>
