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
    <title>ASL : CMPY DETAILS, MB : GM</title>
    <style>
        .table {
            width: 95%;
            margin: 0 auto;
            margin-bottom: 1rem;
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

        .btn:focus,
        .btn:active {
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
            align-items: center;
            width: 97%;
            text-align: center;
            justify-content: center;
            gap: 40px;
            margin: 20px;
            color: var(--primary-color);
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        select {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background-color: #fff;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
            color: var(--primary-color);
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

        #search {
            margin: 20px 40px;
        }

        #search input {
            padding: 6px;
        }

        .anc {
            list-style: none;
            text-decoration: none;
        }
        #search_query{
            width: 25%;
            height: 40px;
            margin: 30px 30px 30px 60px;
            padding: 10px;
            font-size: medium;
        }
    </style>
</head>
<body>
<?php 
    include "nav-c.html";
?>
<br>
<container>
<?php
    include 'db_connection.php'; // Include the database connection

    // Handle the POST request to update flags
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $comp_id = $_POST['comp_id'];
        $action = $_POST['action'];

        // Prepare query for different actions
        if ($action == 'delete') {
            // Delete the company record
            $query = "DELETE FROM company WHERE comp_id = $comp_id";
        }
        // Execute the query for action (delete)
        if (mysqli_query($conn, $query)) {
            // Redirect to the same page to show updated status
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Fetch search query if provided
    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

    // Modify the SQL query to include search functionality
    $query = "SELECT comp_id, name, person, designation, number, email, accreditation, turnover, added_date FROM company WHERE name LIKE '%$search_query%' OR person LIKE '%$search_query%' OR email LIKE '%$search_query%'";

    $result = mysqli_query($conn, $query);
?>

<!-- Search Form -->
<form method="GET" action="" id="searchForm">
    <input type="text" name="search_query" id="search_query" placeholder="Search by Company Name, Person, or Email" value="<?php echo $search_query; ?>" class="form-input" />
    <button type="submit" class="btn btn-primary">Search</button>
    <a href="cmpyin.php" style="float:right;text-decoration:none;" class="btn btn-success">Add New Company</a>
</form>

<!-- Display the results in a table -->
<?php
$i = 0;
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table class='table table-striped table-bordered table-hover'>
        <tr>
            <th>#</th>
            <th>Company Name</th>
            <th>Call Person</th>
            <th>Designation</th>
            <th>Number</th>
            <th>Email</th>
            <th>Action</th>
        </tr>";

    // Iterate through each row and display data
    while ($row = mysqli_fetch_assoc($result)) {
        $i++;
        echo "<tr>";
        echo "<td>" . $i . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['person'] . "</td>";
        echo "<td>" . $row['designation'] . "</td>";
        echo "<td>" . $row['number'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>";
        echo "<form style='display: inline-block;' method='POST' action='" . $_SERVER['PHP_SELF'] . "'>
            <a href='view-cmp.php?comp_id=" . $row['comp_id'] . "' class='btn btn-info anc'>View</a>
            <a href='cmpyin.php?comp_id=" . $row['comp_id'] . "' class='btn btn-warning anc'>Edit</a>
            <input type='hidden' name='comp_id' value='" . $row['comp_id'] . "'>
            <button class='btn btn-danger' type='submit' name='action' value='delete' onclick='return confirmDelete()'>Delete</button>
        </form>";
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
<script src="themescript.js"></script>
<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this entry?');
}
</script>
</body>
</html>
