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

class selected_menu {

	protected $session;

	protected $input;

	protected $db;

	protected $csrf;

	protected $offset;

	protected $num_per_page;

	protected $error_display = NULL;

	protected $ext;

	public function __construct()
	{
		// Load Session
		$this->session = load_lib('session');

		// Load Input Library
		$this->input = load_lib('input');

		// Connected to the database
		$this->db = load_db('default', 'MySQL');

		// Load URL Extension
		$this->url = load_ext(['url', 'string']);

		// Get data offset for pagination
		$this->offset = offset();

		// Get num per page for pagination
		$this->num_per_page = num_per_page();

		// Active MenuF Protection
		get_data_global('SEC')->set_csrf(1);

		$this->csrf = [
			'name' => get_data_global('SEC')->get_csrf_token_name(),
			'hash' => get_data_global('SEC')->get_csrf_hash()
		];

		// Load extension
		$this->ext = load_ext(['url']);

		// Authentication user access
		// This from Common Function
		do_access($this->session->userdata('id'), [99]);

		// Check login
		// This from Common Function
		check_login();
	}

	public function index()
	{
		set_title('Selected Menu');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<div class="bg-white shadow-sm rounded">
			<div class="p-3 mx-2 border-bottom">
				<h5 class="m-0"><i class="fas fa-check-double mr-2"></i> Manage Menu Content</h5>
			</div>

			<div class="row p-4">
				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('selected_menu/list').'">
					<i class="fas fa-folder fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> List Selected Menu</h5>
				</a>
			</div>	
		</div>
		');
	}

	public function edit($bid = 0)
	{
		set_title('Edit Selected Menu');
		
		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_selected_menu where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $bid], $res);
		$row = $this->db->sql_fetch_array($bindParam);


		if ( ! $row['id'] || ! is_numeric($bid))
		{
			error_page();
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$error = NULL;

			if ( ! $this->input->post('title'))
			{
				$error = 'Please input title';
			}

			if ( ! $this->input->post('description'))
			{
				$error = 'Description is empty';
			}

			if (isset($_FILES['thumbnail']))
			{
				$dir = date("Ym", time());
				$s_folder = './contents/userfiles/photos/'.$dir.'/';

				// For database only without dot and slash at the front folder
				$x_folder = 'contents/userfiles/photos/'.$dir.'/';

				if ( ! is_dir($s_folder)) 
				{
					mkdir($s_folder, 0777);
				}

				$config_logo['upload_path']		= $s_folder;
				$config_logo['allowed_types']	= 'jpg|jpeg|png';
				$config_logo['overwrite']		= TRUE;
				$config_logo['remove_spaces']	= TRUE;
				$config_logo['encrypt_name']	= TRUE;
				$config_logo['max_size']		= 8000;

				$upload_logo = load_lib('upload', $config_logo);

				if ( ! $upload_logo->do_upload('thumbnail'))
				{
					if ($_FILES['thumbnail']['error'] != 4)
					{
						$error = array('error' => $upload_logo->display_errors('<span>', '</span>'));

						foreach ($error as $key => $value) 
						{
							$error = $value;
						}
					}

					$thumbnail = $row['thumbnail'];
				}
				else 
				{
					if ($row['thumbnail'])
					{
						unlink($row['thumbnail']);
					}

					$thumbnail = $x_folder.$upload_logo->data('file_name');
				}
			}

			if ( ! $_FILES['thumbnail'])
			{
				$error = 'Please upload the thumbnail image';
			}

			if ( ! strlen($error))
			{
				$data = [
					'title'			=>	$this->input->post('title'),
					'thumbnail'		=>	$thumbnail,
					'description'	=>	$this->input->post('description'),
					'updated'		=>	time()
				];

				$this->db->sql_update($data, "uc_selected_menu", ['id' => $row['id']]);

				redirect('selected_menu/list', 1);
			}
			else
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-pencil-alt mr-2"></i> Edit Menu</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('selected_menu').'">Manage Menu</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('selected_menu/list').'">List Selected Menu</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('selected_menu/edit/'.$row['id']).'" enctype="multipart/form-data" method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Title</label>
								<input type="text" name="title" placeholder="Title here ..." class="form-control" value="'.$row['title'].'">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Thumbnail</label>
								
								<div class="custom-file">
									<input type="file" name="thumbnail" class="custom-file-input" id="thumbnail">
									<label class="custom-file-label" for="thumbnail">Choose file</label>
								</div>
							</div>
						</div>
					</div>
				
					<div class="form-group mb-3">
						<label>Description</label>
						<textarea name="description" placeholder="Description here ..." rows="3" class="form-control">'.$row['description'].'</textarea>
					</div>

					<div class="form-group text-right">
						<input type="hidden" name="step" value="post">
						<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
						<input type="submit" class="btn btn-primary" value="Update">
					</div>
				</div>
			</form>
		</div>
		');
	}

	public function list()
	{
		set_title('List Selected Menu');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<style>
		.ar-img-thumb 
		{
			width:  auto;
			height: 130px;
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
		}

		.list-group-item:first-child 
		{
			border-top: 0 !important;
		}
		</style>

		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-book mr-2"></i> List Selected Menu</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('selected_menu').'">Manage Menu</a></li>
						<li class="breadcrumb-item active" aria-current="page">List Selected Menu</li>
					</ol>
				</nav>
			</div>

			<div class="card-body py-1 px-3">
				<ul class="list-group list-group-flush">');

		$res = $this->db->sql_prepare("select * from uc_selected_menu order by id desc limit $this->offset, $this->num_per_page", "select");
		while ($row = $this->db->sql_fetch_array($res))
		{
			if ( ! empty($row['thumbnail']))
			{
				$thumbnail = '<div class="ar-img-thumb rounded mr-4" style="width: 150px;height: 75px !important;background-image: url('.base_url($row['thumbnail']).')"></div>';
			}
			else 
			{
				$thumbnail = '<span class="fa-layers fa-fw" style="width: 150px;height: 75px"><i class="fas fa-square" style="color:#3f51b5"></i><i class="fa-inverse fab fa-elementor fa-2x" data-fa-transform="shrink-6"></i></span>';
			}

			section_content('
					<li class="list-group-item list-group-item-action px-2">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<h6>'.$row['title'].'</h6>
								<div class="text-muted small">'.get_date($row['updated']).'</div>
							</div>

							<div>
								<div class="d-flex justify-content-between align-items-center pb-1">
									<div class="mr-4 rounded" style="width: 150px;height: 75px;background: #3f51b5;">
										'.$thumbnail.'
									</div>

									<div class="dropdown">
										<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>

										<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="'.site_url('selected_menu/edit/'.$row['id']).'"><i class="fas fa-pencil-alt fa-fw mr-1"></i> Edit</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
			');
		}

		$res_total = $this->db->sql_prepare("select count(*) as num from uc_selected_menu", "select");
		$total = $this->db->sql_fetch_array($res_total);

		$this->pagination = load_lib('pagination', array($total['num']));
		$this->pagination->paras = site_url('selected_menu/listmenu');

		section_content('
				</ul>

				<div class="pt-3">'.$this->pagination->whole_num_bar('justify-content-center').'</div>
			</div>
		</div>');
	}

}

?>