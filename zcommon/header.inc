<?php
$MAXQUOTA = 50; //set both here and in xcommon.inc,xbanner.inc

//
// if no session assign one
// 365 day session until expiration
//
if ((isset($_COOKIE["SESSION"])) != FALSE)
{
    $SESSION = $_COOKIE['SESSION'];
}
else
{
	$SESSION = $rand = rand(10000,99999);
    setcookie('SESSION',$SESSION,time()+60*60*24*365, '/',"christopherminson.com");
}
setcookie('SESSION',$SESSION,time()+60*60*24*365, '/',"christopherminson.com");

//
// if no user id assign one
// keep user id around a long time
//
if ((isset($_COOKIE["USERID"])) != FALSE)
{
    $USERID = $_COOKIE['USERID'];
	//RecordCommand("_COOKIE SEEN $USERID");
}
else
{
	$rand = rand(1000,9999);
	$USERID = $SESSION."-".$rand;

    setcookie('USERID',$USERID,time()+60*60*24*4000, '/',"christopherminson.com");
	RecordCommand("SET _COOKIE ");
}



// mark xyg prefix code based off of trace hit
$PREFIXCODE = 1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<meta name="apple-mobile-web-app-title" content="christopherminson">
<meta http-equiv=Content-Type content="text/html">
<meta name="GOOGLEBOT" content="NOODP">
<meta name="ROBOTS" content="NOODP">
<meta name="verify-v1" content="SF0/KlCGPRF8f4XY1yQvM0KiJG9MJdeBnv+p5kDbBLs=" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >

<?php
print "<meta HTTP-EQUIV=\"Content-Language\" CONTENT=\"en\">\n";
$WindowTitle = "openimage - world's simplest online image converter";
print "<title>$WindowTitle</title>";
?>


<link rel="shortcut icon" href="./wimages/startup/openimage-appleicon.png">
<link rel="apple-touch-icon" href="./wimages/startup/openimage-appleicon.png">
<link rel="apple-touch-startup-image" href="https://www.christopherminson.com/openimage/wimages/startup/startup320x460.png">
<link rel="apple-touch-startup-image" href="https://www.christopherminson.com/openimage/wimages/startup/startup640x920.png" sizes="640x920">
<link rel="apple-touch-startup-image" href="https://www.christopherminson.com/openimage/wimages/startup/startup640x1096.png" sizes="640x1096">
<link rel="apple-touch-startup-image" href="https://www.christopherminson.com/openimage/wimages/startup/startup750x1294.png"  sizes="750x1294">
<link rel="apple-touch-startup-image" href="https://www.christopherminson.com/openimage/wimages/startup/startup1242x2148.png" sizes="1242x2148">
<link rel="SHORTCUT ICON" href="https://www.christopherminson.com/openimage/openimage.ico">


<link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" "text/css" rel="stylesheet">
<link href="../openimage/zsocial/jssocials.css" "text/css" rel="stylesheet">
<link href="../openimage/zsocial/jssocials-theme-flat.css" "text/css" rel="stylesheet">
<link href="../openimage/zjs/imgareaselect-default.css" "text/css" rel="stylesheet">
<link href="../openimage/zcommon/style1.css"  type="text/css" rel="stylesheet">
<style>
.jssocials-share-reddit .jssocials-share-link {
          background: #64a4ff; }
  .jssocials-share-reddit .jssocials-share-link:hover {
                background: #4585ee; }
</style>


<!--
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->
<script src="https://www.christopherminson.com/openimage/zjs/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://www.christopherminson.com/openimage/zjs/jquery.imgareaselect.pack.js"></script>
<script src="https://www.christopherminson.com/openimage/zjs/trans.js"></script>
<script src="https://www.christopherminson.com/openimage/zsocial/jssocials.min.js"></script>
<script src="https://www.christopherminson.com/openimage/tools/newcolorpick/302pop.js" type="text/javascript"></script>
<script src="https://www.christopherminson.com/openimage/zjs/main.js"></script>


</head>
<body onload="loadInit()">

