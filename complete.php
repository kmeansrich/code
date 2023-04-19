<?php
ini_set('max_execution_time', 6000); // 600 seconds = 10 minutes
if (isset($_POST['order_ids'])) {

    $order_ids = array_map('trim', explode(',', $_POST['order_ids']));

    $auth_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3MjMwMzUsImlzcyI6Imh0dHBzOi8vYXBpdjIuc2hpcHJvY2tldC5pbi92MS9leHRlcm5hbC9hdXRoL2xvZ2luIiwiaWF0IjoxNjgxNTc4MDc3LCJleHAiOjE2ODI0NDIwNzcsIm5iZiI6MTY4MTU3ODA3NywianRpIjoiN0wyVDNuMkdXVjFzQlBRbSJ9.2KJ3JeL0wvhVllBGWgI8qVIccT-f86aXYFDtpMg3y6w"; // Replace with your Shiprocket API authorization token
    $show_url = "https://apiv2.shiprocket.in/v1/external/orders/show";
    $label_url = "https://apiv2.shiprocket.in/v1/external/courier/generate/label";

    $zip = new ZipArchive();
    $zip_filename = "shipping_labels.zip";
    if ($zip->open($zip_filename, ZipArchive::CREATE) !== true) {
        exit("Cannot open <$zip_filename>\n");
    }

    foreach ($order_ids as $order_id) {

        $show_url = "https://apiv2.shiprocket.in/v1/external/orders/show/" . $order_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $show_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $auth_token,
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            $error = "Failed to retrieve order details for order $order_id: HTTP $http_code";
            error_log($error);
            echo "Error: $error";
            exit;
        }

        $show_data = json_decode($response, true);

        $shipment_id = $show_data["data"]['shipments']['id'];
        $channel_id = $show_data["data"]['channel_order_id'];


        $label_data = array(
            'shipment_id' => [$shipment_id]
        );

        $label_payload = json_encode($label_data);
        $ch = curl_init();

        curl_setopt_array($ch, array(
          CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/courier/generate/label",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $label_payload,
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3MjMwMzUsImlzcyI6Imh0dHBzOi8vYXBpdjIuc2hpcHJvY2tldC5pbi92MS9leHRlcm5hbC9hdXRoL2xvZ2luIiwiaWF0IjoxNjgxNTU3ODgzLCJleHAiOjE2ODI0MjE4ODMsIm5iZiI6MTY4MTU1Nzg4MywianRpIjoiSXdqS3Qwb2NFUXY3ZHpXbSJ9.2xJzZ9B5I3FWIFvt376xC59Fgud4ZBqoUMBQmxUezcQ",
            "cache-control: no-cache",
            "content-type: application/json",
          ),
        ));
        $label_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            $error = "Failed to generate label for shipment $shipment_id: HTTP $http_code";
            error_log($error);
            echo "Error: $error";
            exit;
        }

        $label_data = json_decode($label_response, true);
        if ($label_data['label_created'] !== 1) {
            $error = "Failed to generate label for shipment $shipment_id: " . json_encode($label_data['not_created']);
            error_log($error);
            echo "Error: $error";
            exit;
        }

        $label_url = $label_data['label_url'];

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
        $pdf_filename = "shipping_label_$channel_id.pdf";

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
    <style>
      form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
      }

      input[type="text"] {
        width: 80%;
        height: 50px;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        padding: 10px;
        margin-bottom: 20px;
      }

      button[type="submit"] {
        background-image: linear-gradient(to right, #fca903, #fc4242);
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      button[type="submit"]:hover {
        background-image: linear-gradient(to right, #fc4242, #fca903);
      }
    </style>
  </head>
  <body>
    <h1 style="text-align: center">Download Shipping Labels</h1>
    <form method="POST">
      <label for="order_ids">Enter order IDs (comma-separated):</label>
      <input type="text" name="order_ids" id="order_ids" required>
      <button type="submit">Download Labels</button>
    </form>
  </body>
</html>
