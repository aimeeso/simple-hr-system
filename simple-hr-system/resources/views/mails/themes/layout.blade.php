<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}

body {
font-family: Arial, sans-serif;
background-color: #f8f9fa;
color: #333;
margin: 0;
padding: 0;
}

.wrapper {
width: 100%;
background-color: #f8f9fa;
margin: 0;
padding: 0;
}

.content {
width: 100%;
margin: 0;
padding: 0;
}

.body {
width: 100%;
margin: 0;
padding: 0;
}

.inner-body {
width: 570px;
background-color: #ffffff;
margin: 0 auto;
padding: 0;
}

.content-cell {
padding: 20px;
}

.footer {
width: 570px;
margin: 0 auto;
padding: 20px;
text-align: center;
color: #999;
font-size: 12px;
}

.footer a {
color: #999;
text-decoration: none;
}

.button {
display: inline-block;
width: auto;
background-color: #007bff;
border-radius: 5px;
color: #ffffff;
font-size: 14px;
font-weight: bold;
text-decoration: none;
padding: 10px 20px;
text-align: center;
}

.button:hover {
background-color: #0056b3;
}

.header {
padding: 20px;
text-align: center;
background-color: #007bff;
color: #ffffff;
font-size: 24px;
font-weight: bold;
}

.logo {
margin-bottom: 20px;
}

.message {
margin-bottom: 20px;
font-size: 16px;
line-height: 1.5;
}

.subcopy {
margin-top: 20px;
font-size: 12px;
color: #666;
}
</style>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333; margin: 0; padding: 0;">

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%; background-color: #f8f9fa; margin: 0; padding: 0;">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%; margin: 0; padding: 0;">
{{ $header ?? '' }}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0; padding: 0;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="width: 570px; background-color: #ffffff; margin: 0 auto; padding: 0;">
<!-- Body content -->
<tr>
<td class="content-cell" style="padding: 20px;">
{{ $logo ?? '' }}
<br/>
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>

{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html>