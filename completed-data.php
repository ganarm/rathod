<?php
ob_start();
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
    <title>ASL : Completed Data, MB : GM</title>
    <style>
        
        .table {

            width: 95%;
            margin: 0 auto;
            margin-bottom: 1rem;
            /* color: #212529; */
            color: var(--primary-color);
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: center; 
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            color: #212529;
            background-color: rgba(0, 0, 0, 0.075);
            cursor: pointer;
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
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
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
            .btn-success {
                color: #fff;
                background-color: #28a745;
                border-color: #28a745;
            }
            .btn-success:hover {
                color: #fff;
                background-color: #218838;
                border-color: #1e7e34;
            }
            .btn-danger {
                color: #fff;
                background-color: #dc3545;
                border-color: #dc3545;
            }
            .btn-danger:hover {
                color: #fff;
                background-color: #c82333;
                border-color: #bd2130;
            }
            .btn-warning {
                color: #212529;
                background-color: #ffc107;
                border-color: #ffc107;
            }
            .btn-warning:hover {
                color: #212529;
                background-color: #e0a800;
                border-color: #d39e00;
            }
            .btn:hover {
                text-decoration: none;
            }
            .btn:focus, .btn:active {
                outline: none;
                box-shadow: none;
            }
            #filterForm {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: row;
        align-items:center;
        width: 97%;
        text-align:center;
        justify-content:center;
        gap: 40px;
        margin:20px;
        color:var(--primary-color);
        }

        label {
        font-size: 14px;
        margin-bottom: 5px;
        color:var(--primary-color);
        }

        select {
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        background-color: #fff;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
        color:var(--primary-color);
        }

        select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        select option {
        padding: 10px;
        }

        form button {
        padding: 10px 15px;
        font-size: 14px;
        border-radius: 4px;
        background-color: var(--primary-color);
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
        }

        form button:hover {
        background-color: var(--primary-color);
        }

        #search
        {
            margin:20px 40px;
        }
        #search input
        {
            padding:6px;
        }
    </style>
</head>
<body>
<?php 
    include "nav-w.html";
    ?>
    <container>
<?php
        include 'db_connection.php'; // Include the database connection

        // Handle the POST request to update flags
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $m_id = $_POST['m_id'];
            $action = $_POST['action'];
            // Prepare query for different actions
            if ($action == 'call') {
                // Increment the call_flag
                $query = "UPDATE maintaince SET call_flag = call_flag + 1 WHERE m_id = $m_id";
            
                // Update last_call to the current timestamp
                $query_last_call = "UPDATE maintaince SET last_call = NOW() WHERE m_id = $m_id";
                mysqli_query($conn, $query_last_call); // Execute the last call update
            } elseif ($action == 'approve') {
                // Set the approved_flag to 1 (approved)
                $query = "UPDATE maintaince SET approved_flag = 1 WHERE m_id = $m_id";
            } elseif ($action == 'complete') {
                // Set the completed_flag to 1 and update done_date to current date in cali_vali table
                $query = "UPDATE cali_vali SET done_date = NOW() WHERE cv_id = (SELECT cv_id FROM maintaince WHERE m_id = $m_id)";
            
                // Execute the query to update done_date
                if (mysqli_query($conn, $query)) {
                // Now reset all flags to 0 in the maintaince table for that m_id
                $query_flags_reset = "UPDATE maintaince SET call_flag = 0, approved_flag = 0, completed_flag = 0 WHERE m_id = $m_id";
                mysqli_query($conn, $query_flags_reset);
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            }
        
            // Execute the query for action (call/approve/complete)
            if (mysqli_query($conn, $query)) {
                // Redirect to the same page to show updated status
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }

        // Initialize filters
        $call_filter = isset($_GET['call_filter']) ? $_GET['call_filter'] : '';
        $approve_filter = isset($_GET['approve_filter']) ? $_GET['approve_filter'] : '';
        $due_filter = isset($_GET['due_filter']) ? $_GET['due_filter'] : '';

        // Fetch data from the database
        $query = "SELECT 
                    c.name AS company_name, 
                    c.person AS person_name, 
                    c.number, 
                    cv.type, 
                    cv.done_date, 
                    mv.due_flag, 
                    mv.call_flag, 
                    mv.approved_flag, 
                    mv.completed_flag, 
                    mv.m_id, 
                    cv.period, 
                    cv.cv_id,
                    DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) AS due_date
                FROM 
                    company c
                JOIN 
                    cali_vali cv ON c.comp_id = cv.comp_id
                JOIN 
                    maintaince mv ON cv.cv_id = mv.cv_id
                WHERE 1=1";
        $query .= " AND mv.completed_flag = 1";
        // Apply filters
        if ($call_filter == 'no_calls') {
            $query .= " AND mv.call_flag = 0";
        } elseif ($call_filter == 'single_call') {
            $query .= " AND mv.call_flag = 1";
        }
        if ($approve_filter == 'remaining') {
            $query .= " AND mv.approved_flag = 0";
        }
        if ($due_filter == 'due_soon_1_month') {
            $query .= " AND DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) <= DATE_ADD(NOW(), INTERVAL 1 MONTH)";
        } elseif ($due_filter == 'due_soon_2_months') {
            $query .= " AND DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) <= DATE_ADD(NOW(), INTERVAL 2 MONTH)";
        }

        $query .= " ORDER BY due_date ASC, mv.due_flag, mv.completed_flag ASC"; // Sort by due date ascending

        $result = mysqli_query($conn, $query);
        ?>

