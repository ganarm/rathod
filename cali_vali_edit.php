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

// Check if the form is submitted for updating
if (isset($_POST['submit'])) {
    // Get updated form data
    $type = $_POST['type'];
    $period = $_POST['period'];
    $comp_id = $_POST['comp_id'];
    $weight_class = $_POST['weight_class'];  // Get selected weight class if applicable

    // Append weight class to type if it's not empty
    if (!empty($weight_class)) {
        $type = $type . " " . $weight_class;
    }

    // SQL query to update cali_vali table
    $update_query = "UPDATE cali_vali SET type = '$type', period = '$period' WHERE cv_id = $cv_id";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Record updated successfully'); window.location.href = 'view-data.php?cv_id=$cv_id';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
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
    <title>ASL : C&V Edit, MB : GM</title>
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
        <h2>Edit Calibration Validation</h2>

        <form method="POST" action="" class="form-container" id="cali_vali-form">
            <div class="form-group">
                <label for="comp_id">Select Company:</label>
                <select id="comp_id" name="comp_id" class="form-input" required disabled>
                    <option value="<?php echo $row['comp_id']; ?>"><?php echo $row['company_name']; ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="type">Type of Validation / Calibration:</label>
                <select id="type" name="type" class="form-input" required>
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
                <select id="weight_class" name="weight_class" class="form-input">
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
                <button type="submit" name="submit" class="submit-btn">Update</button>
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
