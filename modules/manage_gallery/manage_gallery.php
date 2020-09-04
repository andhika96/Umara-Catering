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

class manage_gallery {

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
		$this->url = load_ext(['url', 'text']);

		// Get data offset for pagination
		$this->offset = offset();

		// Get num per page for pagination
		$this->num_per_page = num_per_page();

		// Active CSRF Protection
		get_data_global('SEC')->set_csrf(1);

		$this->csrf = [
			'name' => get_data_global('SEC')->get_csrf_token_name(),
			'hash' => get_data_global('SEC')->get_csrf_hash()
		];

		// Load extension
		$this->ext = load_ext(['url']);

		// Authentication user access
		// This from Common Function
		do_access($this->session->userdata('id'), [99, 96]);

		// Check login
		// This from Common Function
		check_login();
	}

	public function index()
	{
		set_title('Manage Gallery');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<div class="bg-white shadow-sm rounded">
			<div class="p-3 mx-2 border-bottom">
				<h5 class="m-0"><i class="fas fa-image mr-2"></i> Manage Gallery</h5>
			</div>

			<div class="row p-4">
				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_gallery/add').'">
					<i class="fas fa-plus fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Add Photo</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_gallery/list').'">
					<i class="fas fa-images fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> List Photos</h5>
				</a>
			</div>	
		</div>
		');
	}

	public function add()
	{
		set_title('Add Photo');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$error = NULL;

			if (isset($_FILES['photo']))
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
				$config_logo['max_size']		= 16000;

				$upload_logo = load_lib('upload', $config_logo);

				if ( ! $upload_logo->do_upload('photo'))
				{
					if ($_FILES['photo']['error'] != 4)
					{
						$error = array('error' => $upload_logo->display_errors('<span>', '</span>'));

						foreach ($error as $key => $value) 
						{
							$error = $value;
						}
					}

					$photo = FALSE;
				}
				else 
				{
					$photo = $x_folder.$upload_logo->data('file_name');

					$config_img['image_library']	= 'gd2';
					$config_img['source_image']		= $photo;
					$config_img['create_thumb']		= TRUE;
					$config_img['maintain_ratio']	= TRUE;
					$config_img['width']			= 350;
					$config_img['height']			= 225;

					$image_lib = load_lib('image', $config_img);
					$image_lib->resize();
				}
			}

			if ( ! strlen($error))
			{
				$new_data = [
					'thumbnail' =>	$x_folder.$upload_logo->data('raw_name').'_thumb'.$upload_logo->data('file_ext'),
					'photo'		=>	$photo,
					'created'	=>	time(),
				];

				$this->db->sql_insert($new_data, 'uc_gallery');

				redirect('manage_gallery', 1);
			}
			else
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3 h5"><i class="fas fa-plus mr-2"></i> Add Photo</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_gallery').'">Manage Gallery</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Photo</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_gallery/add').'" enctype="multipart/form-data" method="post">
					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="photo">Photo</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<div class="custom-file">
								<input type="file" name="photo" class="custom-file-input" id="photo">
								<label class="custom-file-label" for="photo">Choose file</label>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-6 offset-3">
							<input type="hidden" name="step" value="post">
							<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
							<input type="submit" class="btn btn-brown btn-block btn-lg" value="Upload">
						</div>
					</div>
				</form>
			</div>
		</div>');
	}

	public function edit($id)
	{
		set_title('Edit Photo');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_gallery where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $id], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		$row['id'] = isset($row['id']) ? $row['id'] : NULL;

		if ( ! $row['id'] || ! is_numeric($id))
		{
			error_page();
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$error = NULL;

			if (isset($_FILES['photo']))
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
				$config_logo['max_size']		= 16000;

				$upload_logo = load_lib('upload', $config_logo);

				if ( ! $upload_logo->do_upload('photo'))
				{
					if ($_FILES['photo']['error'] != 4)
					{
						$error = array('error' => $upload_logo->display_errors('<span>', '</span>'));

						foreach ($error as $key => $value) 
						{
							$error = $value;
						}
					}

					$photo = $row['photo'];
				}
				else 
				{
					if (file_exists($row['thumbnail']) && file_exists($row['photo']))
					{
						unlink($row['thumbnail']);
						unlink($row['photo']);
					}

					$photo = $x_folder.$upload_logo->data('file_name');

					$config_img['image_library']	= 'gd2';
					$config_img['source_image']		= $photo;
					$config_img['create_thumb']		= TRUE;
					$config_img['quality']			= '90%';
					$config_img['maintain_ratio']	= TRUE;
					$config_img['width']			= 250;
					$config_img['height']			= 225;

					$image_lib = load_lib('image', $config_img);
					$image_lib->resize();
				}
			}

			if ( ! strlen($error))
			{
				$update_data = [
					'thumbnail' =>	$x_folder.$upload_logo->data('raw_name').'_thumb'.$upload_logo->data('file_ext'),
					'photo'		=>	$photo,
					'created'	=>	time(),
				];

				$this->db->sql_update($update_data, 'uc_gallery', ['id' => $row['id']]);

				redirect('manage_gallery', 1);
			}
			else
			{
				sys_notice($error);
			}
		}
		
		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3 h5"><i class="fas fa-plus mr-2"></i> Edit Photo</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_gallery').'">Manage Gallery</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('manage_gallery/list').'">List Photo</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Photo</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_gallery/edit/'.$row['id']).'" enctype="multipart/form-data" method="post">
					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="photo">Photo</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<div class="uc-img-thumb rounded mr-4 my-3" style="width: 100%;height: 300px !important;background-image: url('.base_url($row['photo']).')"></div>					

							<div class="custom-file">
								<input type="file" name="photo" class="custom-file-input" id="photo">
								<label class="custom-file-label" for="photo">Choose file</label>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-6 offset-3">
							<input type="hidden" name="step" value="post">
							<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
							<input type="submit" class="btn btn-brown btn-block btn-lg" value="Upload">
						</div>
					</div>
				</form>
			</div>
		</div>');
	}

	public function list()
	{
		set_title('List Photo');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);
		
		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3 h5"><i class="fas fa-plus mr-2"></i> Edit Photo</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_gallery').'">Manage Gallery</a></li>
						<li class="breadcrumb-item active" aria-current="page">List Photo</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<div class="row">');

		$res = $this->db->sql_prepare("select * from uc_gallery order by id desc limit $this->offset, $this->num_per_page", "select"); 
		while ($row = $this->db->sql_fetch_array($res))
		{
			section_content('
				<div class="col-md-3 mb-4">
					<a href="'.site_url('manage_gallery/edit/'.$row['id']).'" class="text-decoration-none text-dark">
						<img src="'.base_url($row['thumbnail']).'" class="img-fluid rounded">
						
						<div class="mt-2 text-center text-muted">
							Click photo to edit
						</div>
					</a>
				</div>
			');
		}

		section_content('
				</div>
			</div>
		</div>');
	}

}

?>