<!-- Filter Form -->
<form id="filterForm" method="GET" action="">
    <label for="call_filter">Call Filter:</label>
    <select name="call_filter" id="call_filter">
        <option value="">All</option>
        <option value="no_calls" <?php if ($call_filter == 'no_calls') echo 'selected'; ?>>No Calls</option>
        <option value="single_call" <?php if ($call_filter == 'single_call') echo 'selected'; ?>>Single Call</option>
    </select>
    
    <label for="approve_filter">Approval Filter:</label>
    <select name="approve_filter" id="approve_filter">
        <option value="">All</option>
        <option value="remaining" <?php if ($approve_filter == 'remaining') echo 'selected'; ?>>Approval Remaining</option>
    </select>
    
    <label for="due_filter">Due Date Filter:</label>
    <select name="due_filter" id="due_filter">
        <option value="">All</option>
        <option value="due_soon_1_month" <?php if ($due_filter == 'due_soon_1_month') echo 'selected'; ?>>Due Within 1 Month</option>
        <option value="due_soon_2_months" <?php if ($due_filter == 'due_soon_2_months') echo 'selected'; ?>>Due Within 2 Months</option>
    </select>
    <?php echo"<a style='text-decoration: none;' href='index.php' class='btn btn-success'>Incompleted Data</a>";?>
</form>
<!-- <form id="search">
    <label for="search_term">Search:</label>
    <input type="text" name="search_term" id="search_term" >
    <button class='btn btn-primary' type='submit' name='search' value='search'>Search</button>
</form> -->
<script>
document.getElementById('call_filter').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
document.getElementById('approve_filter').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
document.getElementById('due_filter').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>

<?php
// Display the results in a table
$i=0;
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table class='table table-striped table-bordered table-hover'>
        <tr>
            <th>#</th>
            <th style='width: 100px;'>Company Name</th>
            <th>Person Name</th>
            <th>Number</th>
            <th style='width: 300px;'>Type</th>
            <th>Done Date</th>
            <th>Due Date</th>
            <th>Action</th>
        </tr>";

    // Iterate through each row and display data
    while ($row = mysqli_fetch_assoc($result)) {
        $completed_date = $row['done_date']; // Done date from DB
        $i++;
        // Check if done_date is valid and not empty
        if (empty($completed_date)) {
            echo "<tr><td colspan='7'>Done date is missing for company: " . $row['company_name'] . "</td></tr>";
            continue; // Skip this row if done_date is empty
        }

        // If the done_date has time (e.g., "2024-12-13 23:33:27"), we only need the date part
        $date_parts = explode(' ', $completed_date);
        $done_date_str = $date_parts[0]; // Get the date portion ("2024-12-13")

        // Try to create a DateTime object from the done_date (now just a date without time)
        $done_date = DateTime::createFromFormat('Y-m-d', $done_date_str);
        
        if ($done_date === false) {
            echo "<tr><td colspan='7'>Invalid done date format for company: " . $row['company_name'] . " (Date: $completed_date)</td></tr>";
            continue; // Skip this row if the date format is invalid
        }

        // Add the period (months) to the done date
        $period = $row['period']; // Period in months from DB
        $done_date->modify("+$period months"); // Add period to the done date

        // Format the new date (due date)
        $due_date = $done_date->format('d-m-Y'); // Format it as dd/mm/yyyy

        echo "<tr>";
        echo "<td>" . $i . "</td>";
        echo "<td>" . $row['company_name'] . "</td>";
        echo "<td>" . $row['person_name'] . "</td>";
        echo "<td>" . $row['number'] . "</td>";
        echo "<td>" . $row['type'] . "</td>";
        echo "<td>" . $row['done_date'] . "</td>";
        echo "<td>" . $due_date . "</td>"; // Display due date
        echo "<td>";
        echo "<form method='POST' action='" . $_SERVER['PHP_SELF'] . "'>
                 <input type='hidden' name='m_id' value='" . $row['m_id'] . "'>";

        // For the Call button, show the number of calls
        if ($row['call_flag'] > 0) {
            echo "<button class='btn btn-primary' type='submit' name='action' value='call'>" . $row['call_flag'] . " Call</button>";
        } else {
            echo "<button class='btn btn-danger' type='submit' name='action' value='call'>Call</button>";
        }

        // For the Approve button, show 'Approve' if not approved
        if ($row['approved_flag'] == 0) {
            echo "<button class='btn btn-warning' type='submit' name='action' value='approve'>Approve</button>";
        } else {
            echo "<button class='btn btn-success' type='submit' name='action' value='approve' disabled>Approved</button>";
        }

        // For the Complete button, show 'Complete' if not completed
        if ($row['completed_flag'] == 0) {
            echo "<a style='text-decoration: none;' href='complete.php?cv_id=" . $row['cv_id'] . "' class='btn btn-info'>Complete</a>";
        } else {
            echo "<button type='submit' name='action' value='complete' class='btn btn-warning' disabled>Completed</button>";
        }

        // For the view
        echo "<a style='text-decoration: none;' href='completed-data-view.php?cv_id=" . $row['cv_id'] . "' class='btn btn-success'>View</a>";


        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No data found";
}

// Close the database connection
mysqli_close($conn);
ob_end_flush();
?>

    </container>
    <?php include "footer.html";?>
    <!--  Script for storing theme color  -->
    <script src="themescript.js"></script>
</body>
</html>
    