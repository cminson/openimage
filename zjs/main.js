
var BASE_PATH="https://www.christopherminson.com/openimage/";

var ListImageURLS = [];
var ListImageStats = [];

var CurrentPosition = 0
var PreviousPosition = 0
var CurrentOp = "";
var MaxPosition =  0;
var HomePosition = 0;
var MAXIMAGES = 100;
var BaseDivId = "imagediv";
var BaseImageId = "image";
var BaseStatusId = "imagestatus";
var OpImage;
var LoadDisplayed = 0;
var	SNDisplayed = 0;
var	BusyDisplayed = 0;

var Ias = null;
var ImageAreaSelected = false;


// the dimensions of the currently displayed image
// these two variables are used to scale image picks
var CurrentImageWidth = 1;
var CurrentImageHeight = 1;


//
// invoked via onload <body> tag
// hide the camera button if browser isn't HTML5 compliant for media devices
//
function loadInit() 
{
	if (hasGetUserMedia() == false) {
		hide('loadiconcamera');
	}

    $.extend(jsSocials.shares, {
    reddit: {
            label: "Reddit",
            logo: "fa fa-reddit",
            shareUrl: "https://www.reddit.com/submit?url={url}",
            countUrl: ""
        }
    });
    $("#share_container").jsSocials({
            //url : "http://www.christopherminson.com/openimage",
            url : BASE_PATH,
            shares: ["email", "twitter", "facebook", "reddit", "linkedin"],
                smallScreenWidth: 140,
                largeScreenWidth: 324
    });
}


/************************************************************/
/* Local ANIM functions */
var SelecteFrame = 1;
function chooseFrameFile(frame)
{
    var e;

    SelectedFrame = frame;

    e  = document.getElementById('SUBMITFRAMEFILE');
	e.value=""; // CJM - MUST do this to avoid load caching!
    e.click();
}

function submitFrameFile()
{
    var frame,e;

    e  = document.getElementById('FRAMELOADFORM');
    e.submit();

    frame = "FRAME"+SelectedFrame;
    e  = document.getElementById(frame);
    e.src = "../wimages/tools/busy.gif";
}


function completeFrameLoad(imageList,text)
{
    var image,frame,frameId,e;

	var imageArray = imageList.split(",");
	var imageCount = imageArray.length;
	
	for (i = 0; i < imageCount; i++)
	{

		image = imageArray[i];
		frameId = SelectedFrame + i;
		frame = "FRAME"+frameId;
		e  = document.getElementById(frame);
		if (e != null)
		{
			e.src = image;
		}

		frame = "FRAMEPATH"+frameId;
		e  = document.getElementById(frame);
		if (e != null)
		{
			e.value = image;
		}
	}

}

function reportFrameLoadError(error)
{
var e,frame;

	frame = "FRAME"+SelectedFrame;
	e  = document.getElementById(frame);
    e.src = BASE_PATH+"/wimages/tools/ezimbanoop.png";

	frame = "FRAMEPATH"+SelectedFrame;
	e  = document.getElementById(frame);
    e.src = BASE_PATH+"/wimages/tools/ezimbanoop.png";

	e = document.getElementById('statusReport');
	show('statusReport');
	e.innerHTML = error;
}


function deleteFrameImage(frameId)
{
    var frame,e;

    frame = "FRAME"+frameId;
    e  = document.getElementById(frame);
    e.src = BASE_PATH+"/wimages/tools/ezimbanoop.png";

    frame = "FRAMEPATH"+frameId;
    e  = document.getElementById(frame);
    e.value = BASE_PATH+"/wimages/tools/ezimbanoop.png";
}
/************************************************************/


function deleteImage(id,userid,datecode,image)
{

    hide(id);
    var param = "ID="+id+"&USERID="+userid+"&DATECODE="+datecode+"&IMAGE="+image;
    var http = getajaxRequest();
    http.open("POST","./deleteImage.php");
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send(param);
}


function getFirefoxVersion()
{
    var ua= navigator.userAgent, tem, 
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
	return M[1];
}


function getChromeVersion() 
{     
    var raw = navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./);
    return raw ? parseInt(raw[2], 10) : false;
}




//
// *******************************************
//


