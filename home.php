<?php
    include 'db_connection.php';
    $query = "SELECT * FROM company";
    $result = mysqli_query($conn, $query);
    $cmp_num_row = mysqli_num_rows($result);

    $query = "
    SELECT COUNT(*) AS total_count 
    FROM cali_vali cv 
    JOIN maintaince m ON cv.cv_id = m.cv_id 
    WHERE m.completed_flag = 0
    ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_count = $row['total_count'];
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    $cav_num_row = $total_count;

    $query = "SELECT * FROM maintaince WHERE approved_flag=1 AND completed_flag=0";
    $result = mysqli_query($conn, $query);
    $cava_num_row = mysqli_num_rows($result);

    $query_count = "
    SELECT COUNT(*) AS entry_count
    FROM cali_vali cv
    JOIN maintaince m ON cv.cv_id = m.cv_id
    WHERE 
        DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) <= DATE_ADD(NOW(), INTERVAL 1 MONTH)
        AND m.completed_flag = 0
    ";
    $result_count = mysqli_query($conn, $query_count);

    if ($result_count) {
        $row_count = mysqli_fetch_assoc($result_count);
        $entry_count = $row_count['entry_count'];
    } else {
        echo "Error: " . mysqli_error($conn);
    }


    $query_count_2_months = "
    SELECT COUNT(*) AS entry_count_2_months
    FROM cali_vali cv
    JOIN maintaince m ON cv.cv_id = m.cv_id
    WHERE 
        DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) <= DATE_ADD(NOW(), INTERVAL 2 MONTH)
        AND m.completed_flag = 0
    ";

    $result_count_2_months = mysqli_query($conn, $query_count_2_months);

    if ($result_count_2_months) {
        $row_count_2_months = mysqli_fetch_assoc($result_count_2_months);
        $entry_count_2_months = $row_count_2_months['entry_count_2_months'];
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    $query = "SELECT * FROM completed_task";
    $result = mysqli_query($conn, $query);
    $completed = mysqli_num_rows($result);
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
    <title>ASL : Home, MB: GM</title>
    <style>
        #item1,
        #item2,
        #item3,
        #item4, 
        #item5,
        #item6 {background-image: linear-gradient(195deg,rgb(125, 239, 180) 0%,rgb(25, 242, 148) 100%); color: black;}
        .grid-container 
        {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        .grid-item 
        {
            width: 350px;
            height:150px;
            padding:20px;
            font-size: 30px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(21, 20, 20, 0.6);
            transition: transform 0.3s,box-shadow 0.3s;
            color:aliceblue;
            cursor: pointer;
            margin: 20px 0;
         }
        .grid-item:hover
        {
            transform:scale(1.08);
            box-shadow: 0 8px 16px rgba(21, 20, 20, 0.6);
        }
        a{
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php
        include 'nav.html';
    ?>
    <div class="container">
        <div class="grid-container">
            <a href="company_details.php"><div class="grid-item" id="item1"><h6>Total Companies Recorded</h6><h2><?php echo $cmp_num_row; ?></h2></div></a>
            <a href="index.php"><div class="grid-item" id="item2"><h6>Total Work Recorded</h6><h2><?php echo $cav_num_row; ?></h2></div></a>
            <a href="index.php?call_filter=&approve_filter=approved&due_filter="><div class="grid-item" id="item3"><h6>Total Work Approved</h6><h2><?php echo $cava_num_row; ?></h2></div></a>
        </div>
        <div class="grid-container">
            <a href="index.php?call_filter=&approve_filter=&due_filter=due_soon_1_month"><div class="grid-item" id="item4"><h6>Total Due in a Month</h6><h2><?php echo $entry_count; ?></h2></div></a>
            <a href="index.php?call_filter=&approve_filter=&due_filter=due_soon_2_months"><div class="grid-item" id="item5"><h6>Total Due in 2 Month</h6><h2><?php echo $entry_count_2_months; ?></h2></div></a>
            <a href="completed-data.php"><div class="grid-item" id="item6"><h6>Total Work Completed</h6><h2><?php echo $completed; ?></h2></div></a>
        </div>
    </div>
    <?php include "footer.html";?>
    <!--  Script for storing theme color  -->
    <script src="themescript.js"></script>
</body>
</html>