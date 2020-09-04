<?php

	display_application_header('
	<!doctype html>
	<html lang="en">
		<head>
			<!-- Required meta tags -->
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

			<!-- Favicons -->
			<link rel="apple-touch-icon" href="'.base_url('assets/favicons/apple-touch-icon.png').'" sizes="180x180">
			<link rel="icon" href="'.base_url('assets/favicons/favicon-32x32.png').'" sizes="32x32" type="image/png">
			<link rel="icon" href="'.base_url('assets/favicons/favicon-24x24.png').'" sizes="24x24" type="image/png">
			<link rel="mask-icon" href="'.base_url('assets/favicons/apple-touch-icon.png').'" color="#563d7c">
			<link rel="icon" href="'.base_url('assets/favicons/favicon.ico').'">

			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="'.base_url('assets/plugins/bootstrap/4.4.1/css/bootstrap.min.css').'" async>

			<!-- Font Lato CSS -->
			<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet" async>

			<!-- Font Cormorant Garamond CSS -->
			<link href="https://fonts.googleapis.com/css?family=Cormorant+Garamond:300,400,500,600,700&display=swap" rel="stylesheet" async>

			<!-- jQuery UI CSS -->
			<link rel="stylesheet" href="'.base_url('assets/css/jquery-ui.min.css').'" async>

			<!-- Custom CSS -->
			<link rel="stylesheet" href="'.base_url('assets/plugins/uikit-compatible-w-bootstrap/3.2.6/css/uikit.css').'" async>
			<link rel="stylesheet" href="'.base_url('assets/css/aruna.css').'" async>

			<title>'.get_ctitle().'</title>
		</head>

		<body>
			<header class="navbar navbar-expand-lg navbar-light uc-navbar uc-navbar-transparent fixed-top">
				<div class="container">
					<a href="'.site_url().'" class="navbar-brand mr-0 mr-md-4 py-2 uc-logo-color"><img src="'.base_url('assets/images/logo_brown.png').'"></a>

					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav ml-auto">
							<li class="nav-item">
								<a class="nav-link rounded-pill" href="'.site_url().'">Home</a>
							</li>

							<li class="nav-item">
								<a class="nav-link rounded-pill" href="'.site_url('about_us').'">About Us</a>
							</li>

							<li class="nav-item">
								<a class="nav-link rounded-pill" href="'.site_url('blog').'">Blog</a>
							</li>

							<li class="nav-item">
								<a class="nav-link rounded-pill" href="'.site_url('our_menu').'">Our Menu</a>
							</li>

							<li class="nav-item">
								<a class="nav-link rounded-pill" href="'.site_url('contact_us').'">Contact Us</a>
							</li>
						</ul>
					</div>
				</div>
			</header>');

	// Load application from modules
	display_application_content();

	display_application_footer('
			<footer class="uc-footer text-dark p-4 p-md-5">
				<div class="container">
					<div class="row mb-5">
						<div class="col-md-4">
							<img src="'.base_url('assets/images/logo_brown.png').'" class="img-fluid mb-4" style="width: 200px">
							<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal</p>
						</div>

						<div class="col-md-8 pl-md-5 mt-5 mt-xl-0">
							<div class="row">
								<div class="col-md-5 offset-md-2 mb-5 mb-xl-0">
									<h6 class="mb-3">Social Media</h6>
									
									<div class="list-group list-group-flush">
										<a href="#!" target="_blank" class="list-group-item bg-transparent border-top-0"><i class="fab fa-instagram fa-lg mr-2"></i> Instagram</a>
										<a href="#!" target="_blank" class="list-group-item bg-transparent"><i class="fab fa-facebook-square fa-lg mr-2"></i> Facebook</a>
										<a href="#!" target="_blank" class="list-group-item bg-transparent"><i class="fab fa-twitter-square fa-lg mr-2"></i> Twitter</a>
									</div>
								</div>

								<div class="col-md-5 mb-5 mb-xl-0">
									<h6 class="mb-3">Information</h6>
							
									<div class="list-group list-group-flush text-truncate">
										<a href="'.site_url('contact_us').'" class="list-group-item bg-transparent border-top-0"><i class="fas fa-angle-right fa-lg mr-2"></i> Contact Us</a>
										<a href="https://www.umaragroup.com/careers" class="list-group-item bg-transparent"><i class="fas fa-angle-right fa-lg mr-2"></i> Careers with us</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<hr class="uc-delimiter my-3">

					<div class="row mt-5 pt-5">
						<div class="col-md-6 text-center text-md-left mb-3 mb-xl-0">
							'.get_csite('footer_message').'
						</div>

						<div class="col-md-6 text-center text-md-right">
							Made with <i class="fas fa-heart fa-lg ml-1 mr-1"></i> & <i class="fas fa-coffee fa-lg ml-1"></i> in Jakarta, Indonesia.
						</div>
					</div>
				</div>
			</footer>

			<!-- Optional JavaScript -->
			<!-- jQuery first, then Popper.js, then Bootstrap JS, and other -->
			<script src="'.base_url('assets/js/jquery-3.4.1.min.js').'"></script>
			<script src="'.base_url('assets/js/jquery-ui-1.12.1.min.js').'"></script>
			<script src="'.base_url('assets/js/jquery-common.js').'"></script>
			<script defer src="'.base_url('assets/js/popper.min.js').'"></script>
			<script defer src="'.base_url('assets/plugins/bootstrap/4.4.1/js/bootstrap.min.js').'"></script>
			<script defer src="'.base_url('assets/plugins/fontawesome/5.11.2/js/all.min.js').'"></script>
			<script src="'.base_url('assets/plugins/bootbox/bootbox.all.min.js').'"></script>
			<script src="'.base_url('assets/plugins/uikit-compatible-w-bootstrap/3.2.6/js/uikit.min.js').'"></script>
			<script src="'.base_url('assets/plugins/uikit-compatible-w-bootstrap/3.2.6/js/uikit-icons.min.js').'"></script>
			<script async src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@13.0.1/dist/lazyload.min.js"></script>

			<script>
			$(document).ready(function() 
			{
				$baseurl = "'.base_url().'";

				var url = document.location.toString();
				
				if (url.match("#aneka_nusantara")) 
				{
					$(\'#v-pills-tab a[href="#v-pills-aneka_nusantara"]\').tab(\'show\');
				}
				
				$(".active-tooltip").tooltip();

				if (window.pageYOffset != 0)
				{
					$(".uc-navbar").addClass("uc-navbar-white");
				}

				$(window).scroll(function() 
				{
					if ($(window).scrollTop() == 0) 
					{
						$(".uc-navbar").removeClass("uc-navbar-white");
					}
					else 
					{
						$(".uc-navbar").addClass("uc-navbar-white");
					}
				});

				$(".lazy-bg").removeAttr("data-bg");

				let lazyLoadInstance = new LazyLoad(
				{
					elements_selector: ".lazy",
					threshold: 300
				});

				let lazyLoadInstance2 = new LazyLoad(
				{
					elements_selector: ".lazy-bg"
				});
			});
			</script>

			'.load_js().'
		</body>
	</html>');

?>