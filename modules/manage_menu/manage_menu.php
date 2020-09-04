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

class manage_menu {

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
		set_title('Manage Menu');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<div class="bg-white shadow-sm rounded">
			<div class="p-3 mx-2 border-bottom">
				<h5 class="m-0"><i class="fas fa-utensils mr-2"></i> Manage Menu Content</h5>
			</div>

			<div class="row p-4">
				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_menu/category').'">
					<i class="fas fa-folder fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Menu Category</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_menu/addmenu').'">
					<i class="fas fa-book-medical fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Add Menu</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('manage_menu/listmenu').'">
					<i class="fas fa-book fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> List of Menu</h5>
				</a>
			</div>	
		</div>
		');
	}
	
	public function category()
	{
		set_title('Menu Category');

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
				$this->db->sql_insert($new_data, 'uc_menu_category');

				// Redirect
				redirect('manage_menu/category', 1);
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
					<div class="card-header card-header-divider">List of Menu Category</div>
					<div class="card-body pt-2">
						<ul class="list-group list-group-flush">
		');

		$res = $this->db->sql_prepare("select * from uc_menu_category order by id desc", "select");
		while ($row = $this->db->sql_fetch_array($res))
		{
			section_content('<li class="list-group-item list-group-item-action">'.$row['name'].' <span class="float-right">'.anchor('manage_menu/editcategory/'.$row['id'], 'Edit').' - '.anchor('manage_menu/deletecategory/'.$row['id'], 'Delete').'</span></li>');
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
						<form action="'.base_url('manage_menu/category').'" method="post">
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
				<h5 class="mb-3"><i class="fas fa-utensils mr-2"></i> Menu Category</h5>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white">
						<li class="breadcrumb-item"><a href="'.site_url('manage_menu').'">Manage Menu</a></li>
						<li class="breadcrumb-item active" aria-current="page">Menu Category</li>
					</ol>
				</nav>
			</div>
		</div>');
	}

	public function editcategory($cid = 0)
	{
		set_title('Edit Category');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_menu_category where id = :id");
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
				$this->db->sql_update($update_data, 'uc_menu_category', ['id' => $row['id']]);

				// Redirect
				redirect('manage_menu/category', 1);
			}
			else 
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-utensils mr-2"></i> Edit Category</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_menu').'">Manage Menu</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('manage_menu/category').'">Category</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Category</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_menu/editcategory/'.$row['id']).'" method="post">
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

	public function deletecategory($cid = 0)
	{
		set_title('Delete Category');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_menu_category where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $cid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['id'] || ! is_numeric($cid))
		{
			error_page();
		}

		$this->db->sql_delete("uc_menu_category", ['id' => $row['id']]);
		redirect('manage_menu/category', 1);
	}

	public function addmenu()
	{
		set_title('Add Menu');

		register_js([
			'<script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script>', 
			'<script src="'.base_url('assets/plugins/autogrow/autogrow.js').'"></script>',
			'<script>$("textarea").autogrow({onInitialize: true});</script>']);

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$error = NULL;

			if ( ! $this->input->post('title'))
			{
				$error = 'Please input title';
			}

			if ( ! $this->input->post('category'))
			{
				$error = 'Please select category';
			}

			if ( ! $this->input->post('content'))
			{
				$error = 'Content is empty';
			}

			if ( ! strlen($error))
			{
				$data = [
					'title'		=>	$this->input->post('title'),
					'menu_cat'	=>	$this->input->post('category'),
					'content'	=>	$this->input->post('content', FALSE),
					'updated'	=>	time(),
					'created'	=>	time()
				];

				$this->db->sql_insert($data, "uc_menu");

				redirect('manage_menu/listmenu', 1);
			}
			else
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-book-medical mr-2"></i> Add Menu Content</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_menu').'">Manage Menu</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Menu</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_menu/addmenu').'" enctype="multipart/form-data" method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Title</label>
								<input type="text" name="title" placeholder="Title here ..." class="form-control" value="'.$this->input->post('title').'">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Category</label>
								<select name="category" class="custom-select">
									<option>Select ...</option>');

					$res = $this->db->sql_prepare("select * from uc_menu_category order by id asc", "select");
					while ($row = $this->db->sql_fetch_array($res))
					{
						$selected = $this->input->post('category') ? 'selected' : NULL;

						section_content('
									<option value="'.$row['id'].'" '.$selected.'>'.$row['name'].'</option>');
					}

		section_content('
								</select>
							</div>
						</div>
					</div>
				
					<div class="form-group mb-3">
						<label>Content</label>
						<textarea name="content" placeholder="Type here ..." rows="6" class="form-control">'.$this->input->post('content').'</textarea>
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

	public function editmenu($bid = 0)
	{
		set_title('Edit Menu Content');
		
		register_js([
			'<script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script>', 
			'<script src="'.base_url('assets/plugins/autogrow/autogrow.js').'"></script>',
			'<script>$("textarea").autogrow({onInitialize: true});</script>']);

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_menu where id = :id");
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

			if ( ! $this->input->post('category'))
			{
				$error = 'Please select category';
			}

			if ( ! $this->input->post('content'))
			{
				$error = 'Content is empty';
			}

			if ( ! strlen($error))
			{
				$data = [
					'title'		=>	$this->input->post('title'),
					'menu_cat'	=>	$this->input->post('category'),
					'content'	=>	$this->input->post('content', FALSE),
					'updated'	=>	time()
				];

				$this->db->sql_update($data, "uc_menu", ['id' => $row['id']]);

				redirect('manage_menu/listmenu', 1);
			}
			else
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-book-medical mr-2"></i> Edit Menu</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_menu').'">Manage Menu</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('manage_menu/listmenu').'">List of Menu</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Menu</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('manage_menu/editmenu/'.$row['id']).'" enctype="multipart/form-data" method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Title</label>
								<input type="text" name="title" placeholder="Title here ..." class="form-control" value="'.$row['title'].'">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group mb-3">
								<label>Category</label>
								<select name="category" class="custom-select">
									<option>Select ...</option>');

					$res_cat = $this->db->sql_prepare("select * from uc_menu_category order by id asc", "select");
					while ($row_cat = $this->db->sql_fetch_array($res_cat))
					{
						$selected = ($row['menu_cat'] == $row_cat['id']) ? 'selected' : NULL;

						section_content('
									<option value="'.$row_cat['id'].'" '.$selected.'>'.$row_cat['name'].'</option>
						');
					}

		section_content('
								</select>
							</div>
						</div>
					</div>
				
					<div class="form-group mb-3">
						<label>Content</label>
						<textarea name="content" placeholder="Type here ..." rows="6" class="form-control">'.$row['content'].'</textarea>
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

	public function deletemenu($bid = 0)
	{
		set_title('Delete Menu');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_menu where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $bid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['id'] || ! is_numeric($bid))
		{
			error_page();
		}

		$this->db->sql_delete("uc_menu", ['id' => $row['id']]);
		redirect('manage_menu/listmenu', 1);
	}

	public function listmenu()
	{
		set_title('List of Menu');

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
				<div class="mb-3"><i class="fas fa-book mr-2"></i> List of Menu</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('manage_menu').'">Manage Menu</a></li>
						<li class="breadcrumb-item active" aria-current="page">List of Menu</li>
					</ol>
				</nav>
			</div>

			<div class="card-body py-1 px-3">
				<ul class="list-group list-group-flush">');

		$res = $this->db->sql_prepare("select * from uc_menu order by id desc limit $this->offset, $this->num_per_page", "select");
		while ($row = $this->db->sql_fetch_array($res))
		{
			section_content('
					<li class="list-group-item list-group-item-action px-2">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<h6>'.$row['title'].'</h6>
								<div class="text-muted small">'.get_date($row['created']).'</div>
							</div>

							<div>
								<div class="d-flex justify-content-between align-items-center pb-1">
									<div class="dropdown">
										<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>

										<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="'.site_url('manage_menu/editmenu/'.$row['id']).'"><i class="fas fa-pencil-alt fa-fw mr-1"></i> Edit</a>
											<a class="dropdown-item ar-show-alert-del" href="#!" data-url="'.site_url('manage_menu/deletemenu/'.$row['id']).'"><i class="far fa-trash-alt fa-fw mr-1"></i> Delete</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
			');
		}

		$res_total = $this->db->sql_prepare("select count(*) as num from uc_menu", "select");
		$total = $this->db->sql_fetch_array($res_total);

		$this->pagination = load_lib('pagination', array($total['num']));
		$this->pagination->paras = site_url('manage_menu/listmenu');

		section_content('
				</ul>

				<div class="pt-3">'.$this->pagination->whole_num_bar('justify-content-center').'</div>
			</div>
		</div>');
	}

}

?>