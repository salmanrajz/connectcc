<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name', 'Dialup IT Services') }}</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
<p>Customer Name: {{$details['CustomerName']}}</p>
<p>Customer Email: {{$details['CustomerEmail']}}</p>
<p>Customer Number: {{$details['CustomerNumber']}}</p>
<p>Customer Alternative Number: {{$details['AlternativeNumber']}}</p>
<p>Customer Plan Description: {{$details['PlanDescription']}}</p>
<p>Customer Area: {{$details['area']}}</p>

    <p>Thank you</p>
</body>
</html>
