<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Date</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            background-color: #6e0e05;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            font-size: 24px;
        }

        .content {
            padding: 20px;
            color: #333333;
        }

        .content p {
            text-align: left;
            margin-bottom: 10px;
        }

        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .footer a {
            color: #777;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <p>Appointment</p>
        </div>
        <div class="content">
            <p><b>Hello Mr. {{ $recieverName }}</b></p>
            <p>Thank you for your time</p>
            <p>You have a date on {{ $date }} regarding the ( {{ $realtyName }} ) Realty.</p>
            <p>Thank you,</p>
            <p><b>{{ $senderName }}</b></p>
        </div>
        <div class="footer">
            <p>Terms of Use | Privacy Policy</p>
            <p>© Near Agent</p>
        </div>
    </div>

</body>

</html>
