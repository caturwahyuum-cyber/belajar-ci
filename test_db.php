<?php
// Simple direct test without needing full CI bootstrap
$config_content = file_get_contents('app/Config/Database.php');
echo "Config file size: " . strlen($config_content) . " bytes\n\n";

// Check if it has the correct values
$has_root = strpos($config_content, "'username'     => 'root'") !== false;
$has_db_pwl = strpos($config_content, "'database'     => 'db_pwl'") !== false;

echo "Config has username = 'root': " . ($has_root ? "YES" : "NO") . "\n";
echo "Config has database = 'db_pwl': " . ($has_db_pwl ? "YES" : "NO") . "\n\n";

echo "Checking MySQL connection directly...\n";
$conn = @mysqli_connect('localhost', 'root', '', 'db_pwl');
if ($conn) {
    echo "SUCCESS: Connected to database!\n";

    // Check if users table exists
    $result = mysqli_query($conn, "SELECT * FROM users LIMIT 1");
    if ($result) {
        echo "users table exists and is accessible\n";
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            echo "Sample user record found: " . print_r($row, true) . "\n";
        } else {
            echo "users table is empty\n";
        }
    } else {
        echo "ERROR querying users table: " . mysqli_error($conn) . "\n";
    }
    mysqli_close($conn);
} else {
    echo "FAILED to connect: " . mysqli_connect_error() . "\n";
}
