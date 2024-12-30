<?php
ob_start();
include 'db_connection.php'; // Include the database connection

// Check if 'cv_id' is provided in the URL
if (isset($_GET['cv_id'])) {
    $cv_id = $_GET['cv_id'];

    // Fetch company details along with the cali_vali information
    $query = "SELECT c.comp_id, c.name AS company_name, c.person, c.designation, c.number, c.email, c.accreditation, c.turnover, c.added_date, 
                     cv.type, cv.done_date, cv.period, cv.cv_id, 
                     DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) AS due_date
              FROM company c 
              JOIN cali_vali cv ON c.comp_id = cv.comp_id 
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
    <title>ASL : View, MB : GM</title>
    <style>
        /* Container for the overall layout */
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Header style */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table for displaying data */
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
            text-decoration: none;
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

        .btn-warning {
            color: #fff;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            color: #fff;
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            color: #fff;
            background-color: #117a8b;
            border-color: #0f6674;
        }
    </style>
</head>
<body>
<?php include "nav.html"; ?>

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
        <tr>
            <th>Edit</th>
            <td><a href="cmpyin.php?comp_id=<?php echo $row['comp_id']; ?>" class="btn btn-warning">Edit Company</a></td>
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
        <tr>
            <th>Edit</th>
            <td><a href="cali_vali_edit.php?cv_id=<?php echo $row['cv_id']; ?>" class="btn btn-warning">Edit Calibration</a></td>
        </tr>
    </table>

    <a href="index.php" class="btn btn-primary">Back to Company List</a>
</div>

<?php include "footer.html"; ?>
<script src="themescript.js"></script>
</body>
</html>
