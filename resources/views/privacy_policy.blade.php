<!DOCTYPE html>
<html>
<head>
    <title>Privacy Policy</title>
    <style type="text/css">
        body{
            font-family: Gill Sans Extrabold, sans-serif;
            text-align: justify;
        }
        .marquee{
        	background: #aaa;
		    padding: 1%;
		    font-weight: bold;
		    color: #fff;
        }
    </style>
</head>
<body>
  <div style="float:right;margin-bottom:1rem;">
    	<a href="{{url('Refund-Policy')}}" style="border-radius:1px;color:#fff;background:blue;padding:10px 5px;text-decoration:none;">Refund Policy</a>
  </div>
<marquee class="marquee" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">Dear User, Please follow the <a href="{{url('guidlines')}}">LINK</a> for <a href="{{url('guidlines')}}">guidlines</a> on Re-Installation Process of SmartRankers Mobile Application. Sorry for the inconvience</marquee>
<center><img src="{{asset('No_image_available.png')}}" width="200px"></center>
<h1 style="text-align: center;">{{$PrivacyPolicy->title}}</h1>
<div style="margin-left: 40px;margin-right: 40px;">
    {!! $PrivacyPolicy->content !!}
</div>
</body>
</html>