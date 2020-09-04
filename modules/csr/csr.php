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

class csr {

	protected $session;

	protected $db;

	// Default variable for load extension
	protected $ext;

	protected $offset;

	protected $num_per_page;
	
	public function __construct() 
	{
		// Load Session
		$this->session = load_lib('session');

		// Connected to the database
		$this->db = load_db('default', 'MySQL');
		
		$this->ext = load_ext(['url', 'text']);

		// Get data offset for pagination
		$this->offset = offset();

		// Get num per page for pagination
		$this->num_per_page = num_per_page();
	}

	public function index()
	{
		section_header('
		<div class="content-header">
			<div class="page-header header-filter header-small parallax-window" data-parallax="scroll" data-image-src="'.base_url('assets/images/startup-593341_1280.jpg').'">
				<div class="container">
					<div class="row">
						<div class="col-md-9 ml-auto mr-auto text-center" style="text-shadow: 0.1rem 0.1rem rgba(0, 0, 0, 0.5) !important">
							<h1 class="title">CSR Program</h1>
							<h4 class="font-weight-light">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor</h4>
						</div>
					</div>
				</div>
			</div>
		</div>');

		section_content('
		<div class="container my-5">
			<div>
				<div class="text-center text-umara pb-4 mb-5 border-bottom">
					<h2 class="font-weight-bold mb-0">CSR Program</h2>
				</div>

				<div class="row">
					<div class="col-12">
						<div class="row">');

					$res = $this->db->sql_prepare("select * from ug_csr order by id desc limit $this->offset, $this->num_per_page", "select");
					while ($row = $this->db->sql_fetch_array($res))
					{
						if ( ! empty($row['thumbnail']))
						{
							$thumbnail = '<div class="ug-img-thumb ug-img-thumb-infolomba rounded-top" style="background-image: url('.base_url($row['thumbnail']).')"></div>';
						}
						else 
						{
							$thumbnail = '<span class="fa-layers fa-fw" style="width: 100%;height: 228px"><i class="fas fa-square" style="color:#dc772e"></i><i class="fa-inverse fab fa-elementor" data-fa-transform="shrink-6"></i></span>';
						}

						section_content('
							<div class="col-md-4 d-flex mb-4">
								<div class="card w-100 shadow-sm">
									<div class="fa-8x align-self-center text-center card-img-top ug-card-img-infolomba" style="background: #dc772e;">
										'.$thumbnail.'

										<div class="position-relative">
											<div class="shape shape-bottom shape-fluid-x svg-shim text-white">
												<svg viewBox="0 0 2880 480" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M2160 0C1440 240 720 240 720 240H0V480H2880V0H2160Z" fill="currentColor"></path>
												</svg>
											</div>
										</div>
									</div>

									<div class="card-body py-3">
										<h5 class="card-title text-truncate"><a href="'.site_url('csr/'.$row['uri']).'">'.$row['title'].'</a></h5>
										<p class="card-text">'.character_limiter(strip_tags($row['content']), 124).'</p>
									</div>

									<div class="card-footer d-flex bg-white py-3">
										<div class="d-flex align-items-center">
											<span class="mr-2">'.avatar($row['user_id'], 34).'</span> <strong>'.get_client($row['user_id'], 'fullname').'</strong>
										</div>

										<p class="text-muted mt-2 mb-0 ml-auto">'.get_date($row['created'], 'date').'</p>
									</div>
								</div>
							</div>');
					}

		if ( ! $this->db->sql_counts($res))
		{
			section_content('<div class="col-12 text-center text-danger p-3">No content</div>');
		}

		$res_total = $this->db->sql_prepare("select count(*) as num from ug_csr", "select");
		$total = $this->db->sql_fetch_array($res_total);

		$this->pagination = load_lib('pagination', array($total['num']));
		$this->pagination->paras = site_url('csr');

		section_content('
							<span class="w-100 mx-3">'.$this->pagination->whole_num_bar('justify-content-center').'</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		');
	}

	public function detail($uri)
	{
		$res = $this->db->sql_prepare("select * from ug_csr where uri = :uri");
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

		$res_gallery = $this->db->sql_prepare("select * from ug_photos where csr_id = :csr_id");
		$bindParam_gallery = $this->db->sql_bindParam(['csr_id' => $row['id']], $res_gallery);
		$row_gallery = $this->db->sql_fetch_array($bindParam_gallery);

		section_header('
		<div class="content-header">
			<div class="page-header header-filter header-small parallax-window" data-parallax="scroll" data-image-src="'.base_url('assets/images/startup-593327_1280.jpg').'">
				<div class="container">
					<div class="row" style="margin-top: -3rem">
						<div class="col-md-9 ml-auto mr-auto text-center" style="text-shadow: 0.1rem 0.1rem rgba(0, 0, 0, 0.5) !important">
							<h3 class="title">'.$row['title'].'</h3>
						</div>
					</div>
				</div>
			</div>
		</div>');

		section_content('
		<div class="container">
			<div class="row mb-5" style="margin-top: -6rem">
				<div class="col-md-8 mb-4 mb-md-0">
					<div class="bg-white p-3 p-md-4 border rounded shadow">
						'.$thumbnail.'
						'.$row['content'].'');

		if ($this->db->sql_counts($bindParam_gallery))
		{
			section_content('
						<h3 class="mt-4 mb-3">Gallery</h3>
						<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true">
						    <ul class="uk-slider-items uk-grid">');

					for ($i = 0; $i < $row['photos']; $i++) 
					{
						section_content('
								<li class="uk-width-3-4">
									<div class="uk-panel">
										<img src="'.base_url($row_gallery['image_'.$i]).'" class="img-fluid rounded">
									</div>
								</li>
						');
					}

			section_content('
							</ul>

							<a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
							<a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
						</div>');
		}

		section_content('
					</div>
				</div>

				<div class="col-md-4">
					<div class="bg-white border rounded shadow">
						<div class="h6 mb-0 p-3 p-md-4 font-weight-bold border-bottom">
							Recent Articles
						</div>

						<div class="list-group list-group-flush">');

				$res_ra = $this->db->sql_prepare("select * from ug_csr order by rand() desc limit 5", "select");
				while ($row_ra = $this->db->sql_fetch_array($res_ra))
				{
					$row['uri'] = isset($row['uri']) ? $row['uri'] : NULL;

					if ($row['uri'] != $row_ra['uri'])
					{
						section_content('
							<a href="'.site_url('csr/'.$row_ra['uri']).'" class="list-group-item"><i class="fas fa-chevron-right fa-fw mr-2"></i> '.$row_ra['title'].'</a>
						');
					}
				}

		section_content('
						</div>
					</div>
				</div>
			</div>
		</div>
		');
	}

}

?>