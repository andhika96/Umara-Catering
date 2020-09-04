<?php

	section_header('
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
			<link rel="stylesheet" href="'.base_url('assets/plugins/bootstrap/4.4.1/css/bootstrap.min.css').'">

			<!-- Font Lato CSS -->
			<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet"> 

			<!-- jQuery UI CSS -->
			<link rel="stylesheet" href="'.base_url('assets/css/jquery-ui.min.css').'">

			<!-- Custom CSS -->
			<link rel="stylesheet" href="'.base_url('themes/default/css/aruna_admin_v2.css?v=0.0.1').'">
			<link rel="stylesheet" href="'.base_url('assets/plugins/croppie/2.6.2/css/croppie.css').'">
			<link rel="stylesheet" href="'.base_url('assets/plugins/perfect-scrollbar/1.4.0/css/perfect-scrollbar.css').'">

			<title>'.get_ctitle().'</title>
		</head>

		<body>
			<div class="arv2-admin-container">
				<!--- Header --->
				<header class="navbar navbar-expand-lg navbar-expand-sm navbar-light arv2-admin-navbar-nav sticky-top flex-md-row text-white mb-4 py-0">
					<a class="navbar-brand text-center m-0 py-3 pl-3 pr-5">Umara Catering</a>

					<ul class="navbar-nav p-1 mx-1 d-block d-xl-none">
						<li class="nav-item nav-icon menu-open-mobile">
							<a class="nav-link icon-open-mobile" href="javascript:void(0);"><i class="fas fa-bars fa-lg"></i></a>
						</li>

						<li class="nav-item nav-icon menu-close-mobile d-none">
							<a class="nav-link icon-close-mobile" href="javascript:void(0);"><i class="fas fa-bars fa-lg"></i></a>
						</li>
					</ul>

					<ul class="navbar-nav flex-row ml-md-auto p-1">
						<li class="nav-item dropdown">
							<a href="#!" class="nav-link dropdown-toggle d-md-flex text-white" id="DropdownSettings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-cog fa-lg"></i>
							</a>

							<div class="dropdown-arrow"></div>
							<div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="DropdownSettings" style="position: absolute; will-change: top, right; top: 46px;right: 0">
								<a class="dropdown-item" href="'.site_url('account').'"><i class="far fa-user mr-2"></i> Account</a>
								<a class="dropdown-item" href="'.site_url('auth/logout').'"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
							</div>
						</li>
					</ul>
				</header>
				<!--- End of Header --->

				<!--- Body --->
				<div class="arv2-admin-sidebar position-fixed mx-4 mt-1">
					<div class="arv2-admin-sidebar-content">
						<div class="position-sticky w-100 h-100 active-perfect-scrollbar bg-white shadow-sm rounded mb-4">
							<a href="'.site_url().'" class="list-group-item list-group-item-action" target="_blank"><i class="fas fa-globe-asia mr-2"></i> Visit Site</a>
							<a href="'.site_url('dashboard').'" class="list-group-item list-group-item-action"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>');
						
						if (allow_access([99]))
						{	
							section_header('
							<a href="'.site_url('awesome_admin').'" class="list-group-item list-group-item-action"><i class="fas fa-magic mr-2"></i> Awesome Admin</a>');
						}

						if (allow_access([99, 96]))
						{
							section_header('
							<a href="'.site_url('manage_cover_image').'" class="list-group-item list-group-item-action"><i class="fas fa-images mr-2"></i> Manage Cover Image</a>');
						}

						if (allow_access([99, 96]))
						{
							section_header('
							<a href="'.site_url('manage_gallery').'" class="list-group-item list-group-item-action"><i class="fas fa-image mr-2"></i> Manage Gallery</a>');
						}

						if (allow_access([99, 96]))
						{
							section_header('
							<a href="'.site_url('manage_content_page').'" class="list-group-item list-group-item-action"><i class="fas fa-columns mr-2"></i> Manage Content Page</a>');
						}

						if (allow_access([99, 96]))
						{
							section_header('
							<a href="'.site_url('manage_blog').'" class="list-group-item list-group-item-action"><i class="fas fa-image mr-2"></i> Manage Blog</a>');
						}

						if (allow_access([99, 96]))
						{
							section_header('
							<a href="'.site_url('manage_menu').'" class="list-group-item list-group-item-action"><i class="fas fa-utensils mr-2"></i> Manage Menu</a>');
						}

						if (allow_access([99, 96]))
						{
							section_header('
							<a href="'.site_url('selected_menu').'" class="list-group-item list-group-item-action"><i class="fas fa-check-double mr-2"></i> Selected Menu</a>');
						}

	section_header('
						</div>
					</div>
				</div>

				<!--- Main Content --->
				<div class="arv2-admin-main-content mt-2 mr-5 mr-xl-4">
					<div class="arv2-admin-submain-content">

						<div class="row mb-5 mb-md-0">
							<div class="col-md-6">
								<div class="media text-white p-4">
									'.avatar(get_user('id'), 64, NULL, 'img-fluid align-self-center mr-3').'
						
									<div class="media-object mt-1">
										<h5 class="mt-0">'.get_user('fullname').'</h5>
										'.get_role(get_user('id')).'
									</div>
								</div>
							</div>
						</div>
	');

?>