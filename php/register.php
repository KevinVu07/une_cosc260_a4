<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if (validate_request()) {
    // Continue processing the request, since it's valid
    echo "Request is valid. Proceeding to data validation...";
}

function send_error($code, $message) {

    $responses = [
        400 => "Bad Request",
        404 => "Not Found",
        405 => "Method Not Allowed",
        500 => "Internal server error"
    ];

    // Get the server protocol (e.g., HTTP/1.1)
    $protocol = $_SERVER['SERVER_PROTOCOL'];

    // Set the response code in the HTTP header
    header("$protocol $code " . $responses[$code]);

    // Return the error message in the response body
    echo json_encode([
        "error" => $message,
        "code" => $code
    ]);

    // Stop further execution
    exit;
}

function validate_request() {
    // Validate that the request is a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        send_error(405, "Only POST requests are allowed");
    }

    // Validate that POST data is not empty
    if (empty($_POST)) {
        send_error(400, "Request body is empty");
    }

    // Validate that all required fields are present
    $required_fields = ['username', 'fullName', 'dateOfBirth', 'email'];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            send_error(400, "Missing or empty field: $field. All fields need to be entered.");
        }
    }

    // If all validations pass`, return true
    return true;
}
