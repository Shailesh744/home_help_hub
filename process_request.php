<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $selectedService = $_POST["selectedService"];
    $userName = $_POST["userName"];
    $userEmail = $_POST["userEmail"];
    $userContact = $_POST["userContact"];

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "home_help_hub";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into the service_requests table using prepared statement
    $stmt = $conn->prepare("INSERT INTO service_requests (service, userName, userEmail, userContact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $selectedService, $userName, $userEmail, $userContact);

    if ($stmt->execute()) {
        // Data inserted successfully
        echo '<script>alert("Thanks for booking. Our service provider will contact you soon.")</script>';
    } else {
        // Handle the error
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();

    // Close the connection
    $conn->close();

    // Sending an email notification
    $to = "nk8122357@gmail.com";
    $subject = "Service Request for $selectedService";
    $message = "Name: $userName\nEmail: $userEmail\nContact Number: $userContact";
    mail($to, $subject, $message);

    // Send a JSON response if needed
    echo json_encode(["success" => true]);
} else {
    // Redirect or handle the error
    header("Location: project.html"); // Replace with the appropriate URL
    exit();
}
?>
