<?php
$my_email = "favaslost190@gmail.com";
$continue = "favaslost190@gmail.com";
$errors = array();
// Remove $_COOKIE elements from $_REQUEST.
if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}
// Check all fields for an email header.
function recursive_array_check_header($element_value)
{
global $set;
if(!is_array($element_value)){if(preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$element_value)){$set = 1;}}
else
{
foreach($element_value as $value){if($set){break;} recursive_array_check_header($value);}
}
}
recursive_array_check_header($_REQUEST);
if($set){$errors[] = "You cannot send an email header";}
unset($set);
// Validate email field.
if(isset($_REQUEST['email']) && !empty($_REQUEST['email']))
{
if(preg_match("/(%0A|%0D|\n+|\r+|:)/i",$_REQUEST['email'])){$errors[] = "Email address may not contain a new line or a colon";}
$_REQUEST['email'] = trim($_REQUEST['email']);
if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}
}
// Check referrer is from same site.
if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}
// Check for a blank form.
function recursive_array_check_blank($element_value)
{
global $set;
if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{
foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}
}
}
recursive_array_check_blank($_REQUEST);
if(!$set){$errors[] = "You cannot send a blank form";}
unset($set);
// Display any errors and exit if errors exist.
if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}
if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}
// Build message.
function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");}
$message = build_message($_REQUEST);
$message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."";
$message = stripslashes($message);
$subject = "FAVAS PORTFOLIO";
$headers = "From: " . $_REQUEST['Name'];
mail($my_email,$subject,$message,$headers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>FAVAS</title>
<!-- Icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<!-- Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
<div class="container bee" style="width: 290px;margin:auto; text-align: center; font-family: 'Roboto', sans-serif; color: #1d1d1d; padding-top: 50px; padding-bottom: 50px;"> <i class="fas fa-check" style="font-size: 50px; color: #000;"></i>
  <h3 style="padding-top: 25px;">Thank you</h3>
  <h4 style="padding-bottom: 20px;">Your mail has been sent</h4>
  <a href="../index.html" target="_self" style="font-weight: 700; font-size: 18px; color: #FFF; background-color: #333; padding:15px 50px; text-decoration:none;" >BACK TO HOME</a></div>
</body>
</html>