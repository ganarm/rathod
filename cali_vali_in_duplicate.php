<?php
// Include the database connection file
include('db_connection.php');

// Check if cv_id is provided for editing
if (isset($_GET['cv_id'])) {
    $cv_id = $_GET['cv_id'];

    // Fetch the existing data for the selected cv_id
    $query = "SELECT c.comp_id, c.name AS company_name, cv.type, cv.period, cv.cv_id, DATE_ADD(cv.done_date, INTERVAL cv.period MONTH) AS due_date
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

    // Separate the weight class if it exists in the type field
    $weight_class = '';
    $type = $row['type'];
    if (preg_match('/(.*) (E[12]|F1|M1) class$/', $type, $matches)) {
        $type = $matches[1]; // e.g., "NABL calibration of weighing balance - Weights"
        $weight_class = $matches[2] . ' class'; // e.g., "E1 class"
    }
}

// Check if the form is submitted for newly adding duplicate data
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

    // Check if an entry for the same company and type already exists with completed_flag = 0 in the maintenance table
    $sql_check = "SELECT m.cv_id FROM maintaince m
                  JOIN cali_vali cv ON m.cv_id = cv.cv_id
                  WHERE cv.comp_id = '$comp_id' AND cv.type = '$type' AND m.completed_flag = 0";

    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('An entry for this company and type with completed_flag = 0 already exists.');window.location.href = 'index.php';;</script>";
    } else {
        // If no such entry exists, check if there's an entry with completed_flag = 1
        $sql_check_completed = "SELECT m.cv_id FROM maintaince m
                                JOIN cali_vali cv ON m.cv_id = cv.cv_id
                                WHERE cv.comp_id = '$comp_id' AND cv.type = '$type' AND m.completed_flag = 1";
        
        $result_check_completed = $conn->query($sql_check_completed);
        
        if ($result_check_completed->num_rows > 0) {
            // If there is an entry with completed_flag = 1, insert a similar entry
            $sql_cali_vali = "INSERT INTO cali_vali (type, period, comp_id) VALUES ('$type', '$period', '$comp_id')";
            
            if ($conn->query($sql_cali_vali) === TRUE) {
                // Get the cv_id of the last inserted record in cali_vali
                $cv_id = $conn->insert_id;

                // Insert corresponding entry into maintenance table with default flag values
                $sql_maintenance = "INSERT INTO maintaince (cv_id, due_flag, call_flag, approved_flag, completed_flag) 
                                    VALUES ('$cv_id', 0, 0, 0, 0)";

                if ($conn->query($sql_maintenance) === TRUE) {
                    echo "<script>alert('New record created successfully in cali_vali and maintenance'); window.location.href = 'index.php';</script>";
                } else {
                    echo "<script>alert('Error inserting into maintenance: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Error inserting into cali_vali: " . $conn->error . "');</script>";
            }
        } else {
            // If no completed_flag = 1 entry exists, simply insert a new record into cali_vali and maintenance
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
}


// Close the database connection
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
    <title>ASL : Add, MB : GM</title>
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

        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .form-group label, .form-input {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <?php include "nav-w.html"; ?>
    <div class="container">
        <h2>Add Again Calibration Validation</h2>

        <form method="POST" action="cali_vali_in_duplicate.php" class="form-container" id="cali_vali-form">
            <div class="form-group">
                <label for="comp_id">Select Company:</label>
                <select id="comp_id" name="comp_id1" class="form-input" required disabled>
                    <option value="<?php echo $row['comp_id']; ?>"><?php echo $row['company_name']; ?></option>
                </select>
                <select id="comp_id" name="comp_id" class="form-input" required hidden>
                    <option value="<?php echo $row['comp_id']; ?>"><?php echo $row['company_name']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="type">Type of Validation / Calibration:</label>
                <select id="type" name="type1" class="form-input" required disabled>
                    <option value="Calibration" <?php echo ($type == 'Calibration') ? 'selected' : ''; ?>>Calibration</option>
                    <option value="Validation" <?php echo ($type == 'Validation') ? 'selected' : ''; ?>>Validation</option>
                    <option value="Area validation" <?php echo ($type == 'Area validation') ? 'selected' : ''; ?>>Area validation</option>
                    <option value="Temperature mapping of equipment" <?php echo ($type == 'Temperature mapping of equipment') ? 'selected' : ''; ?>>Temperature mapping of equipment</option>
                    <option value="HVAC validation" <?php echo ($type == 'HVAC validation') ? 'selected' : ''; ?>>HVAC validation</option>
                    <option value="CS Validation" <?php echo ($type == 'CS Validation') ? 'selected' : ''; ?>>CS Validation</option>
                    <option value="Compressed air testing" <?php echo ($type == 'Compressed air testing') ? 'selected' : ''; ?>>Compressed air testing</option>
                    <option value="Nitrogen testing" <?php echo ($type == 'Nitrogen testing') ? 'selected' : ''; ?>>Nitrogen testing</option>
                    <option value="Pure steam qualification" <?php echo ($type == 'Pure steam qualification') ? 'selected' : ''; ?>>Pure steam qualification</option>
                    <option value="NABL calibration of weighing balance - Weights" <?php echo ($type == 'NABL calibration of weighing balance - Weights') ? 'selected' : ''; ?>>NABL calibration of weighing balance - Weights</option>
                </select>
                <select id="type" name="type" class="form-input" required hidden>
                    <option value="Calibration" <?php echo ($type == 'Calibration') ? 'selected' : ''; ?>>Calibration</option>
                    <option value="Validation" <?php echo ($type == 'Validation') ? 'selected' : ''; ?>>Validation</option>
                    <option value="Area validation" <?php echo ($type == 'Area validation') ? 'selected' : ''; ?>>Area validation</option>
                    <option value="Temperature mapping of equipment" <?php echo ($type == 'Temperature mapping of equipment') ? 'selected' : ''; ?>>Temperature mapping of equipment</option>
                    <option value="HVAC validation" <?php echo ($type == 'HVAC validation') ? 'selected' : ''; ?>>HVAC validation</option>
                    <option value="CS Validation" <?php echo ($type == 'CS Validation') ? 'selected' : ''; ?>>CS Validation</option>
                    <option value="Compressed air testing" <?php echo ($type == 'Compressed air testing') ? 'selected' : ''; ?>>Compressed air testing</option>
                    <option value="Nitrogen testing" <?php echo ($type == 'Nitrogen testing') ? 'selected' : ''; ?>>Nitrogen testing</option>
                    <option value="Pure steam qualification" <?php echo ($type == 'Pure steam qualification') ? 'selected' : ''; ?>>Pure steam qualification</option>
                    <option value="NABL calibration of weighing balance - Weights" <?php echo ($type == 'NABL calibration of weighing balance - Weights') ? 'selected' : ''; ?>>NABL calibration of weighing balance - Weights</option>
                </select>
            </div>

            <div class="form-group <?php echo ($type == 'NABL calibration of weighing balance - Weights') ? '' : 'hidden'; ?>" id="weight-class-group">
                <label for="weight_class">Select Weight Class:</label>
                <select id="weight_class" name="weight_class1" class="form-input" disabled>
                    <option value="">Select Weight Class</option>
                    <option value="E1 class" <?php echo ($weight_class == 'E1 class') ? 'selected' : ''; ?>>E1 class</option>
                    <option value="E2 class" <?php echo ($weight_class == 'E2 class') ? 'selected' : ''; ?>>E2 class</option>
                    <option value="F1 class" <?php echo ($weight_class == 'F1 class') ? 'selected' : ''; ?>>F1 class</option>
                    <option value="M1 class" <?php echo ($weight_class == 'M1 class') ? 'selected' : ''; ?>>M1 class</option>
                </select>
                <select id="weight_class" name="weight_class" class="form-input" hiiden>
                    <option value="">Select Weight Class</option>
                    <option value="E1 class" <?php echo ($weight_class == 'E1 class') ? 'selected' : ''; ?>>E1 class</option>
                    <option value="E2 class" <?php echo ($weight_class == 'E2 class') ? 'selected' : ''; ?>>E2 class</option>
                    <option value="F1 class" <?php echo ($weight_class == 'F1 class') ? 'selected' : ''; ?>>F1 class</option>
                    <option value="M1 class" <?php echo ($weight_class == 'M1 class') ? 'selected' : ''; ?>>M1 class</option>
                </select>
            </div>

            <div class="form-group">
                <label for="period">Select Period:</label>
                <div class="radio-group">
                    <label><input type="radio" name="period" value="1" <?php echo ($row['period'] == 1) ? 'checked' : ''; ?>> 1 Month</label>
                    <label><input type="radio" name="period" value="3" <?php echo ($row['period'] == 3) ? 'checked' : ''; ?>> 3 Months</label>
                    <label><input type="radio" name="period" value="6" <?php echo ($row['period'] == 6) ? 'checked' : ''; ?>> 6 Months</label>
                    <label><input type="radio" name="period" value="12" <?php echo ($row['period'] == 12) ? 'checked' : ''; ?>> 12 Months</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="submit-btn">Add Again</button>
            </div>
        </form>
    </div>

    <?php include "footer.html"; ?>
    <script src="themescript.js"></script>
    <script>
        document.getElementById('type').addEventListener('change', function() {
            const weightClassGroup = document.getElementById('weight-class-group');
            if (this.value === 'NABL calibration of weighing balance - Weights') {
                weightClassGroup.classList.remove('hidden');
            } else {
                weightClassGroup.classList.add('hidden');
            }
        });
    </script>
</body>
</html>