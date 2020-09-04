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

class about_us {

	// Default variable for load extension
	protected $ext;
	
	public function __construct() 
	{
		$this->ext = load_ext(['url']);
	}

	public function index()
	{
		set_title('About Us');

		section_header('
		<div class="content-header position-relative">
			<div class="page-header header-filter header-small lazy" data-bg="url('.base_url(get_cover_image('about-us', 'cover-image', 'image')).')">
				<div class="container">
					<div class="row mt-4">
						<div class="col-md-9 ml-auto mr-auto text-center" style="text-shadow: 2px 2px 3px rgba(0,0,0,0.7) !important">
							<h2 class="uc-h3 uc-heading-cg">'.get_cover_image('about-us', 'cover-image', 'title').'</h2>
							<h5 class="font-weight-light">'.get_cover_image('about-us', 'cover-image', 'caption').'</h5>
						</div>
					</div>
				</div>
			</div>
		</div>');

		section_content('
		<div class="container my-5">
			<div class="lead font-weight-normal text-center mb-5 pb-5 border-bottom">
				'.get_content_page('aboutus', 'about-us', 'short_desc').'
			</div>

			<div class="mb-5 pb-5">
				<div class="row">
					<div class="col-md-7 d-flex justify-content-center align-self-center">
						<div>
							<p>
								'.nl2br(get_content_page('aboutus', 'about-us', 'description')).'
							</p>
						</div>
					</div>

					<div class="col-md-5">
						<div class="uc-img-thumb uc-img-thumb350 shadow rounded lazy" data-bg="url('.base_url(get_content_page('aboutus', 'about-us', 'image_0')).')"></div>					
					</div>
				</div>
			</div>

			<div class="row no-gutters">
				<div class="col-md-6">
					<img data-src="'.base_url(get_content_page('aboutus', 'our-vission', 'image_0')).'" class="img-fluid lazy">
				</div>

				<div class="col-md-6 d-flex justify-content-center align-self-center">
					<div class="p-4">
						<h3 class="uc-heading uc-heading-cg mb-4 pb-2">Our <strong>Vission</strong> <span></span></h3>

						<p>
							'.nl2br(get_content_page('aboutus', 'our-vission', 'description')).'
						</p>
					</div>
				</div>

				<div class="col-md-6 d-flex justify-content-center align-self-center">
					<div class="p-4">
						<h3 class="uc-heading uc-heading-cg mb-4 pb-2">Our <strong>Mission</strong> <span></span></h3>

						<p>
							'.nl2br(get_content_page('aboutus', 'our-mission', 'description')).'
						</p>
					</div>
				</div>

				<div class="col-md-6">
					<img data-src="'.base_url(get_content_page('aboutus', 'our-mission', 'image_0')).'" class="img-fluid lazy">
				</div>
			</div>
		</div>
		');
	}
}

?>