<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCPartCatalog - Search</title>
</head>
<body>
    <h1>Search the PCPartCatalog Database</h1>
    <!-- Search form -->
    <form method="GET">
        <select name="table" id="table" required>
            <option value="" selected disabled hidden>Select Table</option>
            <option value="manufacturers">Manufacturers</option>
            <option value="retailers">Retailers</option>
            <option value="parts">Parts</option>
        </select>
        <select name="criteria" id="criteria" required>
            <option value="" selected disabled hidden>Select Criteria</option>
        </select>
        <input type="text" name="query" placeholder="Enter search query" required>
        <button type="submit">Submit</button>
    </form>

    <!-- JavaScript for dynamically populating the criteria dropdown based on the selected table -->
    <script>
        // Function that listens for a change in the dropdown
        document.getElementById("table").addEventListener("change", function() {
            // Function initialization
            var table = this.value;
            var criteriaDropdown = document.getElementById("criteria");
            criteriaDropdown.innerHTML = "";
            criteriaDropdown.disabled = false;

            // Creates and appends a default option to the criteria dropdown
            var defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select Criteria";
            defaultOption.selected = true;
            defaultOption.disabled = true;
            defaultOption.hidden = true;
            criteriaDropdown.appendChild(defaultOption);

            // Defines the criteria options for each table
            var criteriaOptions = {};
            criteriaOptions["manufacturers"] = ["manufacturer_id", "manufacturer_name"];
            criteriaOptions["retailers"] = ["retailer_id", "retailer_name"];
            criteriaOptions["parts"] = ["part_id", "part_name", "part_type", "manufacturer_name"];

            // Populates the criteria dropdown with options based on the selected table
            criteriaOptions[table].forEach(function(option) {
                var formattedOption = option.replace(/_/g, ' ');
                formattedOption = formattedOption.split(' ').map(function(word) {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');

                if(formattedOption.toLowerCase().endsWith('id')) {
                    formattedOption = formattedOption.slice(0, -2) + 'ID';
                }

                var optionElement = document.createElement("option");
                optionElement.value = option;
                optionElement.textContent = formattedOption;
                criteriaDropdown.appendChild(optionElement);
            });
        });
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

        // Checks if the table, criteria, and query parameters are set in the GET request
        if(isset($_GET['table']) && isset($_GET['criteria']) && isset($_GET['query'])) {
            try {
                // Retrieves the table, criteria, and query from the GET request
                $table = $_GET['table'];
                $criteria = $_GET['criteria'];
                $query = $_GET['query'];

                // Defines the SQL query based on the table and criteria
                $sql = "";
                if($table === 'parts') {
                    if($criteria === 'part_name') {
                        $sql = "SELECT * FROM $table WHERE $criteria ILIKE ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindValue(1, '%' . $query . '%', PDO::PARAM_STR);
                    } else if($criteria === 'manufacturer_name') {
                        $sql = "SELECT p.* FROM parts p JOIN manufacturers m ON p.manufacturer_id = m.manufacturer_id WHERE m.manufacturer_name = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindValue(1, $query, PDO::PARAM_STR);
                    } else {
                        $sql = "SELECT * FROM $table WHERE $criteria = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindValue(1, $query, PDO::PARAM_STR);
                    }
                } else {
                    $sql = "SELECT * FROM $table WHERE $criteria = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(1, $query, PDO::PARAM_STR);
                }
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Displays the search results from the results of the SQL query
                if($results) {
                    echo '<ul>';
                    foreach($results as $result) {
                        echo '<li>';
                        foreach($result as $key => $value) {
                            $formatted_key = str_replace('_', ' ', $key);
                            $formatted_key = ucwords($formatted_key);
                            
                            if(strcasecmp(substr($formatted_key, -2), 'id') === 0) {
                                $formatted_key = substr($formatted_key, 0, -2) . 'ID';
                            }
                            
                            echo '<strong>' . $formatted_key . '</strong>: ' . $value . '<br>';
                        }
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No results found.</p>';
                }
            } catch(Exception $e) {
                echo '<script>alert("There was an error with your input. Please try again.");</script>';
            }
        }
    ?>

    <footer>
        <p>Go Home -> <a href="index.php">Home</a></p>
    </footer>
</body>
</html>