function chooseFile() 
{
    var e;

	e  = document.getElementById('SUBMITFILE');
	if (e == null)
	{
		var eSet  = document.getElementsByName('FILENAME');
		e = eSet[0];
	}
    if (e == null)
    {
        alert("Internal load error #1: please contact ezimba to report");
		return;
    }
	e.value=""; // CJM - MUST do this to avoid load caching!
	e.click();
}

// this gets executed via the click function in choosefile
function submitFile() 
{
    var e = document.getElementById('LOADFORM');

    if (e == null)
    {
        alert("Internal load error #2: please contact ezimba to report");
		return;
	}
	show('imagearea');
	show('opimage');
	e.submit();
	executeLoad();
}

function updateview(img, selection)
{
    if (!selection.width || !selection.height)
        return;

    var scaleX = 100 / selection.width;
    var scaleY = 100 / selection.height;

    $('#X1').val(selection.x1);
    $('#Y1').val(selection.y1);
    $('#X2').val(selection.x2);
    $('#Y2').val(selection.y2);
    $('#w').val(selection.width);
    $('#h').val(selection.height);

}

function viewOpList(id)
{
	hide('opslist1');
	hide('opslist2');
	hide('opslist3');
	hide('opslist4');

	show(id);
}

function hide(id)
{
    var e = document.getElementById(id);

    if (e != null)
        e.style.display = 'none';
    return;
}

function show(id)
{
    var e = document.getElementById(id);
    if (e != null)
        e.style.display = 'block';
    return;
}

function setElement(id,v)
{
	var e = document.getElementById(id);
    if (e != null)
        e.value = v;
}

//
// *****************************************************************
//




//
// optionally displays selection box for current image
// this happens when we are in Crop or Overlay ops
//
function displayImageSelection()
{
    var id;

    if (Ias != null)
    {
        Ias.cancelSelection();
        $('#opimage').imgAreaSelect({remove:true});
        Ias = null;
    }
    if ((CurrentOp.indexOf("crop") > -1) || (CurrentOp.indexOf("overlay") > -1))
    {
        Ias = $('#opimage').imgAreaSelect({ handles: true,
            fadeSpeed: 200, onSelectChange: updateview, instance: true });
        $('#opimagex').imgAreaSelect({ x1: 5, y1: 5, x2: 60, y2: 40 });
        setElement('X1',5);
        setElement('Y1',5);
        setElement('X2',60);
        setElement('Y2',40);
        setElement('W',55);
        setElement('H',35);
    }
    PreviousPosition = CurrentPosition;
    //Ias.setOptions({ show: true });
    //Ias.update();
}

function deleteImageAreaSelection()
{
    if (Ias != null)
    {
        Ias.cancelSelection();
        id = '#image'+CurrentPosition;
        $(id).imgAreaSelect({remove:true});
        Ias = null;
        CurrentOp = ""; // must do so we won't think we're still on an op page
    }
}

function openCurrentImage()
{
	OpImage = getCurrentImage();
	window.open(OpImage,"_blank");
}

function viewCurrentImage()
{
	OpImage = getCurrentImage();
	imageArray = OpImage.split("/");
	len = imageArray.length;
	var image = "/work/"+imageArray[len-2]+"/"+imageArray[len-1];
	var e = document.getElementById('viewimage');
	var v = BASE_PATH+"/zpages/displayimage.html?CURRENTFILE="+image;
	e.href=v;
}

function returnToMainArea()
{
    var e;

	hide('statusReport');
    hide('returntomainpage');
	e = document.getElementById('mainslider');
	if (e != null)
	{
			var scroll = 0;
			var s = "-"+scroll+"px";
			e.style.left = s;
	}
    deleteImageAreaSelection();
    return;
}

function displayOpForm()
{
var e;

	e = document.getElementById('mainslider');
	if (e != null)
	{
			var scroll = 900;
			var s = "-"+scroll+"px";
			e.style.left = s;
	}
}

