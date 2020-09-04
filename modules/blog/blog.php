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

class blog {

	// Default variable for load extension
	protected $ext;

	protected $db;

	protected $offset;

	protected $num_per_page;
	
	public function __construct() 
	{
		$this->ext = load_ext(['url', 'text']);

		// Connected to the database
		$this->db = load_db('default', 'MySQL');

		// Get data offset for pagination
		$this->offset = offset();

		// Get num per page for pagination
		$this->num_per_page = num_per_page();
	}

	public function index()
	{
		section_header('
		<div class="content-header position-relative mt-0">
			<div class="page-header header-filter header-small lazy" data-bg="url('.base_url(get_cover_image('blog', 'cover-image', 'image')).')">
				<div class="container">
					<div class="row mt-4">
						<div class="col-md-9 ml-auto mr-auto text-center" style="text-shadow: 2px 2px 3px rgba(0,0,0,0.7) !important">
							<h1 class="uc-heading-cg uc-font-coromorant-garamond">'.get_cover_image('blog', 'cover-image', 'title').'</h1>
							<h5 class="font-weight-light">'.get_cover_image('blog', 'cover-image', 'caption').'</h5>
						</div>
					</div>
				</div>
			</div>
		</div>');

		section_content('
		<div class="container mt-5">
			<div class="row">');

		$res = $this->db->sql_prepare("select * from uc_blog order by id desc limit $this->offset, $this->num_per_page", "select");
		while ($row = $this->db->sql_fetch_array($res))
		{
			section_content('
				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb rounded mb-3 lazy" data-bg="url('.base_url($row['thumbnail']).')"></div>
					<div class="d-flex flex-column">
						<div style="height: 100px;">
							<h6 class="font-weight-bold">'.$row['title'].'</h6>
							<p>'.character_limiter(strip_tags($row['content']), 100).'</p>
						</div>

						<div class="mt-auto text-right">
							<a href="'.site_url('blog/detail/'.$row['uri']).'">Read more ...</a>
						</div>
					</div>
				</div>');
		}

		$res_total = $this->db->sql_prepare("select count(*) as num from uc_blog", "select");
		$total = $this->db->sql_fetch_array($res_total);

		$this->pagination = load_lib('pagination', array($total['num']));
		$this->pagination->paras = site_url('blog');

		section_content('	
				<!---
				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_225109_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_225257_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">		
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_230319_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">		
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_231514_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">		
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_225109_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_225257_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">		
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_230319_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">		
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="uc-img-thumb uc-rounded-sr mb-3" style="background-image: url('.base_url('assets/images/LRM_EXPORT_20191027_231514_optimized-compress1.jpg').')"></div>
					<div class="d-flex flex-column">
						<div style="height: 120px;">		
							<h6 class="font-weight-bold">Lorem Ipsom Dolor Sit Amet</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>

						<div class="mt-auto text-right">
							<a href="#!">Read more ...</a>
						</div>
					</div>
				</div>
				--->

			</div>

			<span class="w-100 mx-3">'.$this->pagination->whole_num_bar('justify-content-center').'</span>
		</div>
		');
	}

	public function detail($uri)
	{
		$res = $this->db->sql_prepare("select * from uc_blog where uri = :uri");
		$bindParam = $this->db->sql_bindParam(['uri' => $uri], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['uri'])
		{
			section_content('<style>.card { margin-top: 8.6rem  !important;margin-right: 1.6rem  !important;margin-bottom: 2.6rem  !important;margin-left: 1.6rem  !important }</style>');
			error_page();
		}

		if ( ! empty($row['thumbnail']))
		{
			$thumbnail = '<div class="mb-3"><img src="'.base_url($row['thumbnail']).'" class="img-fluid rounded"></div>';
		}
		else 
		{
			$thumbnail = '';
		}

		$row['content'] = str_replace('<div style="background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;"', '<div style="background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;margin-bottom: 1rem;"', $row['content']);
		$row['content'] = str_replace('<img', '<img class="img-fluid rounded"', $row['content']);

		section_content('
		<style>
		.uc-navbar-transparent 
		{
			border-bottom: 3px #caa04a solid;
			background: #ffffff !important;
		}

		.uc-navbar-transparent a.uc-btn-menu
		{
			text-decoration: none;
			color: #333333 !important;
		}

		.uc-navbar-transparent .navbar-brand 
		{
			color: #333333 !important;
		}

		.uc-navbar-transparent .navbar-brand img
		{
			width: 100px;
		}

		.uc-navbar-transparent .navbar-nav .nav-link,
		.uc-navbar-transparent .navbar-nav .active > .nav-link 
		{
			color: #333333 !important;
		}

		.uc-navbar-transparent .navbar-nav .nav-link:hover,
		.uc-navbar-transparent .navbar-nav .nav-link:focus 
		{
			color: #ffffff !important;
			background: #caa04a;
			border-radius: 0.2rem;
			transition: all 300ms ease 0s;
		}

		.uc-navbar-transparent .uc-logo-color 
		{
			filter: none;
		}
		</style>

		<div class="uc-bg-wimg py-5" style="background-image: url('.base_url('assets/images/lightpaperfibers-2.png').')">
			<div class="container mb-5" style="margin-top: 6.6rem">
				<div class="row mb-5">
					<div class="col-md-8 mb-4 mb-md-0">
						<div class="bg-white p-3 p-md-4 border rounded shadow">
							'.$thumbnail.'
							'.$row['content'].'
						</div>
					</div>

					<div class="col-md-4">
						<div class="bg-white border rounded shadow">
							<div class="h6 mb-0 p-3 p-md-4 font-weight-bold border-bottom">
								Recent Blogs
							</div>

							<div class="list-group list-group-flush">');

				$res_ra = $this->db->sql_prepare("select * from uc_blog order by rand() desc limit 5", "select");
				while ($row_ra = $this->db->sql_fetch_array($res_ra))
				{
					$row['uri'] ??= NULL;

					if ($row['uri'] != $row_ra['uri'])
					{
						section_content('
							<a href="'.site_url('blog/detail/'.$row_ra['uri']).'" class="list-group-item"><i class="fas fa-chevron-right fa-fw mr-2"></i> '.$row_ra['title'].'</a>
						');
					}
				}

		section_content('
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		');
	}
}

?>