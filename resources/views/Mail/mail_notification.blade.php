<!DOCTYPE html>
<html>
<head>
    <title>Mail Notification</title>
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <style type="text/css">
        .box{
            /*border:1px solid black;*/
            margin: 10px;
        }
        .mailBody{
            margin: 10px;
        }
    </style>
</head>
<body>
<center><h2>{{ config('app.name', 'Laravel') }}</h2></center>
<div class="box">
    <div class="mailBody">{!! $request->description !!}</div>
</div>
</body>
</html>
