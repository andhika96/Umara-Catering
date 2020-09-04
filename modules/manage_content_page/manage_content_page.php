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

class manage_content_page {

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
		set_title('Manage Content Page');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<div class="bg-white shadow-sm rounded">
			<div class="p-3 mx-2 border-bottom">
				<h5 class="m-0"><i class="fas fa-columns mr-2"></i> Manage Content Page</h5>
			</div>

			<div class="row p-4">
				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_content_page/edit/homepage').'">
					<i class="fas fa-home fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Home Page</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_content_page/edit/aboutus').'">
					<i class="fas fa-info-circle fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> About Us</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_content_page/edit/contact_us').'">
					<i class="fas fa-headset fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Contact Us</h5>
				</a>
			</div>	
		</div>
		');
	}

	public function edit($key)
	{
		set_title('Edit Content');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$title = ($key == 'contact_us') ? 'Contact Us' : $key;

		if ($key == 'contact_us')
		{
			$res = $this->db->sql_prepare("select * from uc_contact_us where id = 1", "select");
			$row = $this->db->sql_fetch_array($res);

			if ( ! $row['id'] || is_numeric($key))
			{
				error_page();
			}
		}
		else
		{
			$res = $this->db->sql_prepare("select * from uc_page_content where parent = :parent");
			$bindParam = $this->db->sql_bindParam(['parent' => $key], $res);
			$row = $this->db->sql_fetch_array($res);

			if ( ! $row['parent'] || $key == 'contact_us' || is_numeric($key))
			{
				error_page();
			}
		}

		if ($key == 'contact_us')
		{
			if ($this->input->post('step') && $this->input->post('step') == 'contact_us') 
			{
				$update_data = [
					'master_email'	 =>	$this->input->post('email'),
					'embed_code'	 =>	$this->input->post('embed_code'),
					'detail_address' =>	$this->input->post('detail_address'),
					'phone_number' =>	$this->input->post('phone_number')
				];

				if ($row['id'])
				{
					$this->db->sql_update($update_data, 'uc_contact_us', ['id' => $row['id']]);
				}
				else 
				{
					$this->db->sql_insert($update_data, 'uc_contact_us');
				}

				redirect('manage_content_page/edit/contact_us', 1);
			}

			section_content('
			<div class="card arv2-admin-card">
				<div class="card-header card-header-divider">
					<div class="mb-3 h5"><i class="fas fa-columns mr-2"></i> Edit Content</div>

					<nav aria-label="breadcrumb">
						<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
							<li class="breadcrumb-item"><a href="'.site_url('manage_content_page').'">Manage Content Page</a></li>
							<li class="breadcrumb-item active" aria-current="page">Edit Content</li>
						</ol>
					</nav>
				</div>

				<div class="card-body">
					<form action="'.site_url('manage_content_page/edit/contact_us').'" method="post">
						<div class="form-group row mb-4">
							<div class="col-md-4 mb-4 mb-md-0">
								<label>Email</label>
								<input type="text" name="email" placeholder="Enter email" class="form-control" value="'.$row['master_email'].'">
								<div class="text-muted mt-1" style="font-size: 12px">You can receive emails from users through this form</div>
							</div>

							<div class="col-md-4 mb-4 mb-md-0">
								<label>Phone Number</label>
								<input type="text" name="phone_number" placeholder="Enter phone number" class="form-control" value="'.$row['phone_number'].'">
							</div>

							<div class="col-md-4 mb-4 mb-md-0">
								<label>Google Maps</label>
								<input type="text" name="embed_code" placeholder="Enter your Google Maps (Embed Code)" class="form-control" value="'.$row['embed_code'].'">
								<div class="text-muted mt-1" style="font-size: 12px">How to get embed code <a href="https://support.google.com/maps/answer/144361?co=GENIE.Platform%3DDesktop&hl=en" target="_blank">Click Here</a></div>
							</div>
						</div>

						<div class="form-group mb-4">
							<label>Detail Address</label>
							<textarea name="detail_address" rows="4" placeholder="Description here" class="form-control">'.$row['detail_address'].'</textarea>
						</div>

						<div class="form-group">
							<input type="hidden" name="step" value="contact_us">
							<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
							<input type="submit" class="btn btn-umara btn-block btn-lg" value="Save">
						</div>
					</form>
				</div>	
			</div>
			');
		}
		else
		{
			section_content('
			<div class="card arv2-admin-card">
				<div class="card-header card-header-divider">
					<div class="mb-3 h5"><i class="fas fa-columns mr-2"></i> Edit Content</div>

					<nav aria-label="breadcrumb">
						<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
							<li class="breadcrumb-item"><a href="'.site_url('manage_content_page').'">Manage Content Page</a></li>
							<li class="breadcrumb-item active" aria-current="page">Edit Content</li>
						</ol>
					</nav>
				</div>

				<div class="card-body">
					<div class="row">');

				$res_list_pg = $this->db->sql_prepare("select * from uc_page_content where parent = :parent order by id");
				$bindParam_list_pg = $this->db->sql_bindParam(['parent' => $key], $res_list_pg);
				while ($row_list_pg = $this->db->sql_fetch_array($bindParam_list_pg))
				{
					section_content('
						<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_content_page/detail/'.$row_list_pg['type']).'">
							<i class="fas fa-columns fa-4x"></i>
							<h5 class="mb-0 mt-2 font-weight-light"> '.$row_list_pg['title'].'</h5>
						</a>
					');
				}

			section_content('
					</div>
				</div>
			</div>
			');
		}
	}

	public function detail($key)
	{
		set_title('Manage Content Page');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

			$res = $this->db->sql_prepare("select * from uc_page_content where type = :type");
			$bindParam = $this->db->sql_bindParam(['type' => $key], $res);
			$row = $this->db->sql_fetch_array($res);

			if ( ! $row['type'] || $key == 'contact_us' || is_numeric($key))
			{
				error_page();
			}

			if ($this->input->post('step') && $this->input->post('step') == 'post') 
			{
				$error = NULL;
				$image = NULL;
				$is_redirect = 1;
				$is_uploaded_photo = 1;

				if (isset($_FILES['images']))
				{
					for ($i = 0; $i < $row['total_image']; $i++) 
					{
						if ($_FILES['images']['name'][$i] != NULL)
						{
							$dir = date("Ym", time());
							$s_folder = './contents/userfiles/content_page/'.$dir.'/';

							// For database only without dot and slash at the front folder
							$x_folder = 'contents/userfiles/content_page/'.$dir.'/';

							if ( ! is_dir($s_folder)) 
							{
								mkdir($s_folder, 0777);
							}

							$configs['upload_path']		= $s_folder;
							$configs['allowed_types']	= 'jpg|jpeg|png';
							$configs['encrypt_name']	= TRUE;
							$configs['overwrite']		= TRUE;
							$configs['remove_spaces']	= TRUE;
							$configs['max_size']		= 8000;

							$upload = load_lib('upload', $configs);

							$_FILES['file']['name'] = $_FILES['images']['name'][$i];
							$_FILES['file']['type'] = $_FILES['images']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['images']['error'][$i];
							$_FILES['file']['size'] = $_FILES['images']['size'][$i];

							if ( ! $upload->do_upload('file'))
							{
								if ($_FILES['file']['error'] != 4)
								{
									$error = ['error' => $upload->display_errors('<span>', '</span>')];

									foreach($error as $keys => $values) 
									{
										$error = $values;
									}
								}
							}
							else 
							{
								if ($row['id'] && file_exists($row['image_'.$i]))
								{
									unlink($row['image_'.$i]);
								}

								$image = $x_folder.$upload->data('file_name');
							}

							if ( ! strlen($error))
							{
								$is_redirect = 0;
								$this->db->sql_update(['image_'.$i => $image], 'uc_page_content', ['type' => $row['type']]);
							}
							else
							{
								sys_notice($error);
							}
						}
					}

					if ($is_redirect == 0)
					{
						$is_uploaded_photo = 0;
					}
				}

				if ( ! $this->input->post('title'))
				{
					$error = 'Please fill all the require blanks';
				}

				if ( ! strlen($error) || $is_uploaded_photo == 0)
				{
					$update_data = [
						'title'			=>	$this->input->post('title'),
						'short_desc'	=>	$this->input->post('short_desc'),
						'description'	=>	$this->input->post('description'),
					];

					$this->db->sql_update($update_data, 'uc_page_content', ['type' => $row['type']]);

					redirect('manage_content_page/detail/'.$row['type'], 1);
				}
				else
				{
					sys_notice($error);
				}
			}

			section_content('
			<div class="card arv2-admin-card">
				<div class="card-header card-header-divider">
					<div class="mb-3 h5"><i class="fas fa-info-circle mr-2"></i> Edit Content</div>

					<nav aria-label="breadcrumb">
						<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
							<li class="breadcrumb-item"><a href="'.site_url('manage_content_page').'">Manage Content Page</a></li>
							<li class="breadcrumb-item"><a href="'.site_url('manage_content_page/edit/'.$row['parent']).'">Edit Content</a></li>
							<li class="breadcrumb-item active" aria-current="page">Detail Content</li>
						</ol>
					</nav>
				</div>

				<div class="card-body">
					<form action="'.site_url('manage_content_page/detail/'.$row['type']).'" enctype="multipart/form-data" method="post">
						<div class="form-group row mb-4">
							<div class="col-12">
								<label>Title</label>
								<input type="text" name="title" placeholder="Enter title" class="form-control" value="'.$row['title'].'">
							</div>
						</div>');

					if ($row['type'] == 'about-us')
					{
						section_content('
						<div class="form-group mb-4">
							<label>Short Description</label>
							<textarea name="short_desc" rows="6" placeholder="Short description here" class="form-control">'.$row['short_desc'].'</textarea>
						</div>');
					}

				section_content('
						<div class="form-group mb-4">
							<label>Description</label>
							<textarea name="description" rows="6" placeholder="Description here" class="form-control">'.$row['description'].'</textarea>
						</div>

						<div class="form-group row">');

					for ($i = 0; $i < $row['total_image']; $i++) 
					{ 
						section_content('
						<div class="col-md-3">
							<div class="uc-img-thumb rounded mr-4 my-3" style="width: 100%;height: 150px !important;background-image: url('.base_url($row['image_'.$i]).')"></div>					
							
							<div class="custom-file">
								<input type="file" name="images[]" class="custom-file-input" id="image_'.$i.'">
								<label class="custom-file-label" for="image_'.$i.'">Choose file</label>
							</div>
						</div>');						
					}

			section_content('
						</div>

						<div class="form-group">
							<input type="hidden" name="step" value="post">
							<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
							<input type="submit" class="btn btn-umara btn-block btn-lg" value="Save">
						</div>
					</form>
				</div>	
			</div>
			');
	}
}

?>