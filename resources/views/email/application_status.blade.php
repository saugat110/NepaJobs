<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application Status Email</title>
    <style>
        h3{
            font-weight: 200;
            font-style: normal;
        }
    </style>
</head>
<body>
    <h1 style="font-weight: 200;font-style: normal;">Hi, {{ $uname }}, your job application for, {{ $jobname }}</h3>
    <h1 style="font-weight: 200; font-style: normal;">was {{ $status }}.</h1>
    <br>
    <h1 style="font-weight: 200;font-style: normal;">Thank You.</h4>
</body>
</html>