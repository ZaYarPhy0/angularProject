<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
</head>

<body>
    <div class="container">
        <p style="margin-bottom: 0px;">Dear {{ $user->name }}, </p> <br>
        <p style="margin-bottom: 20px;">We have received a request to reset your password for your account. If you did
            not
            initiate
            this request, please disregard this email.</p>
        <p>To reset your password, visit the password reset page by clicking on the following link:</p>
        <a href="{{ $frontend_url }}/auth/password-reset/{{ $token }}" style="color:blue;">Password Reset
            Link</a>

        <p style="margin-top:40px;">Thanks & Best regards,<br><strong>R2O Employee Loan Web</strong></p>
    </div>
</body>

</html>
