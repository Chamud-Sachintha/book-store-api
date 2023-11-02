<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Auth Code</title>
</head>

<body>
    <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
        <div style="margin:50px auto;width:70%;padding:20px 0">
            <div style="border-bottom:1px solid #eee">
                <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">dpuremaths</a>
            </div>
            <p style="font-size:1.1em">Hi,</p>
            <p>We received a request to reset your dpuremaths password. Please use the One-Time Password (OTP) below to continue the process.</p>
            <h3>Your OTP :</h3>
            <h2
                style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">
                {{ $details['body'] }}</h2>
            <p>If you didn’t request a password reset, please disregard this email. Thank you for choosing dpuremaths.</p>
            <p style="font-size:0.9em;">Best regards,<br />DPuremaths Team</p>
            <hr style="border:none;border-top:1px solid #eee" />
            {{-- <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
                <p>Your Brand Inc</p>
                <p>1600 Amphitheatre Parkway</p>
                <p>California</p>
            </div> --}}
        </div>
    </div>
</body>

</html>