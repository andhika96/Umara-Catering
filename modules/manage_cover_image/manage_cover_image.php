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

class manage_cover_image {

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
		do_access($this->session->userdata('id'), [99]);

		// Check login
		// This from Common Function
		check_login();
	}

	public function index()
	{
		set_title('Manage Cover Image');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<div class="bg-white shadow-sm rounded">
			<div class="p-3 mx-2 border-bottom">
				<h5 class="m-0"><i class="fas fa-images mr-2"></i> Manage Cover Image</h5>
			</div>

			<div class="row p-4">');

			$res = $this->db->sql_prepare("select * from uc_cover_image order by id asc", "select");
			while ($row = $this->db->sql_fetch_array($res))
			{
				section_content('
				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_cover_image/edit/'.$row['uri']).'">
					<i class="far fa-images fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> '.$row['parent'].'</h5>
				</a>');
			}

		section_content('
			</div>	
		</div>
		');
	}

	public function edit($uri)
	{
		set_title('Edit Cover Image');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_cover_image where uri = :uri");
		$bindParam = $this->db->sql_bindParam(['uri' => $uri], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		$row['uri'] = isset($row['uri']) ? $row['uri'] : NULL;

		if ( ! $row['uri'] || ! is_string($uri))
		{
			error_page();
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			if (isset($_FILES['cover']))
			{
				$dir = date("Ym", time());
				$s_folder = './contents/userfiles/cover_images/'.$dir.'/';

				// For database only without dot and slash at the front folder
				$x_folder = 'contents/userfiles/cover_images/'.$dir.'/';

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

				if ( ! $upload_logo->do_upload('cover'))
				{
					if ($_FILES['cover']['error'] != 4)
					{
						$error = array('error' => $upload_logo->display_errors('<span>', '</span>'));

						foreach ($error as $key => $value) 
						{
							$error = $value;
						}
					}

					$cover = $row['image'];
				}
				else 
				{
					if ($row['image'])
					{
						unlink($row['image']);
					}

					$cover = $x_folder.$upload_logo->data('file_name');
				}
			}

			if ( ! $_FILES['cover'])
			{
				$error = 'Please upload the cover image';
			}

			if ( ! strlen($error))
			{
				$update_data = [
					'image'		=>	$cover,
					'title'		=>	$this->input->post('title'),
					'caption'	=>	$this->input->post('caption'),
				];

				$this->db->sql_update($update_data, 'uc_cover_image', ['uri' => $row['uri']]);

				redirect('manage_cover_image', 1);
			}
			else
			{
				sys_notice($error);
			}
		}
		
		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3 h5"><i class="fas fa-columns mr-2"></i> Edit Cover Image</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_cover_image').'">Manage Cover Image</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Cover Image '.$row['parent'].'</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_cover_image/edit/'.$row['uri']).'" enctype="multipart/form-data" method="post">
					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="title">Title</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="title" class="form-control" id="title" value="'.$row['title'].'">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="caption">Caption</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="caption" class="form-control" id="caption" value="'.$row['caption'].'">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="cover">Cover Image</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<img src="'.base_url($row['image']).'" class="img-fluid rounded mb-3">

							<div class="custom-file">
								<input type="file" name="cover" class="custom-file-input" id="cover">
								<label class="custom-file-label" for="cover">Choose file</label>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-6 offset-3">
							<input type="hidden" name="step" value="post">
							<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
							<input type="submit" class="btn btn-umara btn-block btn-lg" value="Update">
						</div>
					</div>
				</form>
			</div>
		</div>');
	}

}

?>