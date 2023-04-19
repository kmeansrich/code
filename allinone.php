<?php
require 'vendor/autoload.php';

// Set error reporting level to display all errors and warnings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Extract the channel IDs from the form data
    $channel_ids = explode("\n", trim($_POST['channel_ids']));

    // Create the labels directory if it doesn't exist
    if (!is_dir("labels")) {
        if (!mkdir("labels", 0777)) {
            exit("Unable to create the labels directory.");
        }
    }

    // Initialize an empty array to store any errors
    $errors = [];

    // Initialize a new ZipArchive object to create the zip file
    $zip = new ZipArchive;
    $zip_filename = "labels/labels.zip";

    // Open the zip file and add the label files to it
    if ($zip->open($zip_filename, ZipArchive::CREATE) !== true) {
        $errors[] = "Unable to create the ZIP file.";
    } else {
        foreach ($channel_ids as $channel_id) {
            // Generate the label URL and download the label file
            $client = new GuzzleHttp\Client();
            $shipment_id = fetchShipmentId($channel_id, $client);
            $url = generateLabelUrl($shipment_id);
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3MjMwMzUsImlzcyI6Imh0dHBzOi8vYXBpdjIuc2hpcHJvY2tldC5pbi92MS9leHRlcm5hbC9hdXRoL2xvZ2luIiwiaWF0IjoxNjgxNTc4MDc3LCJleHAiOjE2ODI0NDIwNzcsIm5iZiI6MTY4MTU3ODA3NywianRpIjoiN0wyVDNuMkdXVjFzQlBRbSJ9.2KJ3JeL0wvhVllBGWgI8qVIccT-f86aXYFDtpMg3y6w',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $label_filename = "labels/{$channel_id}.pdf";
                if (file_put_contents($label_filename, $response->getBody()) === false) {
                    $errors[] = "Unable to save the label file for channel ID '{$channel_id}'.";
                } else {
                    // Add the label file to the zip file
                    if (!$zip->addFile($label_filename, "{$channel_id}.pdf")) {
                        $errors[] = "Unable to add the label file for channel ID '{$channel_id}' to the ZIP file.";
                    }

                    // Delete the downloaded label file from disk
                    unlink($label_filename);
                }
            } else {
    $errors[] = "Unable to create the ZIP file.";
}

// Close the zip file
$zip->close();

// Check if there are any errors and display them
if (count($errors) > 0) {
    // Output any errors
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>{$error}</li>";
    }
    echo "</ul>";
} else {
    // Set the headers to initiate a file download of the zip file
    header("Content-Type: application/zip");
    header('Content-Disposition: attachment; filename="labels.zip"');
    header('Content-Length: ' . filesize($zip_filename));

    // Output the contents of the zip file to the browser using a temporary file
    $tmpFile = fopen($zip_filename, 'rb');
    fpassthru($tmpFile);
    fclose($tmpFile);

    // Delete the zip file from disk
    unlink($zip_filename);
    exit;
}


        }
    }
}

// Function to generate the label URL for a given shipment ID
function generateLabelUrl($shipment_id) {
    return "https://apiv2.shiprocket.in/v1/external/courier/generate/label?shipment_id={$shipment_id}";
}

// Function to fetch the shipment ID for a given channel ID
function fetchShipmentId($channel_id, $client) {
// Generate the URL to fetch the order details for the given channel ID
$url = "https://apiv2.shiprocket.in/v1/external/orders/?search=" . $channel_id;
// Send a GET request to the ShipRocket API to fetch the order details
$response = $client->get($url, [
    'headers' => [
        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3MjMwMzUsImlzcyI6Imh0dHBzOi8vYXBpdjIuc2hpcHJvY2tldC5pbi92MS9leHRlcm5hbC9hdXRoL2xvZ2luIiwiaWF0IjoxNjgxNTc4MDc3LCJleHAiOjE2ODI0NDIwNzcsIm5iZiI6MTY4MTU3ODA3NywianRpIjoiN0wyVDNuMkdXVjFzQlBRbSJ9.2KJ3JeL0wvhVllBGWgI8qVIccT-f86aXYFDtpMg3y6w',
    ],
]);

// Decode the JSON response and extract the shipment ID for the first shipment in the order
$data = json_decode($response->getBody(), true);
if (!empty($data[0]["shipments"])) {
    return $data[0]["shipments"][0]["id"];
} else {
    return null; // or return a default value as per your requirement
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Generator</title>
</head>
<body>
    <form method="POST">
        <label for="channel_ids">Enter Channel IDs (one per line):</label>
        <br>
        <textarea id="channel_ids" name="channel_ids" rows="10" cols="30"></textarea>
        <br>
        <button type="submit" name="submit">Download Labels</button>
    </form>
</body>
</html>
