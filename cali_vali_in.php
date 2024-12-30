<?php
// Include the database connection file
include('db_connection.php');

$sql_companies = "SELECT comp_id, name FROM company"; // Adjust the table name and column names accordingly
$companies = $conn->query($sql_companies);

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $type = $_POST['type'];
    $period = $_POST['period'];
    $comp_id = $_POST['comp_id'];
    $weight_class = $_POST['weight_class'];  // Get selected weight class if applicable

    // Append weight class to type if it's not empty
    if (!empty($weight_class)) {
        $type = $type . " " . $weight_class;
    }

    // Check if an entry for the same company and type already exists
    $sql_check = "SELECT * FROM cali_vali WHERE comp_id='$comp_id' AND type='$type'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('An entry for this company and type already exists.');</script>";
    } else {
        // SQL to insert data into cali_vali table
        $sql_cali_vali = "INSERT INTO cali_vali (type, period, comp_id) VALUES ('$type', '$period', '$comp_id')";

        if ($conn->query($sql_cali_vali) === TRUE) {
            // Get the cv_id of the last inserted record in cali_vali
            $cv_id = $conn->insert_id;

            // Insert corresponding entry into maintenance table with default flag values
            $sql_maintenance = "INSERT INTO maintaince (cv_id, due_flag, call_flag, approved_flag, completed_flag) 
                                VALUES ('$cv_id', 0, 0, 0, 0)";

            if ($conn->query($sql_maintenance) === TRUE) {
                echo "<script>alert('New record created successfully in cali_vali and maintenance');</script>";
            } else {
                echo "<script>alert('Error inserting into maintenance: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error inserting into cali_vali: " . $conn->error . "');</script>";
        }
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
    <title>ASL : C&V IN, MB : GM</title>
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

        .radio-group {
            display: flex;
            flex-direction: row;
            gap: 10px;
        }

        .hidden {
            display: none;
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
    <?php include "nav-w.html"; ?>
    <div class="container">
        <a href="index.php" style="float:right;text-decoration:none;"class="submit-btn">View Data</a>
        <h2>Company Data Registration Form</h2>

        <form method="POST" action="" class="form-container" id="company-form">
            <div class="form-group">
                <label for="comp_id">Select Company:</label>
                <select id="comp_id" name="comp_id" class="form-input" onchange="showTypeOptions()" required>
                    <option value="">Select Company</option>
                    <?php
                    if ($companies->num_rows > 0) {
                        // Output data of each company
                        while ($row = $companies->fetch_assoc()) {
                            echo "<option value='" . $row['comp_id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" id="type-group" style="display: none;">
                <label for="type">Type of Validation / Calibration:</label>
                <select id="type" name="type" class="form-input" onchange="showWeightOptions()" required>
                    <option value="">Select Type</option>
                    <option value="Calibration">Calibration</option>
                    <option value="Validation">Validation</option>
                    <option value="Area validation">Area validation</option>
                    <option value="Temperature mapping of equipment">Temperature mapping of equipment</option>
                    <option value="HVAC validation">HVAC validation</option>
                    <option value="CS Validation">CS Validation</option>
                    <option value="Compressed air testing">Compressed air testing</option>
                    <option value="Nitrogen testing">Nitrogen testing</option>
                    <option value="Pure steam qualification">Pure steam qualification</option>
                    <option value="NABL calibration of weighing balance - Weights">NABL calibration of weighing balance - Weights</option>
                </select>
            </div>

            <div class="form-group hidden" id="weight-class-group">
                <label for="weight_class">Select Weight Class:</label>
                <select id="weight_class" name="weight_class" class="form-input">
                    <option value="">Select Weight Class</option>
                    <option value="E1 class">E1 class</option>
                    <option value="E2 class">E2 class</option>
                    <option value="F1 class">F1 class</option>
                    <option value="M1 class">M1 class</option>
                </select>
            </div>

            <div class="form-group">
                <label for="period">Select Period:</label>
                <div class="radio-group">
                    <label><input type="radio" name="period" value="1" required> 1 Month</label>
                    <label><input type="radio" name="period" value="3" required> 3 Months</label>
                    <label><input type="radio" name="period" value="6" required> 6 Months</label>
                    <label><input type="radio" name="period" value="12" required checked> 12 Months</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="submit-btn">Submit</button>
            </div>
        </form>
    </div>

    <?php include "footer.html"; ?>
    <script src="themescript.js"></script>
    <script>
        // Function to display type options when a company is selected
        function showTypeOptions() {
            const comp_id = document.getElementById("comp_id").value;
            const typeGroup = document.getElementById("type-group");

            // Show the type selection once a company is selected
            if (comp_id) {
                typeGroup.style.display = "block";
            } else {
                typeGroup.style.display = "none";
            }

            // Hide the weight class selection by default
            const weightClassGroup = document.getElementById("weight-class-group");
            weightClassGroup.classList.add('hidden');
        }

        // Function to show weight class options when "NABL calibration of weighing balance" is selected
        function showWeightOptions() {
            const type = document.getElementById("type").value;
            const weightClassGroup = document.getElementById("weight-class-group");

            // Show the weight class selection only if "NABL calibration of weighing balance" is selected
            if (type === "NABL calibration of weighing balance - Weights") {
                weightClassGroup.classList.remove('hidden');
            } else {
                weightClassGroup.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