function displayCurrentImage()
{

	var imagePath = ListImageURLS[CurrentPosition];
	var stats = ListImageStats[CurrentPosition];

	// set the this displayed image as the one to share (should user hit share button)
	var imageURL = BASE_PATH + imagePath;
    $("#share_container").jsSocials({
        url : imageURL,
    	shares: ["email", "twitter", "facebook", "reddit", "linkedin"],
    });

	// now display image and stats
    var e_opimage = document.getElementById('opimage');
    e_opimage.crossOrigin = "Anonymous";
    var e_stats = document.getElementById('imagestatus');

	stats = "[" + (CurrentPosition+1) + "/" + ListImageStats.length + "] " + stats;
	var b = "&nbsp;&nbsp;<button id=\"share\" onclick=\"socialShare(event)\">share</button>";
	stats = stats + b;

	e_opimage.src = imageURL;
    e_opimage.onload = function() {

		setHiddenImage(e_opimage);
    };

	e_stats.innerHTML = stats;
	setDownloadImageLink(imagePath);
}


function hideBusyImage()
{
	BusyDisplayed = 0;
}

function displayBusyImage()
{
    var e = document.getElementById('opimage');
    e.src = "/openimage/wimages/tools/busy.gif";
	BusyDisplayed = 1;
}

function getCurrentImage()
{

	var imageURL = null;

	if (CurrentPosition < ListImageURLS.length)  {
		imageURL = ListImageURLS[CurrentPosition];
	}

	return imageURL;
}

function setDownloadImageLink(imageURL)
{
    imageURL = "." + imageURL;
    console.log("HERE!@!!!!!!!!!!!!!!! setDownloadImage");
    var downloadLink = document.getElementById('downloadimage');
	if (downloadLink != null) {
        console.log("SET DOWNLOAD IMAGE", imageURL);
		downloadLink.href = imageURL;
	}
}

function setCurrentImage(imageURL)
{
    var e = document.getElementById('opimage');
    e.src = imageURL;
	setDownloadImageLink(imageURL);

	hide('statusReport');
}

function imageReady()
{
    displayImageSelection();
}

function setHiddenImage(image)
{

	var c = document.getElementById("hiddenImage");
	var ctx = c.getContext("2d");
	ctx.drawImage(image,0,0);

	// now get the real width and height of image.  
	// can't use image for this, as it will be scaled.
	var tmpImage = document.createElement("img");
	tmpImage.src = image.src;
    tmpImage.onload = function() {

        CurrentImageWidth = tmpImage.width;
        CurrentImageHeight = tmpImage.height;
    };

}


//
// get the color at this event point.
// images are stored both in the displayable opimage area as 
// well as in a hidden canvas.  we sample the point at the canvas image,
// taking into account scaling of the images
//
function getImageColorAtCurrentPoint(event)
{

    var image = document.getElementById('opimage');
	var image_width = image.width;
	var image_height = image.height;
	var image_rect = image.getBoundingClientRect();

	var x = Math.floor(event.clientX - image_rect.x);
	var y = Math.floor(event.clientY - image_rect.y);

    scale_x = CurrentImageWidth / image.width;
    scale_y = CurrentImageHeight / image.height;
    x = Math.floor(x * scale_x);
    y = Math.floor(y * scale_y);

	var c=document.getElementById("hiddenImage");
	var ctx=c.getContext("2d");
	var imgData = ctx.getImageData(x,y,1,1);
	red=imgData.data[0];
	green=imgData.data[1];
	blue=imgData.data[2];

	var hexRed = red.toString(16);
	if (hexRed.length < 2) hexRed = '00';
	var hexGreen = green.toString(16);
	if (hexGreen.length < 2) hexGreen = '00';
	var hexBlue = blue.toString(16);
	if (hexBlue.length < 2) hexBlue = '00';
	var hex ="#"+hexRed+hexGreen+hexBlue;

    e = document.getElementById('PICKCOLOR');
	if (e != null)
	{
		e.value = hex;
	}
	e=document.getElementById("COLOR1");
	if (e != null)
	{
		e.style.backgroundColor = hex;
	}

    e = document.getElementById('CLIENTX');
	if (e != null)
	{
		e.value = x;
	}
    e = document.getElementById('CLIENTY');
	if (e != null)
	{
		e.value = y;
	}
}


