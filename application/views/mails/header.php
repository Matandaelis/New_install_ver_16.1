<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Centers the email-container in the viewport */
            height: 100vh; /* Use the full height of the screen */
        }
        .email-container {
            width: 600px;
            background-color: #ffffff;
            border-collapse: collapse;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: auto;
            margin-bottom: auto; /* Margin auto for top and bottom will center align the table vertically */
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: none; /* Remove individual borders */
        }
        .body {
            padding: 20px;
            text-align: left;
            border-bottom: none; /* Remove individual borders */
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: #fff;
            border-top: none; /* Remove individual borders */
        }
        .unsubscribe-text {
            color: #fff; /* Change color to white */
            padding: 20px;
            text-align: center;
        }
        .header img {
            width: 144px;
        }
    </style>
</head>
<body>
    <table class="email-container">
<!-- Header Section -->
<tr>
    <td class="header">
        <!-- Company Name and Logo -->
        <h1><?= $site_name; ?></h1>
        <a href="<?= $base_url; ?>" target="_blank">
            <img src="<?= $logo_url; ?>" alt="Company Logo" border="0">
        </a>
    </td>
</tr>