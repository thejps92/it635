<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCPartCatalog - Update</title>
</head>
<body>
    <h1>Update the PCPartCatalog Database</h1>
    <!-- Update form -->
    <form method="POST">
        <select name="table" id="table" required onchange="showFormFields()">
            <option value="" selected disabled hidden>Select Table</option>
            <option value="manufacturers">Manufacturers</option>
            <option value="retailers">Retailers</option>
            <option value="parts">Parts</option>
        </select>
        <select name="action" id="action" required onchange="showFormFields()">
            <option value="" selected disabled hidden>Select Action</option>
            <option value="insert">Insert</option>
            <option value="delete">Delete</option>
        </select>
        <br><br>
        <div id="formFields" style="display: none;">
        </div>
        <button type="submit">Submit</button>
    </form>

    <!-- JavaScript for dynamically populating the form fields based on the selected table and action -->
    <script>
        // Function that listens for a change in the dropdown
        document.getElementById("action").addEventListener("change", function() {
            showFormFields();
        });

        // Function that populates the form fields
        function showFormFields() {
            // Gets the selected table and action
            var table = document.getElementById("table").value;
            var action = document.getElementById("action").value;
            var formFieldsDiv = document.getElementById("formFields");
            formFieldsDiv.innerHTML = "";

            // Populates the form fields based on the action and table
            if(action === 'insert') {
                if(table === 'manufacturers') {
                    formFieldsDiv.innerHTML += `
                        Manufacturer Name: <input type="text" name="manufacturer_name" required><br><br>
                        Manufacturer Street: <input type="text" name="manufacturer_street" required><br><br>
                        Manufacturer City: <input type="text" name="manufacturer_city" required><br><br>
                        Manufacturer State: <input type="text" name="manufacturer_state" required><br><br>
                        Manufacturer ZIP: <input type="text" name="manufacturer_zip" required><br><br>
                    `;
                } else if(table === 'retailers') {
                    formFieldsDiv.innerHTML += `
                        Retailer Name: <input type="text" name="retailer_name" required><br><br>
                        Retailer Website: <input type="text" name="retailer_website" required><br><br>
                    `;
                } else if(table === 'parts') {
                    formFieldsDiv.innerHTML += `
                        Part Name: <input type="text" name="part_name" required><br><br>
                        Part Type: <input type="text" name="part_type" required><br><br>
                        Part Price: <input type="number" step="0.01" name="part_price" required><br><br>
                        Part Inventory: <input type="number" name="part_inventory" required><br><br>
                        Manufacturer ID: <input type="number" name="manufacturer_id" required><br><br>
                        Retailer ID: <input type="number" name="retailer_id" required><br><br>
                    `;
                }
            } else if(action === 'delete') {
                if(table === 'manufacturers') {
                    formFieldsDiv.innerHTML += `
                        Manufacturer ID: <input type="number" name="primaryKey" required><br><br>
                    `;
                } else if(table === 'retailers') {
                    formFieldsDiv.innerHTML += `
                        Retailer ID: <input type="number" name="primaryKey" required><br><br>
                    `;
                } else if(table === 'parts') {
                    formFieldsDiv.innerHTML += `
                        Part ID: <input type="number" name="primaryKey" required><br><br>
                    `;
                }
            }
            formFieldsDiv.style.display = "block";
        }
    </script>

    <!-- PHP for interfacing with the PostgreSQL database -->
    <?php
        // Database connection parameters
        $host = 'db';
        $port = '5432';
        $dbname = 'pcpartcatalog_db';
        $user = 'pcpartcatalog_user';
        $password = 'P@ssword123';
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
        
        // Checks if the table and action are set in the POST request
        if(isset($_POST['table']) && isset($_POST['action'])) {
            try {
                // Retrieves table and action from the POST request
                $table = $_POST['table'];
                $action = $_POST['action'];

                // Defines the SQL query based on the table and action
                if($action === 'insert') {
                    if($table === 'manufacturers') {
                        $stmt = $conn->prepare("INSERT INTO manufacturers (manufacturer_name, manufacturer_street, manufacturer_city, manufacturer_state, manufacturer_zip) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$_POST['manufacturer_name'], $_POST['manufacturer_street'], $_POST['manufacturer_city'], $_POST['manufacturer_state'], $_POST['manufacturer_zip']]);
                    } else if($table === 'retailers') {
                        $stmt = $conn->prepare("INSERT INTO retailers (retailer_name, retailer_website) VALUES (?, ?)");
                        $stmt->execute([$_POST['retailer_name'], $_POST['retailer_website']]);
                    } else if($table === 'parts') {
                        $stmt = $conn->prepare("INSERT INTO parts (part_name, part_type, part_price, part_inventory, manufacturer_id, retailer_id) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$_POST['part_name'], $_POST['part_type'], $_POST['part_price'], $_POST['part_inventory'], $_POST['manufacturer_id'], $_POST['retailer_id']]);
                    }
                    echo '<script>alert("Data inserted successfully.");</script>';
                } else if($action === 'delete') {
                    $primaryKey = $_POST['primaryKey'];
                    if($table === 'manufacturers') {
                        $stmt = $conn->prepare("DELETE FROM manufacturers WHERE manufacturer_id = ?");
                    } else if($table === 'retailers') {
                        $stmt = $conn->prepare("DELETE FROM retailers WHERE retailer_id = ?");
                    } else if($table === 'parts') {
                        $stmt = $conn->prepare("DELETE FROM parts WHERE part_id = ?");
                    }
                    $stmt->execute([$primaryKey]);
                    echo '<script>alert("Data deleted successfully.");</script>';
                }
            } catch(Exception $e) {
                $error = $e->getMessage();
                echo '<script>alert(' . json_encode($error) . ');</script>';
            }
        }
    ?>
    
    <footer>
        <p>Go Home -> <a href="index.php">Home</a></p>
    </footer>
</body>
</html>