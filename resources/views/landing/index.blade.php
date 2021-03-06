<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

    <title>The Smartbots System</title>

    <meta content="noindex, nofollow" name="robots">

    <meta name="description" content="The Smartbots System">
    <meta name="author" content="">

    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Arvo:400,700' rel='stylesheet' type='text/css'>
    <link href="@asset('public/libs/bootstrap/css/bootstrap.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/libs/font-awesome/css/font-awesome.css')" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('public/landing/css/general.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/landing/css/custom.min.css') }}" rel="stylesheet">
	<link href="{{ asset('public/landing/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/landing/css/owl.theme.min.css') }}" rel="stylesheet">
	<link href="{{ asset('public/landing/css/style.min.css') }}" rel="stylesheet">
	<link href="@asset('public/libs/animate.css/animate.css')" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="{{ asset('public/landing/css/magnific-popup.min.css') }}">

	<style>
	.tick {
		position: relative;
		width: 10px;
		height: 10px;
		line-height: 10px;
	}
	</style>

	<script src="{{ asset('public/landing/js/modernizr-2.8.3.min.js') }}"></script>
	<!--[if IE 9]>
		<script src="{{ asset('public/landing/js/PIE_IE9.js') }}"></script>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="{{ asset('public/landing/js/PIE_IE678.js') }}"></script>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="{{ asset('public/landing/js/html5shiv.min.js') }}"></script>
	<![endif]-->

</head>

