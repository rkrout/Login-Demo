
<?php
// Function to validate the entered username and password
function validateCredentials($username, $password) {
    // Add your own logic to validate the credentials
    $validUsername = 'admin';
    $validPassword = 'password';

    return ($username === $validUsername && $password === $validPassword);
}

// Check if the user is already authenticated
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    if (validateCredentials($username, $password)) {
        // The user is authenticated, allow access to the page
    } else {
        // Invalid credentials, display an error message
        header('WWW-Authenticate: Basic realm="Restricted Area"');
        header('HTTP/1.0 401 Unauthorized');
        echo "Invalid credentials!";
        exit;
    }
} else {
    // Prompt the user for credentials
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Authentication required!";
    exit;
}


?>