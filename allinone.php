<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
use ZipArchive;
if (isset($_POST['submit'])) {
    $channel_ids = explode("\n", trim($_POST['channel_ids']));

    $zip = new ZipArchive;
    $zip_filename = "labels/labels.zip";

    if ($zip->open($zip_filename, ZipArchive::CREATE) === true) {
        foreach ($channel_ids as $channel_id) {
            $client = new GuzzleHttp\Client();
            $shipment_id = fetchShipmentId($channel_id, $client);
            $url = generateLabelUrl($shipment_id);
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3MjMwMzUsImlzcyI6Imh0dHBzOi8vYXBpdjIuc2hpcHJvY2tldC5pbi92MS9leHRlcm5hbC9hdXRoL2xvZ2luIiwiaWF0IjoxNjgxNTc4MDc3LCJleHAiOjE2ODI0NDIwNzcsIm5iZiI6MTY4MTU3ODA3NywianRpIjoiN0wyVDNuMkdXVjFzQlBRbSJ9.2KJ3JeL0wvhVllBGWgI8qVIccT-f86aXYFDtpMg3y6w',
                ],
            ]);
            $label_filename = "labels/{$channel_id}.pdf";
            file_put_contents($label_filename, $response->getBody());
            $zip->addFile($label_filename, "{$channel_id}.pdf");
            unlink($label_filename);
        }

        $zip->close();

        header("Content-Type: application/zip");
        header('Content-Disposition: attachment; filename="labels.zip"');
        header('Content-Length: ' . filesize($zip_filename));
        readfile($zip_filename);
        unlink($zip_filename);
        exit;
    } else {
        exit("Unable to create the ZIP file.");
    }
}

function generateLabelUrl($shipment_id) {
    return "https://apiv2.shiprocket.in/v1/external/courier/generate/label?shipment_id={$shipment_id}";
}

function fetchShipmentId($channel_id, $client) {
    $url = "https://apiv2.shiprocket.in/v1/external/orders/?search=" . $channel_id;
    $response = $client->get($url, [
        'headers' => [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3MjMwMzUsImlzcyI6Imh0dHBzOi8vYXBpdjIuc2hpcHJvY2tldC5pbi92MS9leHRlcm5hbC9hdXRoL2xvZ2luIiwiaWF0IjoxNjgxNTc4MDc3LCJleHAiOjE2ODI0NDIwNzcsIm5iZiI6MTY4MTU3ODA3NywianRpIjoiN0wyVDNuMkdXVjFzQlBRbSJ9.2KJ3JeL0wvhVllBGWgI8qVIccT-f86aXYFDtpMg3y6w',
        ],
    ]);

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