function setCurrentStatus(image,text)
{
	var id = BaseStatusId+CurrentPosition;
    var e = document.getElementById('opstatus');

	// special case: if this is result of batchoperation then
	// display a link to the batch viewer
	if ((text.indexOf("BATCH")) != -1)
	{
		OpImage = getCurrentImage();
		imageArray = OpImage.split("/");
		len = imageArray.length;
		image = "/work/"+imageArray[len-2]+"/"+imageArray[len-1];
		var url=BASE_PATH+"/zpages/batchview.html?"+"CURRENTFILE="+image;
		var link="<a target=blank href="+url+">Batched Results</a>";
		text = text+"&nbsp;&nbsp;&nbsp;&nbsp;"+link;
	}
    e.innerHTML = text;
}



// 
// add image to the end of the array of possible images (max=MAXIMAGES).
// if reached end of the array, then loop back and overwrite images 
// beginning at the first (1) position.
//
function addImage(imageURL,text)
{
	hideBusyImage();

	ListImageURLS.push(imageURL);
	ListImageStats.push(text);
	CurrentPosition = ListImageURLS.length - 1;
	displayCurrentImage();

	show('hometext');

    //
    // handle case where we are on an animation page and want to use this added image
    // when constructing an animation
    //
    // this means putting the added image into our file list (so will be picked up 
    // as input in a conversion). only do this if there are no other images already
    // loaded
    //
//DEV TBD
/*
    if (CurrentOp.indexOf("anim") > -1)
    {
        var e = document.getElementById('FRAME1');
	    var path = BASE_PATH+image;
        if (e != null) 
        {
            if (e.src.indexOf("noop") > -1)
                e.src = path;
        }
        e = document.getElementById('FRAMEPATH1');
        if (e != null)
        {
            if (e.src.indexOf("noop") > -1)
                e.value = path;
        }
    }
*/
}

function nextImage()
{
	hide('statusReport');
    CurrentPosition++;
	if (CurrentPosition >= ListImageURLS.length) CurrentPosition = 0;
	displayCurrentImage();
}

function previousImage()
{
	hide('statusReport');

    CurrentPosition--;
	if (CurrentPosition < 0) CurrentPosition = ListImageURLS.length - 1;
	displayCurrentImage();
}

function homeImage()
{
	hide('statusReport');
	if (HomePosition >= ListImageURLS.length) HomePosition = 0;
	CurrentPosition = HomePosition;
	displayCurrentImage();
}

function setHomeImage(imageURL,position)
{
	show('homeimage');
    var e = document.getElementById('homeimage');
	HomePosition = position;

	if (imageURL == null)
	{
		imageURL = BASE_PATH+"/wimages/tools/blank.jpg";
		HomePosition = 0;
	}
	e.src = imageURL;
}

function enableConvertButton()
{
	var e = document.getElementById('convert1');
	if (e != null)
	{
		e.disabled = false;
	}
}

function disableConvertButton()
{
	var e = document.getElementById('convert1');
	if (e != null)
	{
		e.disabled = true;
	}
}

function displaySmallLoadIcon()
{
    var e;

	//hide('loadimagetext');
	e = document.getElementById('loadiconglobe');
	if (e != null)
	{
		e.src = BASE_PATH+"/wimages/icons/LoadIconSmall-Globe.png";
	}

	e = document.getElementById('loadiconcomputer');
	e.src = BASE_PATH+"/wimages/icons/LoadIconSmall-Computer.png";

	e = document.getElementById('loadiconcamera');
	if (e != null)
	{
		e.src = BASE_PATH+"/wimages/icons/LoadIconSmall-Camera.png";
	}


}

function displayBigLoadIcon()
{
    var e;

	e = document.getElementById('loadiconglobe');
	e.src = BASE_PATH+"/wimages/icons/LoadIconBig-Globe.png";

	e = document.getElementById('loadiconcomputer');
	e.src = BASE_PATH+"/wimages/icons/LoadIconBig-Computer.png";

	e = document.getElementById('loadiconcamera');
	e.src = BASE_PATH+"/wimages/icons/LoadIconBig-Camera.png";


	//show('loadimagetext');
}


function executeLoad()
{
	show('opimage');
	displaySmallLoadIcon();

    OpImage = getCurrentImage();
	displayBusyImage();
}

function completeWithNoAction()
{
	enableConvertButton();
	hideBusyImage();
	if (CurrentPosition < 1)
	{
		hide('imagearea');
	}
}


