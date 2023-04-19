<?php
if (isset($_POST['shipment_ids'])) {
    $shipment_ids = array_map('trim', explode(',', $_POST['shipment_ids']));
    sort($shipment_ids); // sort the array in ascending order
    $auth_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3MjMwMzUsImlzcyI6Imh0dHBzOi8vYXBpdjIuc2hpcHJvY2tldC5pbi92MS9leHRlcm5hbC9hdXRoL2xvZ2luIiwiaWF0IjoxNjgxNTc4MDc3LCJleHAiOjE2ODI0NDIwNzcsIm5iZiI6MTY4MTU3ODA3NywianRpIjoiN0wyVDNuMkdXVjFzQlBRbSJ9.2KJ3JeL0wvhVllBGWgI8qVIccT-f86aXYFDtpMg3y6w"; // Replace with your Shiprocket API authorization token
    $url = "https://apiv2.shiprocket.in/v1/external/courier/generate/label";

    $zip = new ZipArchive();
    $zip_filename = 'shipping_labels.zip';
    if ($zip->open($zip_filename, ZipArchive::CREATE) !== true) {
        exit("Cannot open <$zip_filename>\n");
    }

    foreach ($shipment_ids as $shipment_id) {
        $data = array(
            'shipment_id' => [$shipment_id]
        );
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $auth_token,
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            $error = "Failed to generate label for shipment $shipment_id: HTTP $http_code";
            error_log($error);
            echo "Error: $error";
            exit;
        }

        $data = json_decode($response, true);
        if ($data['label_created'] !== 1) {
            $error = "Failed to generate label for shipment $shipment_id: " . json_encode($data['not_created']);
            error_log($error);
            echo "Error: $error";
            exit;
        }

        $label_url = $data['label_url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $label_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $pdf_content = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            $error = "Failed to download label for shipment $shipment_id: HTTP $http_code";
            error_log($error);
            echo "Error: $error";
            exit;
        }

        $pdf_filename = "shipping_label_$shipment_id.pdf";
        $zip->addFromString($pdf_filename, $pdf_content);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename=$zip_filename");
    header('Pragma: no-cache');
    readfile($zip_filename);
    unlink($zip_filename);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Download Shipping Labels</title>
</head>
<body>
    <h1>Download Shipping Labels</h1>
    <form method="POST">
        <label for="shipment_ids">Enter shipment IDs (comma-separated):</label>
        <input type="text" name="shipment_ids" id="shipment_ids" required>
        <button type="submit">Download Labels</button>
         </form>
    </body>
</html>

