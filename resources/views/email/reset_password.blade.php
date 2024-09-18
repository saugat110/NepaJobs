<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>
<body>
    <h2>Hello, {{ $mailData['user'] -> name }}</h2>
    <p>To change your password, click the link below</p>

    <a href="{{ route('resetPassword', ['token' => $mailData['token'], 'email'=>$mailData['email']] ) }}">Click Me</a>
</body>
</html>