<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"><![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<title>SmartBots - Coming Soon...</title>
	<meta name="description" content="SmartBots is almost ready">
	<meta name="keywords" content="smartbots">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link href="@asset('favicon.ico')" rel="shortcut icon">
	<!--[if IE]><![endif]-->
	<link href="@asset('public/libs/font-awesome/css/font-awesome.css')" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300italic,400,400italic,600,600italic,700,700italic,900,900italic">
	<link rel="stylesheet" href="@asset('public/comingsoon/css/reset.css')">
	<link rel="stylesheet" href="@asset('public/comingsoon/css/style.css')">
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body id="backtotop">
	<div class="fullwidth clearfix">
		<div id="topcontainer" class="bodycontainer clearfix">
			<p><span class="fa fa-signal"></span></p>
			<h1><span>SmartBots</span><br />is coming soon</h1>
			<p>It's almost ready ... honest</p>
		</div>
	</div>
	<div class="arrow-separator arrow-white"></div>
	<div class="fullwidth colour1 clearfix">
		<div id="countdown" class="bodycontainer clearfix">
			<div id="countdowncont" class="clearfix">
				<ul id="countscript">
					<li>
						<span class="days">00</span>
						<p>Days</p>
					</li>
					<li>
						<span class="hours">00</span>
						<p>Hours</p>
					</li>
					<li class="clearbox">
						<span class="minutes">00</span>
						<p>Minutes</p>
					</li>
					<li>
						<span class="seconds">00</span>
						<p>Seconds</p>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="arrow-separator arrow-theme"></div>
	<div class="fullwidth colour2 clearfix">
		<div id="maincont" class="bodycontainer clearfix">
			<h2>Sign up and we'll let you know when we launch</h2>
			<p>If you would like to receive <strong>news and special offers</strong> please send us your email address below:</p>
			<div id="signupform" class="sb-search clearfix">
				{!! Form::open(['route' => ['comingsoon::subscribe'], 'class' => 'form-horizontal', 'onsubmit' => 'return false']) !!}
					<input class="sb-search-input" placeholder="Enter email ..." type="text" value="">
					<input class="sb-search-submit" value="" type="submit">
					<button class="formbutton" type="submit"><span class="fa fa-search"></span></button>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<script src="@asset('public/libs/jquery/jquery.js')"></script>
	<script src="@asset('public/comingsoon/js/countdown.js')"></script>
	<script>
		$(document).ready(function() {
			"use strict";
			$("#countdown").countdown({
				date: "{{ $time }}",
				format: "on"
			},
			function() {
			//
			});
		});
	</script>
</body>
</html>
