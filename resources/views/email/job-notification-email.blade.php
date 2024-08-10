<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alu</title>
    <style>
        *{
            font-family: Google Sans;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <p style="font-size:23px;">Hi {{ $mailData['employer'] -> name}}, {{ $mailData['employee']->name }} just
        applied for a job u posted.
    </p>
    <p></p>
    <p style="font-size:17px;"">Job Title: {{ $mailData['job'] -> title }}</p>
    <p>Employee Details:</p>
    <p>Name: {{ $mailData['employee'] -> name  }}</p>
    <p>Email: {{ $mailData['employee'] -> email  }}</p>
    <p>Phone: {{ $mailData['employee'] -> mobile  }}</p>

</body>
</html>