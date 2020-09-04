<?php

	/*
	 *	Aruna Development Project
	 *	IS NOT FREE SOFTWARE
	 *	Codename: Aruna Personal Site
	 *	Source: Based on Sosiaku Social Networking Software
	 *	Website: https://www.sosiaku.gq
	 *	Website: https://www.aruna-dev.id
	 *	Created and developed by Andhika Adhitia N
	 */

defined('MODULEPATH') OR exit('No direct script access allowed');

class home {

	// Default variable for load extension
	protected $ext;

	protected $db;
	
	public function __construct() 
	{
		$this->ext = load_ext(['url']);

		// Connected to the database
		$this->db = load_db('default', 'MySQL');
	}

	public function index()
	{
		section_content('
		<!-- Custom CSS -->
		<link rel="stylesheet" href="'.base_url('assets/css/default-homepage.css').'">

		<div class="container d-flex align-items-center vh-100 my-4 my-lg-0">
			<div>
				<div class="ar-default-homepage bg-white shadow mb-3">
					<div class="row">
						<div class="col-lg-8 order-2 order-lg-1 d-flex align-items-center text-center">
							<div>
								<h3 class="mb-3">Welcome to Aruna Development Project</h3>
								<p class="mb-4 mb-md-5">The page you are looking at is being generated dynamically by Aruna Development Project.</p>
								<h4>Thanks for using my Framework !! <i class="far fa-smile ml-1"></i></h4>
							</div>
						</div>

						<div class="col-lg-4 mb-5 mb-lg-0 order-1 order-lg-2 text-center">
							<img src="'.base_url('assets/images/super_thankyou.svg').'" class="img-fluid">
						</div>
					</div>
				</div>

				<div class="row text-white">
					<div class="col-lg-6 col-md-6 text-center text-md-left mb-3 mb-md-0">
						Made with <i class="fas fa-heart mx-1"></i> & <i class="fas fa-coffee mx-1"></i> in Jakarta, Indonesian.
					</div>

					<div class="col-lg-6 col-md-6 text-center text-md-right">
						Created & Developed by <a href="https://www.instagram.com/andhika_adhitia" target="_blank" class="text-white font-weight-bold"><u>Andhika Adhitia N</u></a>
					</div>
				</div>
			</div>
		</div>

			<div class="container d-flex align-items-center vh-100 my-4 my-lg-0">
			<div>
				<div class="ar-default-homepage bg-white shadow mb-3">
					<div class="row">
						<div class="col-lg-8 order-2 order-lg-1 d-flex align-items-center text-center">
							<div>
								<h3 class="mb-3">Welcome to Aruna Development Project</h3>
								<p class="mb-4 mb-md-5">The page you are looking at is being generated dynamically by Aruna Development Project.</p>
								<h4>Thanks for using my Framework !! <i class="far fa-smile ml-1"></i></h4>
							</div>
						</div>

						<div class="col-lg-4 mb-5 mb-lg-0 order-1 order-lg-2 text-center">
							<img src="'.base_url('assets/images/super_thankyou.svg').'" class="img-fluid">
						</div>
					</div>
				</div>

				<div class="row text-white">
					<div class="col-lg-6 col-md-6 text-center text-md-left mb-3 mb-md-0">
						Made with <i class="fas fa-heart mx-1"></i> & <i class="fas fa-coffee mx-1"></i> in Jakarta, Indonesian.
					</div>

					<div class="col-lg-6 col-md-6 text-center text-md-right">
						Created & Developed by <a href="https://www.instagram.com/andhika_adhitia" target="_blank" class="text-white font-weight-bold"><u>Andhika Adhitia N</u></a>
					</div>
				</div>
			</div>
		</div>
		');
	}

	public function landing()
	{
		section_header('
		<div class="content-header position-relative">
			<div class="page-header header-filter header-large lazy" data-bg="url('.base_url(get_cover_image('homepage', 'cover-image', 'image')).')">
				<div class="container">
					<div class="row mt-4">
						<div class="col-md-9 ml-auto mr-auto text-center" style="text-shadow: 2px 2px 3px rgba(0,0,0,0.7) !important">
							<h1 class="uc-h2 uc-heading-cg">'.get_cover_image('homepage', 'cover-image', 'title').'</h1>
							<h5 class="font-weight-light">'.get_cover_image('homepage', 'cover-image', 'caption').'</h5>
						</div>
					</div>
				</div>
			</div>

			<div class="text-center position-absolute" style="bottom: 2rem;left: 0;right: 0">
				<a href="#our_story">
					<svg class="blurp--top" width="192" height="61" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 160.7 61.5" enable-background="new 0 0 160.7 61.5" xml:space="preserve"><path fill="#FFFFFF" d="M80.3,61.5c0,0,22.1-2.7,43.1-5.4s41-5.4,36.6-5.4c-21.7,0-34.1-12.7-44.9-25.4S95.3,0,80.3,0c-15,0-24.1,12.7-34.9,25.4S22.3,50.8,0.6,50.8c-4.3,0-6.5,0,3.5,1.3S36.2,56.1,80.3,61.5z"></path></svg>
					<i class="fas fa-chevron-down text-dark position-absolute" style="top: -5px;left: 50%;margin-left: -12px;z-index: 12;font-size: 1.6rem;"></i>
				</a>
			</div>
		</div>');

		section_content('
		<div class="container my-5 pt-4 pb-3">
			<div class="row mb-5" id="our_story" style="margin-top: -10rem;padding-top: 10rem">
				<div class="col-md-6 d-flex align-items-center">
					<div>
						<h3 class="uc-heading uc-heading-cg mb-4 pb-2">Our <strong>Story</strong> <span></span></h3>

						<p>
							'.nl2br(get_content_page('homepage', 'our-story', 'description')).'
						</p>
					</div>
				</div>

				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<img data-src="'.base_url(get_content_page('homepage', 'our-story', 'image_0')).'" class="img-fluid shadow uc-rounded-sl lazy">
						</div>

						<div class="col-md-6 pt-5">
							<img data-src="'.base_url(get_content_page('homepage', 'our-story', 'image_1')).'" class="img-fluid shadow uc-rounded-sl lazy">
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container position-relative mb-5">
			<h3 class="uc-heading uc-heading-cg pb-2">Our <strong>Menu</strong> <span></span></h3>
		</div>

		<div class="uc-home-menu position-relative mb-5 pb-3">
			<div class="content-header">
				<div class="page-header header-filter lazy" data-bg="url('.base_url('assets/images/MD2_7822.JPG').')" style="height: 400px"></div>
			</div>

			<div class="container uc-row-menu">
				<div class="d-none d-md-block">
					<div class="row text-dark mb-4 mb-md-5">');

			$res = $this->db->sql_prepare("select * from uc_selected_menu order by id asc", "select");
			while ($row = $this->db->sql_fetch_array($res))
			{
				section_content('
						<div class="col-lg-4 mb-4 mb-md-0">
							<div class="bg-white shadow uc-rounded-sl">
								<div class="uc-img-thumb uc-rounded-sl lazy" data-bg="url('.base_url($row['thumbnail']).')"></div>
							
								<div class="p-3 p-md-4">
									<h6 class="font-weight-bold mb-3">'.$row['title'].'</h6>

									'.$row['description'].'
								</div>
							</div>
						</div>
				');
			}

		section_content('
						<!---
						<div class="col-lg-4 mb-4 mb-md-0">
							<div class="bg-white shadow uc-rounded-sl">
								<div class="uc-img-thumb uc-rounded-sl" style="background-image: url('.base_url('assets/images/MD2_7793.JPG').')"></div>
							
								<div class="p-3 p-md-4">
									<h6 class="font-weight-bold mb-3">Lorem ipsum dolor sit amet, consectetur</h6>

									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum rhoncus convallis. Morbi nec ante consequat, auctor mauris elementum, ultricies dolor.
								</div>
							</div>
						</div>

						<div class="col-lg-4 mb-4 mb-md-0">
							<div class="bg-white shadow uc-rounded-sl">
								<div class="uc-img-thumb uc-rounded-sl" style="background-image: url('.base_url('assets/images/MD2_0663.JPG').')"></div>
							
								<div class="p-3 p-md-4">
									<h6 class="font-weight-bold mb-3">Lorem ipsum dolor sit amet, consectetur</h6>

									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum rhoncus convallis. Morbi nec ante consequat, auctor mauris elementum, ultricies dolor.
								</div>
							</div>
						</div>

						<div class="col-lg-4 mb-4 mb-md-0">
							<div class="bg-white shadow uc-rounded-sl">
								<div class="uc-img-thumb uc-rounded-sl" style="background-image: url('.base_url('assets/images/MD2_7793.JPG').')"></div>
							
								<div class="p-3 p-md-4">
									<h6 class="font-weight-bold mb-3">Lorem ipsum dolor sit amet, consectetur</h6>

									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum rhoncus convallis. Morbi nec ante consequat, auctor mauris elementum, ultricies dolor.
								</div>
							</div>
						</div>
						--->
					</div>
				</div>

				<div id="carouselExampleControls" class="carousel slide d-block d-md-none" data-ride="carousel">
					<div class="carousel-inner row mx-0 text-dark mb-4 mb-md-5">
						<div class="col-12 carousel-item active">
							<div class="bg-white shadow uc-rounded-sl">
								<div class="uc-img-thumb uc-rounded-sl" style="background-image: url('.base_url('assets/images/MD2_7793.jpg').')"></div>
							
								<div class="p-3 p-md-4">
									<h6 class="font-weight-bold mb-3">Lorem ipsum dolor sit amet, consectetur</h6>

									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum rhoncus convallis. Morbi nec ante consequat, auctor mauris elementum, ultricies dolor.
								</div>
							</div>
						</div>

						<div class="col-12 carousel-item">
							<div class="bg-white shadow uc-rounded-sl">
								<div class="uc-img-thumb uc-rounded-sl" style="background-image: url('.base_url('assets/images/MD2_0663.jpg').')"></div>
							
								<div class="p-3 p-md-4">
									<h6 class="font-weight-bold mb-3">Lorem ipsum dolor sit amet, consectetur</h6>

									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum rhoncus convallis. Morbi nec ante consequat, auctor mauris elementum, ultricies dolor.
								</div>
							</div>
						</div>

						<div class="col-12 carousel-item">
							<div class="bg-white shadow uc-rounded-sl">
								<div class="uc-img-thumb uc-rounded-sl" style="background-image: url('.base_url('assets/images/MD2_7793.jpg').')"></div>
							
								<div class="p-3 p-md-4">
									<h6 class="font-weight-bold mb-3">Lorem ipsum dolor sit amet, consectetur</h6>

									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum rhoncus convallis. Morbi nec ante consequat, auctor mauris elementum, ultricies dolor.
								</div>
							</div>
						</div>
					</div>

					<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					
					<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>

				<div class="text-center">
					<a href="'.site_url('our_menu').'" class="btn btn-outline-brown-alt2 rounded-pill">View All</a>
				</div>
			</div>
		</div>

		<div class="container uc-home-gallery position-relative mb-5">
			<h3 class="uc-heading uc-heading-cg pb-2">Our <strong>Gallery</strong> <span></span></h3>
		</div>

		<div class="container position-relative">
			<div uk-slider class="pb-5">
				<div class="uk-position-relative uk-visible-toggle uk-dark" tabindex="-1">
					<ul class="ru-slider-cs uk-slider-items uk-child-width-1-3 uk-grid">');

		$res = $this->db->sql_prepare("select * from uc_gallery order by id desc", "select"); 
		while ($row = $this->db->sql_fetch_array($res))
		{
			section_content('
						<li class="uk-width-1-4@m">
							<div class="uk-card uk-card-default shadow" uk-slider-parallax="opacity:0.3,1,0.3">
								<div class="uk-card-media-top">
									<div class="uc-img-thumb rounded lazy" data-bg="url('.base_url($row['thumbnail']).')"></div>
								</div>
							</div>
						</li>');
		}

		section_content('
					</ul>

					<a class="uk-position-center-left uk-position-small uk-hidden-hover btn btn-outline-brown-alt" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
					<a class="uk-position-center-right uk-position-small uk-hidden-hover btn btn-outline-brown-alt" href="#" uk-slidenav-next uk-slider-item="next"></a>
				</div>

				<ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
			</div>
		</div>
		');
	}

}

?>