<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Near Agent Welcome Email</title>
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
        }
        .header {
            background-color: #6e0e05;
            padding: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content h2 {
            color: #333333;
        }
        .verification-code {
            font-size: 24px;
            color: #d9534f;
            margin: 20px 0;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
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
        Hello, Welcome To Near Agent!
        <p>Thank you for subscribing!</p>
    </div>
    <div class="content">
        <h2>Let's Get Started</h2>
        <p>Someone (hopefully it is you) has signed up for BookSMM.</p>
        <p>For verify your email address, enter this verification code when prompted:</p>
        <div class="verification-code">{{$verified}}</div>
        <p>If you have any questions, feel free to message us at <a href="mailto:info@booksmm.com">info@booksmm.com</a>.</p>
    </div>
    <div class="footer">
        <p>Terms of use | Privacy Policy</p>
        <p>© BookSMM</p>
    </div>
</div>

</body>
</html>
