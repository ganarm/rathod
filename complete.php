<?php
ob_start();
include 'db_connection.php'; // Include the database connection

// Check if 'cv_id' is provided in the URL
if (isset($_GET['cv_id'])) {
    $cv_id = $_GET['cv_id'];

    // Fetch company details along with the calibration/validation information
    $query = "SELECT c.comp_id, c.name AS company_name, c.person, c.designation, c.number, c.email, c.accreditation, c.turnover, c.added_date, 
                     cv.type, cv.done_date, cv.period, cv.cv_id, 
                     m.m_id,
                     DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) AS due_date
              FROM company c 
              JOIN cali_vali cv ON c.comp_id = cv.comp_id 
              JOIN maintaince m ON cv.cv_id = m.cv_id
              WHERE cv.cv_id = $cv_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "No data found for the given CV ID.";
        exit;
    }
} else {
    echo "No CV ID provided.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $comp_id = $row['comp_id'];
    $m_id = $row['m_id'];
    $company_intake = $_POST['company_intake'];
    $profit = $_POST['profit'];
    $description = $_POST['description'];

    // Check if the record already exists in the completed_task table
    $check_query = "SELECT * FROM completed_task WHERE cv_id = $cv_id AND m_id = $m_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('This task has already been completed for this CV ID and Maintenance ID.'); window.location.href = 'cali_vali_in_duplicate.php?cv_id=$cv_id';</script>";
        return;
    }

    // Insert data into the completed_task table if no entry exists
    $insert_query = "INSERT INTO completed_task (comp_id, cv_id, m_id, cmpny_in, profit, any_description) 
                     VALUES ($comp_id, $cv_id, $m_id, '$company_intake', '$profit', '$description')";
    if (mysqli_query($conn, $insert_query)) {
        // Update the maintenance table
        $update_query = "UPDATE maintaince 
                         SET completed_flag = 1, approved_flag = 1 
                         WHERE cv_id = $cv_id";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Task completed successfully.'); window.location.href = 'cali_vali_in_duplicate.php?cv_id=$cv_id';</script>";
        } else {
            echo "Error updating maintenance table: " . mysqli_error($conn);
        }
    } else {
        echo "Error inserting into completed_task table: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
ob_end_flush();
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
    <title>ASL : Complete Task, MB : GM</title>
    <style>
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            margin: 20px 0;
            color: var(--primary-color);
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 10px;
            vertical-align: top;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            margin: 0 8px 0 0;
        }
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
<?php include "nav-w.html"; ?>

<div class="container">
    <div class="header">
        <h2>Company and Calibration Validation Data</h2>
    </div>

    <!-- Display Company Information -->
    <h3>Company Information</h3>
    <table class="table">
        <tr>
            <th>Company Name</th>
            <td><?php echo $row['company_name']; ?></td>
        </tr>
        <tr>
            <th>Contact Person</th>
            <td><?php echo $row['person']; ?></td>
        </tr>
        <tr>
            <th>Designation</th>
            <td><?php echo $row['designation']; ?></td>
        </tr>
        <tr>
            <th>Contact Number</th>
            <td><?php echo $row['number']; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $row['email']; ?></td>
        </tr>
        <tr>
            <th>Accreditation</th>
            <td><?php echo $row['accreditation']; ?></td>
        </tr>
        <tr>
            <th>Turnover</th>
            <td><?php echo $row['turnover']; ?></td>
        </tr>
        <tr>
            <th>Added Date</th>
            <td><?php echo $row['added_date']; ?></td>
        </tr>
    </table>

    <!-- Display Calibration Validation Information -->
    <h3>Calibration Validation Information</h3>
    <table class="table">
        <tr>
            <th>Type</th>
            <td><?php echo $row['type']; ?></td>
        </tr>
        <tr>
            <th>Done Date</th>
            <td><?php echo $row['done_date']; ?></td>
        </tr>
        <tr>
            <th>Period</th>
            <td><?php echo $row['period']; ?> months</td>
        </tr>
        <tr>
            <th>Due Date</th>
            <td><?php echo $row['due_date']; ?></td>
        </tr>
        <tr>
            <th>CV ID</th>
            <td><?php echo $row['cv_id']; ?></td>
        </tr>
    </table>

    <!-- Form to input additional data and complete the task -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="company_intake">Company Intake (Rs.):</label>
            <input type="number" id="company_intake" name="company_intake" required>
        </div>
        <div class="form-group">
            <label for="profit">Profit (Rs.):</label>
            <input type="number" id="profit" name="profit" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" onclick="return confirmCompletion();">Complete Task</button>
    </form>
</div>

<script>
    function confirmCompletion() {
        return confirm('Are you sure you want to complete the task?');
    }
</script>
</body>
</html>