<body id="home">

	<div id="preloader">
		<div id="status"></div>
	</div>

    <div class="intro-header">
		<div class="col-xs-12 text-center abcen1">
			<h1 class="h1_home wow fadeIn" data-wow-delay="0.4s">Smart Bots</h1>
			<h3 class="h3_home wow fadeIn" data-wow-delay="0.6s">A smarthome for every home</h3>
			<ul class="list-inline intro-social-buttons">
				<li><a href="{{ route('a::login') }}" class="btn  btn-lg mybutton_cyano wow fadeIn" data-wow-delay="0.8s"><span class="network-name">Go</span></a>
				</li>
				<li id="download" ><a href="#whatis" class="btn  btn-lg mybutton_standard wow swing wow fadeIn" data-wow-delay="1.2s"><span class="network-name">Learn more</span></a>
				</li>
			</ul>
		</div>

		<div class="col-xs-12 text-center abcen wow fadeIn">
			<div class="button_down ">
				<a class="imgcircle wow bounceInUp" data-wow-duration="1.5s"  href="#whatis"> <img class="img_scroll" src="{{ asset('public/landing/img/icon/circle.png') }}" alt=""> </a>
			</div>
		</div>
    </div>

	<nav class="navbar-default" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#home">SmartBots</a>
			</div>

			<div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
				<ul class="nav navbar-nav">

					<li class="menuItem"><a href="#whatis">What is?</a></li>
					<li class="menuItem"><a href="#features">Features</a></li>
					<li class="menuItem"><a href="#screen">Screenshot</a></li>
					<li class="menuItem"><a href="#credits">Team</a></li>
					<li class="menuItem"><a href="#contact">Contact</a></li>
				</ul>
			</div>

		</div>
	</nav>

	<div id="whatis" class="content-section-b" style="border-top: 0">
		<div class="container">

			<div class="col-md-8 col-md-offset-2 text-center wrap_title">
				<h2>What is "Smart Bots"?</h2>
				<p class="lead" style="margin-top:0">A system that connects many wireless bots, each bot just like a human finger that can push most ordinary buttons and switches. </p>

			</div>

			<div class="row">

				<div class="col-sm-4 wow fadeInDown text-center">
				  <img class="rotate" src="{{ asset('public/landing/img/icon/tick.svg') }}" width="100px" alt="Generic placeholder image">
				  <h3>Easy</h3>
				  <p class="lead">Easy to install, easy to setup, easy to use</p>
				</div>

				<div class="col-sm-4  wow fadeInDown text-center">
				  <img class="rotate" src="{{ asset('public/landing/img/icon/ball.svg') }}" width="100px" alt="Generic placeholder image">
				  <h3>Fit</h3>
				  <p class="lead">Fit every devices and can control every thing</p>
				</div>

				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="{{ asset('public/landing/img/icon/home.svg') }}" width="100px" alt="Generic placeholder image">
				   <h3>Anywhere</h3>
				   <p class="lead">Can be controlled from anywhere</p>
				</div>

			</div>

			<div class="row tworow">

				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="{{ asset('public/landing/img/icon/interface.svg') }}" width="100px" alt="Generic placeholder image">
				   <h3>Automatically</h3>
					<p class="lead">Also can work automatically as scheduled, or with workflows</p>
				</div>

				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="{{ asset('public/landing/img/icon/shopping.svg') }}" width="100px" alt="Generic placeholder image">
				   <h3>Low cost</h3>
				   <p class="lead">Low price suitable for all audiences</p>
				</div>

				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate" src="{{ asset('public/landing/img/icon/app.svg') }}" width="100px" alt="Generic placeholder image">
				   <h3>Beautiful</h3>
				 <p class="lead">Beautiful design and beautiful app</p>
				</div>

			</div>
		</div>
	</div>

	<div id ="features" class="content-section-a">

        <div class="container">

            <div class="row">

				<div class="col-sm-4 pull-right wow fadeInRightBig">
                    <img class="img-responsive " src="{{ asset('public/landing/img/1.png') }}" alt="">
                </div>

                <div class="col-sm-8 wow fadeInLeftBig"  data-animation-delay="200">
                    <h3 class="section-heading">Automation - Event - Schedule</h3>
                    <p class="lead">With 3 features above, users can make SmartBots run automatically. With Automation, users can create many many workflow that make SmartBots work exactly the right way that users want to. With event, users can monitor all activities of bots. With Schedule, users can make SmartBots work automatically intime</p>
				</div>
            </div>
        </div>
    </div>

    <div class="content-section-b">

		<div class="container">
            <div class="row">

                <div class="col-sm-2 pull-left wow fadeInLeftBig">
                    <img class="img-responsive " src="{{ asset('public/landing/img/2.png') }}" alt="">
                </div>

                <div class="col-sm-10 wow fadeInRightBig"  data-animation-delay="200">
                    <h3 class="section-heading">Notification System</h3>
                    <p class="lead">SmartBots's notification system bring a more comprehensive monitor to user, that notice them everything happened in everywhere at anytime.</p>
				</div>
			</div>
        </div>
    </div>

    <div id ="useit" class="content-section-a">

        <div class="container">

            <div class="row">

				<div class="col-sm-2 pull-right wow fadeInRightBig">
                    <img class="img-responsive " src="{{ asset('public/landing/img/3.png') }}" alt="">
                </div>

                <div class="col-sm-10 wow fadeInLeftBig"  data-animation-delay="200">
                    <h3 class="section-heading">Quick Control Panel</h3>
                    <p class="lead">This is a very interesting and useful of SmartBots. Quick Control Panel will give users the right to control some bot they want in every page</p>
				</div>
            </div>
        </div>
    </div>

    <div id ="useit" class="content-section-b">

        <div class="container">

            <div class="row">

				<div class="col-sm-4 pull-right wow fadeInRightBig">
                    <img class="img-responsive " src="{{ asset('public/landing/img/4.png') }}" alt="">
                </div>

                <div class="col-sm-8 wow fadeInLeftBig"  data-animation-delay="200">
                    <h3 class="section-heading">Awesome Technology</h3>
                    <p class="lead">The SmartBots system is building on top of many awesome technologies: PHP, NodeJS, Laravel, MariaDB, Redis, ExpressJS, SocketIO, LESS, Gulp,...</p>
				</div>
            </div>
        </div>
    </div>

	<!-- Screenshot -->
	<div id="screen" class="content-section-a">
        <div class="container">
          <div class="row" >
			 <div class="col-md-6 col-md-offset-3 text-center wrap_title ">
				<h2>App Interface</h2>
				<p class="lead" style="margin-top:0">The design of the "Smartbots" App</p>
			 </div>
		  </div>
		    <div class="row wow bounceInUp" >
              <div id="owl-demo" class="owl-carousel">

				<a href="{{ asset('public/landing/img/slide/1.png') }}" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="{{ asset('public/landing/img/slide/1.png') }}" alt="Owl Image">
					</div>
				</a>

               <a href="{{ asset('public/landing/img/slide/2.png') }}" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="{{ asset('public/landing/img/slide/2.png') }}" alt="Owl Image">
					</div>
				</a>

				<a href="{{ asset('public/landing/img/slide/3.png') }}" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="{{ asset('public/landing/img/slide/3.png') }}" alt="Owl Image">
					</div>
				</a>

				<a href="{{ asset('public/landing/img/slide/4.png') }}" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="{{ asset('public/landing/img/slide/4.png') }}" alt="Owl Image">
					</div>
				</a>

               <a href="{{ asset('public/landing/img/slide/5.png') }}" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="{{ asset('public/landing/img/slide/5.png') }}" alt="Owl Image">
					</div>
				</a>

				<a href="{{ asset('public/landing/img/slide/6.png') }}" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="{{ asset('public/landing/img/slide/6.png') }}" alt="Owl Image">
					</div>
				</a>

				<a href="{{ asset('public/landing/img/slide/7.png') }}" class="image-link">
					<div class="item">
						<img  class="img-responsive img-rounded" src="{{ asset('public/landing/img/slide/7.png') }}" alt="Owl Image">
					</div>
				</a>
              </div>
          </div>
        </div>
	</div>


	<!-- Team -->
	<div id="credits" class="content-section-b">
		<div class="container">
			<div class="row">

			<div class="col-md-6 col-md-offset-3 text-center wrap_title">
				<h2>Team</h2>
				<p class="lead" style="margin-top:0">People behind this project</p>
			 </div>

			<div class="row">

				<div class="col-sm-4 wow fadeInDown text-center">
				  <img class="rotate img-rounded" src="https://avatars0.githubusercontent.com/u/12293622" alt="Generic placeholder image" width="200px">
				  <h3>Huynh Duc Duy</h3>
				  <p class="lead">Founder, CEO, Coder, Designer</p>
				  <li class="social" style="margin-top: -20px">
					<a href="http://fb.com/huynhducduy"><i class="fa fa-facebook-square fa-size"> </i></a>
					<a href="http://github.com/h2dvnnet"><i class="fa fa-github-square fa-size"> </i></a>
					<a href="mailto:h2dvnnet@gmail.com"><i class="fa fa-envelope fa-size"> </i></a>
					<a href="skype:delete.vn"><i class="fa fa-skype fa-size"> </i></a>
				  </li>
				</div>

				<div class="col-sm-4  wow fadeInDown text-center">
				  <img class="rotate img-rounded" src="https://avatars1.githubusercontent.com/u/16163451" alt="Generic placeholder image" width="200px">
				  <h3>Tran Tuan Anh</h3>
				  <p class="lead">Co-founder, Tester, Engineer</p>
				  <li class="social" style="margin-top: -20px">
					<a href="http://fb.com/yorkittran"><i class="fa fa-facebook-square fa-size"> </i></a>
					<a href="http://github.com/yorkittran"><i class="fa fa-github-square fa-size"> </i></a>
					<a href="mailto:yorkittran@gmail.com"><i class="fa fa-envelope fa-size"> </i></a>
					<a href="skype:yorkittran@outlook.com"><i class="fa fa-skype fa-size"> </i></a>
				  </li>
				</div>

				<div class="col-sm-4 wow fadeInDown text-center">
				  <img  class="rotate img-rounded" src="https://avatars2.githubusercontent.com/u/10744609" alt="Generic placeholder image" width="200px">
				   <h3>Tong Xuan Bao</h3>
				   <p class="lead">Co-founder, Tester, Engineer</p>
				   <li class="social" style="margin-top: -20px">
					<a href="http://fb.com/byn.cuco"><i class="fa fa-facebook-square fa-size"> </i></a>
					<a href="http://github.com/13yn"><i class="fa fa-github-square fa-size"> </i></a>
					<a href="mailto:baotongxuan@gmail.com"><i class="fa fa-envelope fa-size"> </i></a>
					<a href="skype:byncuco"><i class="fa fa-skype fa-size"> </i></a>
				  </li>
				</div>

			</div>

		  </div>

		</div>
	</div>

	<div  class="content-section-c ">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center white">
					<h2>Get Live Updates</h2>
					<p class="lead" style="margin-top:0">If you would like to receive news and special offers please send us your email address below</p>
				 </div>
				<div class="col-md-6 col-md-offset-3 text-center">
					<div class="mockup-content">
						<div class="morph-button wow pulse morph-button-inflow morph-button-inflow-1">
							<button type="button "><span>Subscribe to our Newsletter</span></button>
							<div class="morph-content">
								<div>
									<div class="content-style-form content-style-form-4 ">
										<h2 class="morph-clone">Subscribe to our Newsletter</h2>
										<form>
											<p><label>Your Email Address</label><input type="text"/></p>
											<p><button>Subscribe me</button></p>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="contact" class="content-section-a">
		<div class="container">
			<div class="row">

			<div class="col-md-12 text-center wrap_title">
				<h2>Get in touch</h2>
				<p class="lead" style="margin-top:0">Have a question? do not hesitate to contact us</p>
			</div>

			<form role="form" action="" method="post" >
				<div class="col-md-12">
					<div class="form-group">
						<label for="InputName">Your Name</label>
						<div class="input-group">
							<input type="text" class="form-control" name="InputName" id="InputName" placeholder="Enter Name" required>
							<span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback tick"></i></span>
						</div>
					</div>

					<div class="form-group">
						<label for="InputEmail">Your Email</label>
						<div class="input-group">
							<input type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="Enter Email" required  >
							<span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback tick"></i></span>
						</div>
					</div>

					<div class="form-group">
						<label for="InputMessage">Message</label>
						<div class="input-group">
							<textarea name="InputMessage" id="InputMessage" class="form-control" rows="5" required></textarea>
							<span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback tick"></i></span>
						</div>
					</div>
					<input type="submit" name="submit" id="submit" value="Submit" class="btn wow tada btn-embossed btn-primary pull-right">
				</div>
			</form>
			</div>
			</div>
		</div>
	</div>

    <footer>
      <div class="container">
        <div class="row">
          <div class="pull-right" style="margin-right: 15px">
             <li class="social">
				<a href="#"><i class="fa fa-facebook-square fa-size"> </i></a>
				<a href="#"><i class="fa fa-twitter-square fa-size"> </i> </a>
				<a href="#"><i class="fa fa-google-plus-square fa-size"> </i></a>
				<a href="#"><i class="fa fa-flickr fa-size"> </i> </a>
			</li>
          </div>
        </div>
      </div>
    </footer>

    <script src="{{ asset('public/landing/js/jquery-1.10.2.min.js') }}"></script>
    <script type="text/javascript" src="@asset('public/libs/raven-js/raven.js')"></script>
    <script>Raven.config('https://fbc910700f1a485c9fc08c38fc8cbec1@app.getsentry.com/83304').install()</script>
    <script type="text/javascript" src="@asset('public/libs/bootstrap/js/bootstrap.js')"></script>
	<script type="text/javascript" src="{{ asset('public/landing/js/owl.carousel.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/landing/js/script.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/landing/js/stickUp.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/landing/js/jquery.corner.min.js') }}"></script>
	<script type="text/javascript" src="@asset('public/libs/wow/wow.js')"></script>
	<script type="text/javascript" src="{{ asset('public/landing/js/classie.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/landing/js/uiMorphingButton_inflow.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/landing/js/jquery.magnific-popup.min.js') }}"></script>

	<script type="text/javascript">
	  jQuery(function($) {
		$(document).ready( function() {
		  $('.navbar-default').stickUp();
		  new WOW().init();
		});
	  });
	</script>
</body>
</html>