function completeImageLoad(image,text)
{
	enableConvertButton();

	if (BusyDisplayed == 0)
	{
		return;
	}
	hideBusyImage();
	show('imagearea');
	show('mainslider');
	hide('loadarea');
	hide('webcam1');
	LoadDisplayed = 0;

	//var relImage = image.replace(BASE_PATH,".");
	var relImage = image.replace(BASE_PATH,"");

	addImage(relImage,text);

	setHomeImage(image,CurrentPosition);
}


function completeImageOp(image,text)
{
	enableConvertButton();


	//var relImage = image.replace(BASE_PATH,".");
	var relImage = image.replace(BASE_PATH,"");
	console.log("completeImageOp: ", image, relImage);

	// if this is true, indicates the op was cancelled via 
	// delete button prior to completion.
	if (BusyDisplayed == 0)
	{
		return;
	}
	addImage(relImage,text);
	hideBusyImage();
}

function xsubmitOpForm()
{
    var e = document.getElementById('OPSUBMITFORM');
    if (e == null)
    {
        alert("processing error - please try again");
        return;
    }
    executeOp();
    e.submit();

}

function submitOpForm()
{
    // CJM DEV - more validation here
    setTimeout(xsubmitOpForm, 500);
}

function executeOp()
{
var e;
var image;
var len;
var imageArray;

	// if already busy don't allow multiple converts
	if (BusyDisplayed == 1)
	{
		disableConvertButton();
	}
	else
	{
		enableConvertButton();
	}
	displayBusyImage();

	show('imagearea');

	image = "";
    OpImage = getCurrentImage();
	if (OpImage != null)
	{
		imageArray = OpImage.split("/");
		len = imageArray.length;
		image = "/work/"+imageArray[len-2]+"/"+imageArray[len-1];
	}

	e = document.getElementById('current');
	e.value = image;
}

function reportOpError(error)
{
	hideBusyImage();
	e = document.getElementById('statusReport');
	show('statusReport');
	e.innerHTML = error;
}


function reportLoadError(error)
{
var e;

	hideBusyImage();
	show('mainslider');
	hide('loadarea');
	if (CurrentPosition < 1)
	{
		hide('imagearea');
		displayBigLoadIcon();
	}
	LoadDisplayed = 0;
	e = document.getElementById('statusReport');
	show('statusReport');
	e.innerHTML = error;
}

function getajaxRequest()
{
    var ajaxRequest;

    try{
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e){
        // Internet Explorer Browsers
        try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                // Something went wrong
                return false;
            }
        }
    }
    return ajaxRequest;
}


function displayOp(op)
{
	var image;
	var imageArray;
	var len;
    var ajaxRequest = getajaxRequest();

    CurrentOp = op;
    ajaxRequest.onreadystatechange = function()
    {
        if(ajaxRequest.readyState == 4)
        {
            var response=ajaxRequest.responseText;
			var e;
            if (response.length > 10)
            {
			e  = document.getElementById('opform');
			e.innerHTML = response;
			displayOpForm();
            show('returntomainpage');
            displayImageSelection();
            }
        }
	}

	var params="";
	OpImage = getCurrentImage();
	if (OpImage != null) 
	{
		imageArray = OpImage.split("/");
		len = imageArray.length;
		image = "/work/"+imageArray[len-2]+"/"+imageArray[len-1];
		params="CURRENTFILE="+image;
	}
	ajaxRequest.open("POST",op,true);
	ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxRequest.send(params);
}


function updateFBLikeButton()
{
	var url =  BASE_PATH+"/zpages/fbdisplay.html";
	var params = "";
	OpImage = getCurrentImage();
	if (OpImage != null)	
	{
		imageArray = OpImage.split("/");
		len = imageArray.length;

		image = "/work/"+imageArray[len-2]+"/"+imageArray[len-1];
		params="?CURRENTFILE="+image;
	}

	url = url+params;
	url=encodeURIComponent(url);
	$('#like').html('<fb:like href="' + url + '" layout="button_count" show_faces="false" width="65" action="like" font="segoe ui" colorscheme="light" />');
	if (typeof FB !== 'undefined') 
	{
		FB.XFBML.parse(document.getElementById('like'));
	}

}


function toggleLoadDisplay()
{
	if (LoadDisplayed == 0)
	{
		hide('mainslider');
		show('loadarea');
		LoadDisplayed = 1;
	//e.innerHTML = s;
	}
	else
	{
		show('mainslider');
		hide('loadarea');
		LoadDisplayed = 0;
	}
}


