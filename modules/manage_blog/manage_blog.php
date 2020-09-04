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

class manage_blog {

	protected $session;

	protected $input;

	protected $db;

	protected $blogf;

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
		set_title('Manage Blog');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<div class="bg-white shadow-sm rounded">
			<div class="p-3 mx-2 border-bottom">
				<h5 class="m-0"><i class="fas fa-scroll mr-2"></i> Manage Blog Content</h5>
			</div>

			<div class="row p-4">
				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_blog/category').'">
					<i class="fas fa-folder fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Blog Category</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_blog/addblog').'">
					<i class="fas fa-book-medical fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Add Blog</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_blog/listblog').'">
					<i class="fas fa-book fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> List of Blog</h5>
				</a>
			</div>	
		</div>
		');
	}
	
	public function category()
	{
		set_title('Blog Category');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$error = NULL;

			if ( ! $this->input->post('category_name'))
			{
				$error = 'Please enter category name';
			}

			if ( ! strlen($error)) 
			{
				$category = strtolower($this->input->post('category_name'));
				$category = str_replace(" ", "_", $category);

				$new_data = ['uri' => $category, 'name' => $this->input->post('category_name')];
				$this->db->sql_insert($new_data, 'uc_blog_category');

				// Redirect
				redirect('manage_blog/category', 1);
			}
			else 
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="row">
			<div class="col-md-12 col-xl-6 mb-4">
				<div class="card arv2-admin-card" style="max-height: 550px;overflow-x: hidden;">
					<div class="card-header card-header-divider">List of Blog Category</div>
					<div class="card-body pt-2">
						<ul class="list-group list-group-flush">
		');

		$res = $this->db->sql_prepare("select * from uc_blog_category order by id desc", "select");
		while ($row = $this->db->sql_fetch_array($res))
		{
			section_content('<li class="list-group-item list-group-item-action">'.$row['name'].' <span class="float-right">'.anchor('manage_blog/editcategory/'.$row['id'], 'Edit').' - '.anchor('manage_blog/deletecategory/'.$row['id'], 'Delete').'</span></li>');
		}

		section_content('
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-12 col-xl-6 mb-4">
				<div class="card arv2-admin-card">
					<div class="card-header card-header-divider">Add New Category</div>
					<div class="card-body">
						<form action="'.base_url('manage_blog/category').'" method="post">
							<div class="form-group mb-4">
								<label>Category Name</label>
								<input type="text" name="category_name" placeholder="Category name" class="form-control">
							</div>

							<div class="form-group">
								<input type="hidden" name="step" value="post">
								<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
								<input type="submit" class="btn btn-primary" value="Add Category">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		');

		section_title('
		<div class="card arv2-admin-card mb-4">
			<div class="card-body">
				<h5 class="mb-3"><i class="fas fa-scroll mr-2"></i> Blog Category</h5>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white">
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog').'">Manage Blog</a></li>
						<li class="breadcrumb-item active" aria-current="page">Blog Category</li>
					</ol>
				</nav>
			</div>
		</div>');
	}

	public function editcategory($cid = 0)
	{
		set_title('Edit Category');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_blog_category where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $cid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['id'] || ! is_numeric($cid))
		{
			error_page();
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$error = NULL;

			if ( ! $this->input->post('category_name'))
			{
				$error = 'Pleaser enter category name';
			}

			if ( ! strlen($error)) 
			{
				$category = strtolower($this->input->post('category_name'));
				$category = str_replace(" ", "_", $category);

				$update_data = array('uri' => $category, 'name' => $this->input->post('category_name'));
				$this->db->sql_update($update_data, 'uc_blog_category', ['id' => $row['id']]);

				// Redirect
				redirect('manage_blog/category', 1);
			}
			else 
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-scroll mr-2"></i> Edit Category</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog').'">Manage Blog</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog/category').'">Category</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Category</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_blog/editcategory/'.$row['id']).'" method="post">
					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Category Name</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="category_name" class="form-control" id="inputText3" value="'.$row['name'].'">
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-sm-8 col-lg-6 offset-sm-3 offset-lg-3 text-right">
							<input type="hidden" name="step" value="post">
							<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
							<input type="submit" class="btn btn-success" value="Save">
						</div>
					</div>
				</form>
			</div>
		</div>
		');
	}

	public function editdesccat($cid = 0)
	{
		set_title('Edit Description Category');

		// Register file JS
		register_js(['<script src="'.base_url('assets/plugins/ckeditor/ckeditor.js').'"></script>', '<script>CKEDITOR.replace("editor");</script>']);

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_blog_category where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $cid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['id'] || ! is_numeric($cid))
		{
			error_page();
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$error = NULL;

			if ( ! $this->input->post('description'))
			{
				$error = 'Description is empty';
			}

			if ( ! strlen($error))
			{
				$data = [
					'description'	=>	$this->input->post('description', FALSE)
				];

				$this->db->sql_update($data, "uc_blog_category", ['id' => $row['id']]);
				redirect('manage_blog/editdesccat/'.$row['id'], 1);
			}
			else
			{
				sys_notice($error);
			}
		}

		// Custom CSS for CKEditor 4
		section_content('<style>.cke_chrome {border-radius: 6px;border: 1px solid #ced4da;box-shadow: none !important} .cke_top, .cke_inner {border-top-left-radius: 6px;border-top-right-radius: 6px} .cke_bottom {background: #ccc} .cke_path_item {color:#000}</style>');

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-scroll mr-2"></i> Edit Description Category</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog').'">Manage Blog</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog/category').'">Category</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Description '.$row['name'].'</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_blog/editdesccat/'.$row['id']).'" method="post">
					<div class="form-group mb-3">
						<label>Description</label>
						<textarea name="description" placeholder="Type here ..." rows="3" class="form-control" id="editor">'.$row['description'].'</textarea>
					</div>

					<div class="form-group text-right">
						<input type="hidden" name="step" value="post">
						<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
						<input type="submit" class="btn btn-primary" value="Publish">
					</div>
				</div>
			</form>
		</div>
		');
	}

	public function deletecategory($cid = 0)
	{
		set_title('Delete Category');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_blog_category where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $cid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['id'] || ! is_numeric($cid))
		{
			error_page();
		}

		$this->db->sql_delete("uc_blog_category", ['id' => $row['id']]);
		redirect('manage_blog/category', 1);
	}

	public function addblog()
	{
		set_title('Add Blog Content');

		// Register file JS
		register_js([
			'<script src="'.base_url('assets/plugins/ckeditor/ckeditor.js').'"></script>', 
			'<script>
			CKEDITOR.replace("editor", 
			{
				filebrowserBrowseUrl: "'.base_url("assets/plugins/filemanager/dialog.php?type=2&editor=ckeditor&fldr=").'", 
				filebrowserImageBrowseUrl: "'.base_url("assets/plugins/filemanager/dialog.php?type=1&editor=ckeditor&fldr=").'" 
			});
			</script>'
		]);

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$error = NULL;
			$image = NULL;
			$total = NULL;
			$get_imgid = NULL;
			$is_redirect = 1;
			$is_uploaded_photo = 1;

			if ( ! $this->input->post('title'))
			{
				$error = 'Please input title';
			}

			/*
			if ( ! $this->input->post('category'))
			{
				$error = 'Please select category';
			}
			*/

			if ( ! $this->input->post('content'))
			{
				$error = 'Content is empty';
			}

			if (isset($_FILES['userfile']))
			{
				$dir = date("Ym", time());
				$s_folder = './contents/userfiles/thumbs/'.$dir.'/';

				// For database only without dot and slash at the front folder
				$x_folder = 'contents/userfiles/thumbs/'.$dir.'/';

				if ( ! is_dir($s_folder)) 
				{
					mkdir($s_folder, 0777);
				}

				$configs['upload_path']		= $s_folder;
				$configs['allowed_types']	= 'jpg|jpeg|png';
				$configs['overwrite']		= TRUE;
				$configs['remove_spaces']	= TRUE;
				$configs['encrypt_name']	= TRUE;
				$configs['max_size']		= 1500;

				$upload = load_lib('upload', $configs);

				if ( ! $upload->do_upload('userfile'))
				{
					if ($_FILES['userfile']['error'] != 4)
					{
						$error = array('error' => $upload->display_errors('<span>', '</span>'));

						foreach ($error as $key => $value) 
						{
							$error = $value;
						}
					}

					$thumbnail = FALSE;
				}
				else 
				{
					$thumbnail = $x_folder.$upload->data('file_name');
				}
			}

			/*
			if (isset($_FILES['images']))
			{
				$total = count(array_filter($_FILES['images']['name']));

				if ( ! strlen($error))
				{
					$this->db->sql_insert(['created' => time()], 'uc_photos');
					$get_imgid = $this->db->insert_id();
				}

				for ($i = 0; $i < count(array_filter($_FILES['images']['name'])); $i++) 
				{
					if ($_FILES['images']['name'][$i] != NULL)
					{
						if ( ! strlen($error))
						{
							$dir = date("Ym", time());
							$s_folder = './contents/userfiles/photos/'.$dir.'/';

							// For database only without dot and slash at the front folder
							$x_folder = 'contents/userfiles/photos/'.$dir.'/';

							if ( ! is_dir($s_folder)) 
							{
								mkdir($s_folder, 0777);
							}

							$configs['upload_path']		= $s_folder;
							$configs['allowed_types']	= 'jpg|jpeg|png';
							$configs['encrypt_name']	= TRUE;
							$configs['overwrite']		= TRUE;
							$configs['remove_spaces']	= TRUE;
							$configs['max_size']		= 16000;

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

								$this->db->sql_delete('uc_photos', ['id' => $get_imgid]);
							}
							else 
							{
								$image = $x_folder.$upload->data('file_name');
							}
						}

						if ( ! strlen($error))
						{
							$is_redirect = 0;
							$this->db->sql_update(['image_'.$i => $image], 'uc_photos', ['id' => $get_imgid]);
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
			*/

			if ( ! strlen($error) /* || $is_uploaded_photo == 0 */)
			{
				$uri = str_replace(' ', '-', $this->input->post('title'));
				$uri = preg_replace("/[^a-zA-Z0-9-_]/", "", $uri);
				$uri = strtolower($uri).'-'.random_string('alnum', 4);

				$data = [
					'uri'		=>	$uri,
					'thumbnail'	=>	$thumbnail,
					// 'photos'	=>	$total,
					'user_id'	=>	get_user('id'),
					'title'		=>	$this->input->post('title'),
					// 'cat_id'	=>	$this->input->post('category'),
					'content'	=>	$this->input->post('content', FALSE),
					'created'	=>	time()
				];

				$this->db->sql_insert($data, "uc_blog");
				
				/*
				$get_cid = $this->db->insert_id();

				if (isset($_FILES['images']))
				{
					$this->db->sql_update(['blog_id' => $get_cid], 'uc_photos', ['id' => $get_imgid]);
				}
				*/

				redirect('manage_blog/listblog', 1);
			}
			else
			{
				sys_notice($error);
			}
		}

		// Custom CSS for CKEditor 4
		section_content('<style>.cke_chrome {border-radius: 6px;border: 1px solid #ced4da;box-shadow: none !important} .cke_top, .cke_inner {border-top-left-radius: 6px;border-top-right-radius: 6px} .cke_bottom {background: #ccc} .cke_path_item {color:#000}</style>');

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-book-medical mr-2"></i> Add Blog Content</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog').'">Manage Blog</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Blog Content</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_blog/addblog').'" enctype="multipart/form-data" method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Title</label>
								<input type="text" name="title" placeholder="Title here ..." class="form-control" value="'.$this->input->post('title').'">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Thumbnail</label>

								<div class="custom-file">
									<input type="file" name="userfile" class="custom-file-input" id="customFile">
									<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div>
						</div>
					</div>
				
					<div class="form-group mb-3">
						<label>Content</label>
						<textarea name="content" placeholder="Type here ..." rows="3" class="form-control" id="editor">'.$this->input->post('content').'</textarea>
					</div>

					<!---
					<div class="form-group mb-3">
						<label>Upload Photos</label>
						<div class="custom-file mb-2">
							<input type="file" name="images[]" class="custom-file-input" id="customFile">
							<label class="custom-file-label" for="customFile">Choose file</label>
						</div>

						<a href="javascript:void(0);" id="add_another_photo">Add More+</a>
					</div>
					--->

					<div class="form-group text-right">
						<input type="hidden" name="step" value="post">
						<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
						<input type="submit" class="btn btn-primary" value="Publish">
					</div>
				</div>
			</form>
		</div>
		');
	}

	public function editblog($bid = 0)
	{
		set_title('Edit Blog Content');

		// Register file JS
		register_js([
			'<script src="'.base_url('assets/plugins/ckeditor/ckeditor.js').'"></script>', 
			'<script>
			CKEDITOR.replace("editor", 
			{
				filebrowserBrowseUrl: "'.base_url("assets/plugins/filemanager/dialog.php?type=2&editor=ckeditor&fldr=").'", 
				filebrowserImageBrowseUrl: "'.base_url("assets/plugins/filemanager/dialog.php?type=1&editor=ckeditor&fldr=").'" 
			});
			</script>'
		]);
		
		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_blog where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $bid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		$res_photo = $this->db->sql_prepare("select * from uc_photos where blog_id = :blog_id");
		$bindParam_photo = $this->db->sql_bindParam(['blog_id' => $row['id']], $res_photo);
		$row_photo = $this->db->sql_fetch_array($bindParam_photo);

		if ( ! $row['id'] || ! is_numeric($bid))
		{
			error_page();
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$error = NULL;
			$image = NULL;
			$total = NULL;
			$get_imgid = NULL;
			$is_redirect = 1;
			$is_uploaded_photo = 1;

			if ( ! $this->input->post('title'))
			{
				$error = 'Please input title';
			}

			/*
			if ( ! $this->input->post('category'))
			{
				$error = 'Please select category';
			}
			*/

			if ( ! $this->input->post('content'))
			{
				$error = 'Content is empty';
			}

			if (isset($_FILES['userfile']))
			{
				$dir = date("Ym", time());
				$s_folder = './contents/userfiles/thumbs/'.$dir.'/';

				// For database only without dot and slash at the front folder
				$x_folder = 'contents/userfiles/thumbs/'.$dir.'/';

				if ( ! is_dir($s_folder)) 
				{
					mkdir($s_folder, 0777);
				}

				$configs['upload_path']		= $s_folder;
				$configs['allowed_types']	= 'jpg|jpeg|png';
				$configs['overwrite']		= TRUE;
				$configs['remove_spaces']	= TRUE;
				$configs['encrypt_name']	= TRUE;
				$configs['max_size']		= 1500;

				$upload = load_lib('upload', $configs);

				if ( ! $upload->do_upload('userfile'))
				{
					if ($_FILES['userfile']['error'] != 4)
					{
						$error = array('error' => $upload->display_errors('<span>', '</span>'));

						foreach ($error as $key => $value) 
						{
							$error = $value;
						}
					}

					$thumbnail = $row['thumbnail'];
				}
				else 
				{
					if (file_exists($row['thumbnail']))
					{
						unlink($row['thumbnail']);
					}

					$thumbnail = $x_folder.$upload->data('file_name');
				}
			}

			/*
			if (isset($_FILES['images']))
			{
				if ( ! $row_photo['blog_id'])
				{
					$total = count(array_filter($_FILES['images']['name']));
					$this->db->sql_insert(['created' => time()], 'uc_photos');
					$get_imgid = $this->db->insert_id();
				}
				else
				{
					for ($i = 0; $i < 10; $i++) 
					{
						if ($row_photo['image_'.$i])
						{				
							if ($_FILES['images']['name'][$i] != NULL)
							{
								$vals[] = $_FILES['images']['name'][$i];
							}
						}
					}

					$new_total = count(array_filter($_FILES['images']['name']))-count($vals);

					$total = $row['photos']+$new_total;
					$get_imgid = $row_photo['id'];
				}

				for ($i = 0; $i < 10; $i++) 
				{
					if ($_FILES['images']['name'][$i] != NULL)
					{
						if ( ! strlen($error))
						{
							$dir = date("Ym", time());
							$s_folder = './contents/userfiles/photos/'.$dir.'/';

							// For database only without dot and slash at the front folder
							$x_folder = 'contents/userfiles/photos/'.$dir.'/';

							if ( ! is_dir($s_folder)) 
							{
								mkdir($s_folder, 0777);
							}

							$configs['upload_path']		= $s_folder;
							$configs['allowed_types']	= 'jpg|jpeg|png';
							$configs['encrypt_name']	= TRUE;
							$configs['overwrite']		= TRUE;
							$configs['remove_spaces']	= TRUE;
							$configs['max_size']		= 16000;

							$upload = load_lib('upload', $configs);

							$_FILES['file']['name'] 	= $_FILES['images']['name'][$i];
							$_FILES['file']['type'] 	= $_FILES['images']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
							$_FILES['file']['error'] 	= $_FILES['images']['error'][$i];
							$_FILES['file']['size'] 	= $_FILES['images']['size'][$i];

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
								if ($row_photo['id'] && file_exists($row_photo['image_'.$i]))
								{
									unlink($row_photo['image_'.$i]);
								}

								$image = $x_folder.$upload->data('file_name');
							}
						}

						if ( ! strlen($error))
						{
							$is_redirect = 0;
							$this->db->sql_update(['image_'.$i => $image], 'uc_photos', ['id' => $get_imgid]);
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
			*/

			if ( ! strlen($error) /* || $is_uploaded_photo == 0 */)
			{
				$uri = str_replace(' ', '-', $this->input->post('title'));
				$uri = preg_replace("/[^a-zA-Z0-9-_]/", "", $uri);
				$uri = strtolower($uri).'-'.random_string('alnum', 4);

				$data = [
					'uri'		=>	$uri,
					'thumbnail'	=>	$thumbnail,
					// 'photos'	=> 	$total,
					'user_id'	=>	get_user('id'),
					'title'		=>	$this->input->post('title'),
					// 'cat_id'	=>	$this->input->post('category'),
					'content'	=>	$this->input->post('content', FALSE),
					'created'	=>	time()
				];

				$this->db->sql_update($data, "uc_blog", ['id' => $row['id']]);

				/*
				if (isset($_FILES['images']))
				{
					$this->db->sql_update(['blog_id' => $row['id']], 'uc_photos', ['id' => $get_imgid]);
				}
				*/

				redirect('manage_blog/listblog', 1);
			}
			else
			{
				sys_notice($error);
			}
		}

		// Custom CSS for CKEditor 4
		section_content('<style>.cke_chrome {border-radius: 6px;border: 1px solid #ced4da;box-shadow: none !important} .cke_top, .cke_inner {border-top-left-radius: 6px;border-top-right-radius: 6px} .cke_bottom {background: #ccc} .cke_path_item {color:#000}</style>');

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-book-medical mr-2"></i> Edit Blog</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog').'">Manage Blog</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog/listblog').'">List of Blog</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Blog Content</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_blog/editblog/'.$row['id']).'" enctype="multipart/form-data" method="post">
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
									<input type="file" name="userfile" class="custom-file-input" id="customFile">
									<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div>
						</div>
					</div>
				
					<div class="form-group mb-3">
						<label>Content</label>
						<textarea name="content" placeholder="Type here ..." rows="3" class="form-control" id="editor">'.$row['content'].'</textarea>
					</div>

					<div class="form-group text-right">
						<input type="hidden" name="step" value="post">
						<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
						<input type="submit" class="btn btn-primary" value="Publish">
					</div>
				</div>
			</form>
		</div>
		');
	}

	public function deleteblog($bid = 0)
	{
		set_title('Delete Blog');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_blog where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $bid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		/*
		$res_photo = $this->db->sql_prepare("select * from uc_photos where blog_id = :blog_id");
		$bindParam_photo = $this->db->sql_bindParam(['blog_id' => $bid], $res_photo);
		$row_photo = $this->db->sql_fetch_array($bindParam_photo);
		*/

		$row['id'] ??= NULL;
		// $row_photo['id'] ??= NULL;

		if ( ! $row['id'] || ! is_numeric($bid))
		{
			error_page();
		}

		if (file_exists($row['thumbnail']))
		{
			unlink($row['thumbnail']);
		}

		/*
		if ($row_photo['id'])
		{
			for ($i = 0; $i < 10; $i++) 
			{
				if (file_exists($row_photo['image_'.$i]))
				{
					unlink($row_photo['image_'.$i]);
				}
			}
		}
		*/

		// $this->db->sql_delete("uc_photos", ['blog_id' => $row['id']]);
		$this->db->sql_delete("uc_blog", ['id' => $row['id']]);
		redirect('manage_blog/listblog', 1);
	}

	public function listblog()
	{
		set_title('List of Blog');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<style>
		.ar-img-thumb {
			width:  auto;
			height: 130px;
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
		}

		.list-group-item:first-child {
			border-top: 0 !important;
		}
		</style>

		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-book mr-2"></i> List of Blog</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_blog').'">Manage Blog</a></li>
						<li class="breadcrumb-item active" aria-current="page">List of Blog</li>
					</ol>
				</nav>
			</div>

			<div class="card-body py-1 px-3">
				<ul class="list-group list-group-flush">');

		$res = $this->db->sql_prepare("select * from uc_blog order by id desc limit $this->offset, $this->num_per_page", "select");
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
								<div class="text-muted small">'.get_date($row['created']).'</div>
							</div>

							<div>
								<div class="d-flex justify-content-between align-items-center pb-1">									
									<div class="mr-4 rounded" style="width: 150px;height: 75px;background: #3f51b5;">
										'.$thumbnail.'
									</div>

									<div class="dropdown">
										<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>

										<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="'.site_url('manage_blog/editblog/'.$row['id']).'"><i class="fas fa-pencil-alt fa-fw mr-1"></i> Edit</a>
											<a class="dropdown-item ar-show-alert-del" href="#!" data-url="'.site_url('manage_blog/deleteblog/'.$row['id']).'"><i class="far fa-trash-alt fa-fw mr-1"></i> Delete</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
			');
		}

		$res_total = $this->db->sql_prepare("select count(*) as num from uc_blog", "select");
		$total = $this->db->sql_fetch_array($res_total);

		$this->pagination = load_lib('pagination', array($total['num']));
		$this->pagination->paras = site_url('manage_blog/listblog');

		section_content('
				</ul>

				<div class="pt-3">'.$this->pagination->whole_num_bar('justify-content-center').'</div>
			</div>
		</div>');
	}

}

?>