function hasGetUserMedia() 
{
  return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}


const constraints = {
  video: true
};


function toggleWebcamDisplay()
{
	logTrace("CAMERA: toggleWebcamDisplay");

    var video = document.getElementById("video");

	if ($("#webcam1").is(":visible")) {
		logTrace("CAMERA stop video ");
        $("#webcam1").hide();
    }
    else {
		logTrace("CAMERA wants to start video ");
		navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {

			logTrace("CAMERA Device Found");
        	video.srcObject = stream
        	var playPromise = video.play();

			playPromise.then(function() {
				logTrace("CAMERA Device Now Playing");
      			$("#webcam1").show();
  			}).catch(function(error) {
				logTrace("CAMERA ERROR Device NOT Playing");
    			// Automatic playback failed.
    			// Show a UI element to let the user manually start playback.
  			});
    	})
		.catch(function(error) {

        	$("#webcam1").hide();
			logTrace("CAMERA Error: " + error.message);
  		});
    }
}

function takeHTML5Photo()
{
	logTrace("CAMERA takeHTML5Photo");
	executeLoad();

    var canvas = document.getElementById("cameracanvas");
    var context = canvas.getContext("2d");
    var video = document.getElementById("video");

    context.drawImage(video, 0, 0, 640, 480);

    $.post('./webcamuploaderx.php',
    {
        img : canvas.toDataURL("image/jpg")
    }, function(image) {
         BusyDisplayed = 1;
         completeImageLoad(image,"");
         logTrace("CAMERA completed uploadHTML5CameraData");
    });

	$("#webcam1").hide();
}



function execSimpleOp(op,target)
{
	var image;
	var imageArray;
	var l;

    if (ListImageURLS.length == 0)
        return;

    var ajaxRequest = getajaxRequest();

    ajaxRequest.onreadystatechange = function()
    {
        if(ajaxRequest.readyState == 4)
        {
			var image;
			var text;
			var a;
           	var response = ajaxRequest.responseText;
		
            console.log("ExecSimpleOp: Reponse Seen");
			// indicating op was cancelled prior to completion
			if (BusyDisplayed == 0)
			{
				hideBusyImage();
				return;
			}
			a = response.split("?");
			image = a[0];
			text = a[1];
            console.log("IMAGE-TEXT", response);
			
			addImage(image,text);
        }
	}

	var params="";
	OpImage = getCurrentImage();
	if (OpImage != null)	
	{
		displayBusyImage();
		imageArray = OpImage.split("/");
		len = imageArray.length;
		image = "/work/"+imageArray[len-2]+"/"+imageArray[len-1];
		params="CURRENTFILE="+image+"&TGT="+target;
		ajaxRequest.open("POST",op,true);
		ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxRequest.send(params);
	}
}


function selectTableItem(id,path,file,status)
{
    var sel;

    sel = document.getElementById("IMAGE");
    sel.src = path+file;
    sel = document.getElementById(id);
    sel.value = file;
    sel = document.getElementById("STATUS");
    sel.innerHTML = status;
}

function SetCookie( name, value, expires, path, domain)
{
// set time, it's in milliseconds
var today = new Date();
today.setTime( today.getTime() );

/*
if the expires variable is set, make the correct
expires time, the current script below will set
it for x number of days, to make it for hours,
delete * 24, for minutes, delete * 60 * 24
*/
if ( expires )
{
expires = expires * 1000 * 60 * 60 * 24;
}
var expires_date = new Date( today.getTime() + (expires) );

document.cookie = name + "=" +escape( value ) +
( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
( ( path ) ? ";path=" + path : "" ) +
( ( domain ) ? ";domain=" + domain : "" );
//( ( secure ) ? ";secure" : "" );
}

function Get_Cookie( name )
{
var start = document.cookie.indexOf( name + "=" );
var len = start + name.length + 1;
if ( ( !start ) &&
( name != document.cookie.substring( 0, name.length ) ) )
{
return null;
}
if ( start == -1 ) return null;
var end = document.cookie.indexOf( ";", len );
if ( end == -1 ) end = document.cookie.length;
return unescape( document.cookie.substring( len, end ) );
}

function logTrace(text)
{
	text = "JSTRACE: " + text;
	var log = BASE_PATH+"/zs/jstrace.php";
	var params = "VALUE="+text;
    var ajaxRequest = getajaxRequest();
	ajaxRequest.open("POST",log,true);
	ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxRequest.send(params);
}


function createSharedImage(imageURL)
{
	var target = BASE_PATH+"/zs/jsshare.php";
	var params = "VALUE="+imageURL;
    var ajaxRequest = getajaxRequest();
	ajaxRequest.open("POST",target,true);
	ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxRequest.send(params);
}




// CJM DREAM CODE
//*****************************************
var DREAMBASE_PATH="http://52.11.134.185/work/converted/";

var Timer = 0;  // the timer object for dream processing
var TimerCount = 1;     // where we are in getting sequence of dream files
var MissedImageCount = 0;       // count of misses of dream files

var DreamResultImage = 0;   // the image being dreamed
var DreamStatusFile = 0;   // the attributes of Images (max frames, current count) being dreamed
var DreamStatusElement = 0;   // status for that image
var DreamImageElement = 0;   // frames for that image
var MaxDreamFrames = 0;  // max of dream images generated by this dnn
var CurrentDreamFrame = 0;  // current frame being generated
var StatusImage;  // the image that gets encodes current and max frames

function startOpTimer(op,imagePath)
{
var e;

		StatusImage = new Image();
		StatusImage.addEventListener('load',imageLoad,false);
        if (Timer != 0)
        {
                e = document.getElementById(BaseStatusId+CurrentPosition);
                e.innerHTML = "Another Dream In Progress - Wait Until It's Done";
                return;
        }

        DreamResultImage = imagePath;
		DreamStatusFile = DreamResultImage.replace(".jpg",".status");
		DreamStatusFile = DreamStatusFile.replace(".png",".status");
		DreamStatusFile = DreamStatusFile.replace(".gif",".status");
		DreamStatusFile = DreamStatusFile.replace(".bmp",".status");

        DreamStatusElement = document.getElementById('statusarea');
		DreamStatusElement.innerHTML = "Dream Begins ...";
        DreamImageElement = document.getElementById('dreamframe');

        Timer = setInterval(displayDreamStatus,500);
}

function stopOpTimer()
{
	clearInterval(Timer);
	Timer = 0;
	TimerCount = 1;
	MissedImageCount = 0;
	DreamStatusElement.innerHTML = "The Dream is Complete";
	DreamImageElement.width=150;
	DreamImageElement.height=150;
}


function displayDreamStatus()
{
var rand, path;

	rand = Math.random().toString();
	path = DreamStatusFile+"?R="+rand;
	StatusImage.src = path;
}

function imageLoad()
{
	rand = Math.random().toString();
	CurrentDreamFrame = StatusImage.width;
	MaxDreamFrames = StatusImage.height;
    if (CurrentDreamFrame > 200) return;

	if (MaxDreamFrames == 0)	// no status report yet, just flag and wait
	{
			MissedImageCount++;
			if (MissedImageCount > 100)
			{
				DreamStatusElement.innerHTML = "Dream Canceled - Load Too Heavy. Try Again Later";
				stopOpTimer();
			}
			return;
	}
	if (MaxDreamFrames == 404)	// generic error reported by server
	{
		DreamStatusElement.innerHTML = "Dream Canceled - Load Too Heavy. Try Again Later";
		stopOpTimer();
	    return;
	}

	var percent = ((CurrentDreamFrame-1) / MaxDreamFrames) * 100;
	DreamStatusElement.innerHTML = "Dreaming: " + percent.toPrecision(2) + "% Complete";

	DreamImageElement.src = DreamResultImage+"?R="+rand;
	DreamImageElement.width=600;
	DreamImageElement.height=450;

	if (CurrentDreamFrame >= MaxDreamFrames)
	{
			stopOpTimer();
	}
}


function executeOpTimer()
{
	//executeOp();
    OpImage = getCurrentImage();
	startOpTimer('DEEPDREAM', OpImage);
    submitOpForm();
}


function socialShare(e)
{
    if ($("#share_container").is(":visible")) {
        $("#share_container").hide();
    }
    else {
        $("#share_container").show();
    }


}

    

