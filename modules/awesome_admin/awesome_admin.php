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

class awesome_admin {

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
		$this->url = load_ext('url');

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
		set_title('Awesome Admin');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		section_content('
		<div class="bg-white shadow-sm rounded">
			<div class="p-3 mx-2 border-bottom">
				<h5 class="m-0"><i class="fas fa-magic mr-2"></i> Awesome Admin</h5>
			</div>

			<div class="row p-4">
				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('awesome_admin/config').'">
					<i class="fas fa-cogs fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Configuration</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('awesome_admin/user').'">
					<i class="fas fa-users fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> User</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('awesome_admin/userroles').'">
					<i class="fas fa-users-cog fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> Roles</h5>
				</a>

				<a class="col-md-4 col-xl-3 p-4 text-center text-decoration-none" href="'.site_url('awesome_admin/smtp').'">
					<i class="fas fa-paper-plane fa-4x"></i>
					<h5 class="mb-0 mt-2 font-weight-light"> SMTP Settings</h5>
				</a>
			</div>	
		</div>
		');
	}

	public function config()
	{
		set_title('Site Config');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_site_config where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => 1], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		$offline_mode = $row['offline_mode'] ? 'checked' : FALSE;
		$signup_closed_0 = $row['signup_closed'] ? 'selected' : FALSE;
		$signup_closed_1 = $row['signup_closed'] ? 'selected' : FALSE;

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			if ( ! $this->input->post('site_name') || ! $this->input->post('site_slogan') || ! $this->input->post('site_description'))
			{
				$this->error = 'Please fill in all the blank forms required';
			}

			if ( ! strlen($this->error))
			{
				$_POST['offline_mode'] = isset($_POST['offline_mode']) ? 1 : 0;

				$update_data = array(
					'site_name'			=>	$this->input->post('site_name'),
					'site_slogan'		=>	$this->input->post('site_slogan'),
					'site_description'	=>	$this->input->post('site_description'),
					'footer_message'	=>	$this->input->post('footer_message'),
					'signup_closed'		=>	$this->input->post('signup_closed'),
					'offline_mode'		=>	$_POST['offline_mode'],
					'offline_reason'	=>	$this->input->post('offline_reason')
				);

				if ($row['id'])
				{
					$this->db->sql_update($update_data, 'uc_site_config', ['id' => $row['id']]);
				}
				else 
				{
					$this->db->sql_insert($update_data, 'uc_site_config');
				}

				redirect('awesome_admin/config', 1);
			}
			else 
			{
				sys_notice($this->error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3 h5"><i class="fas fa-cogs mr-2"></i> Site Configuration</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('awesome_admin').'">Awesome Admin</a></li>
						<li class="breadcrumb-item active" aria-current="page">Site Configuration</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('awesome_admin/config').'" method="post">
					<!--- Site Configuration --->
					<div class="row">
						<div class="col-12 col-sm-8 mx-auto">
							<h5 class="pl-3 mb-4" style="border-left:5px red solid">Site Configuration</h5>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="sitename">Site Name</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="site_name" class="form-control" id="sitename" value="'.$row['site_name'].'">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="siteslogan">Site Slogan</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="site_slogan" class="form-control" id="siteslogan" value="'.$row['site_slogan'].'">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="sitedescription">Site Description</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="site_description" class="form-control" id="sitedescription" value="'.$row['site_description'].'">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="footermessage">Footer Message</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<textarea name="footer_message" rows="3" class="form-control" id="footermessage">'.$row['footer_message'].'</textarea>
						</div>
					</div>
					<!---  / End Site Configuration --->

					<!--- Privacy and Security Section --->
					<div class="row mt-5">
						<div class="col-12 col-sm-8 mx-auto">
							<h5 class="pl-3 mb-4" style="border-left:5px red solid">Privacy & Security</h5>
						</div>
					</div>

					<div class="form-group row pb-3">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="signupclosed">Setting Registration Form</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<select name="signup_closed" class="custom-select" id="signupclosed">
								<option value="0" '.$signup_closed_0.'>Open - Accepting new members</option>
								<option value="1" '.$signup_closed_1.'>Close - Not accepting new members</option>
							</select>
						</div>
					</div>
					<!--- / End Privacy and Security Section --->

					<!--- Site Status Settings Section --->
					<div class="row mt-5">
						<div class="col-12 col-sm-8 mx-auto">
							<h5 class="pl-3 mb-4" style="border-left:5px red solid">Site Status Settings</h5>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="offlinemode">Maintenance Mode</label>

						<div class="col-12 col-sm-8 col-lg-6 pt-1">
							<div class="ar-switch-button ar-switch-button-success">
								<input type="checkbox" name="offline_mode" id="offlinemode" '.$offline_mode.'>
								<span><label for="offlinemode"></label></span>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="offlinereason">Offline Reason</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<textarea name="offline_reason" rows="3" placeholder="Even though it\'s offline, you can still access the admin area" class="form-control" id="offlinereason">'.$row['offline_reason'].'</textarea>
						</div>
					</div>
					<!--- / End Site Status Settings Section --->

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

	public function userroles()
	{
		set_title('User Roles');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$error = NULL;

			if ( ! $this->input->post('role_name'))
			{
				$error = 'Please enter role name';
			}

			if ( ! strlen($error)) 
			{
				$res = $this->db->sql_prepare("select max(id) as maxid from uc_roles", "select");
				$row = $this->db->sql_fetch_array($res);

				$current_total = $this->db->num_rows('ar_roles');
				$total = $current_total-3;

				$id = $row['maxid']-$total;
				$role_code = strtolower($this->input->post('role_name'));
				$role_code = str_replace(" ", "_", $role_code);

				$new_data = ['id' => $id, 'name' => $this->input->post('role_name'), 'code_name' => $role_code];
				$this->db->sql_insert($new_data, 'uc_roles');

				// Redirect
				redirect('awesome_admin/userroles', 1);
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
					<div class="card-header card-header-divider">List of Roles</div>
					<div class="card-body pt-2">
						<ul class="list-group list-group-flush">
		');

		$res = $this->db->sql_prepare("select * from uc_roles order by id desc", "select");
		while ($row = $this->db->sql_fetch_array($res))
		{
			if ($row['id'] == 99)
			{
				$chg_bg = 'list-group-item-dark';
				$link = '<span class="float-right">You cannot edit or delete this role</span>';
			}
			else 
			{
				$chg_bg = 'list-group-item-action';
				$link = '<span class="float-right">'.anchor('awesome_admin/userroleedit/'.$row['id'], 'Edit').' - '.anchor('admin/userroledelete/'.$row['id'], 'Delete').'</span>';
			}

			section_content('<li class="list-group-item '.$chg_bg.'">'.$row['name'].' '.$link.'</li>');
		}

		section_content('
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-12 col-xl-6 mb-4">
				<div class="card arv2-admin-card">
					<div class="card-header card-header-divider">Add New Role</div>
					<div class="card-body">
						<form action="'.base_url('awesome_admin/userroles').'" method="post">
							<div class="form-group mb-4">
								<label>Role Name</label>
								<input type="text" name="role_name" placeholder="Role name" class="form-control">
							</div>

							<div class="form-group">
								<input type="hidden" name="step" value="post">
								<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
								<input type="submit" class="btn btn-primary" value="Add Role">
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
				<h5 class="mb-3"><i class="fas fa-users-cog mr-2"></i> User Role Settings</h5>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white">
						<li class="breadcrumb-item"><a href="'.site_url('awesome_admin').'">Admin Panel</a></li>
						<li class="breadcrumb-item active" aria-current="page">User Roles</li>
					</ol>
				</nav>
			</div>
		</div>');
	}

	public function userroleedit($rid = 0)
	{
		set_title('Edit User Role');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_roles where id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $rid], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['id'] || ! is_numeric($rid) || $row['id'] == 99)
		{
			error_page();
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$error = NULL;

			if ( ! $this->input->post('role_name'))
			{
				$error = 'Pleaser enter role name';
			}

			if ( ! strlen($error)) 
			{
				$role_code = strtolower($this->input->post('role_name'));
				$role_code = str_replace(" ", "_", $role_code);

				$update_data = array('name' => $this->input->post('role_name'), 'code_name' => $role_code);
				$this->db->sql_update($update_data, 'uc_roles', ['id' => $row['id']]);

				// Redirect
				redirect('awesome_admin/userroles', 1);
			}
			else 
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card">
			<div class="card-header card-header-divider">
				<div class="mb-3"><i class="fas fa-users-cog mr-2"></i> Edit Role</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('awesome_admin').'">Admin Panel</a></li>
						<li class="breadcrumb-item"><a href="'.site_url('awesome_admin/userroles').'">User Roles</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Role</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('awesome_admin/userroleedit/'.$row['id']).'" method="post">
					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Roles Name</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="role_name" class="form-control" id="inputText3" value="'.$row['name'].'">
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

	public function user()
	{
		set_title('User');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$error = NULL;

			if ( ! $this->input->post('roles'))
			{
				$error = 'Please select a role';
			}

			if ( ! strlen($error))
			{
				$res = $this->db->sql_prepare("select * from uc_roles where id = :id");
				$bindParam = $this->db->sql_bindParam(['id' => $_POST['roles']], $res);
				$row = $this->db->sql_fetch_array($bindParam);

				$update_data = [
					'status'	=> $this->input->post('status'),
					'roles'		=> $this->input->post('roles'),
				];

				$this->db->sql_update($update_data, 'uc_accounts', ['id' => $this->input->post('user_id')]);
				redirect('awesome_admin/user', 1);
			}
			else 
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="card arv2-admin-card mb-4">
			<div class="card-header">
				<div class="row mb-3">
					<div class="col-md-6 col-xl-6">
						<h5 class="mb-0 font-weight-light pt-2"><i class="fas fa-users mr-2"></i> List of Users</h5>
					</div>
				</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('awesome_admin').'">Admin Panel</a></li>
						<li class="breadcrumb-item active" aria-current="page">User</li>
					</ol>
				</nav>
			</div>

			<div class="card-body table-responsive p-0">
				<table class="table table-striped table-hover ar-table mb-0">
					<thead class="border-top">
						<tr>
							<th style="width:1%">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="fileSelectAll">
									<label class="custom-control-label" for="fileSelectAll"></label>
								</div>
							</th>

							<th style="width:19%">User</th>
							<th style="width:6%">Role</th>
							<th style="width:6%">Division</th>
							<th style="width:15%">Registered</th>
						</tr>
					</thead>

					<tbody>');

		$res = $this->db->sql_prepare("select * from uc_accounts order by id desc limit $this->offset, $this->num_per_page", "select");
		while ($row = $this->db->sql_fetch_array($res))
		{
			if (get_info_client($row['id'], 'gender') == 1)
			{
				$get_gender = '<i class="fas fa-venus-mars fa-lg fa-fw mr-2"></i> Male';
			}
			elseif (get_info_client($row['id'], 'gender') == 2)
			{
				$get_gender = '<i class="fas fa-venus-mars fa-lg fa-fw mr-2"></i> Female';
			}
			else 
			{
				$get_gender = '<i class="fas fa-genderless fa-lg fa-fw mr-2"></i> Unknown';
			}

			$active = ( ! empty($row['status'])) == 0 ? 'selected' : FALSE;
			$deactive = ( ! empty($row['status'])) == 1 ? 'selected' : FALSE;

			section_content('
			<tr>
				<td class="text-center">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="uids[]" class="custom-control-input checkids" id="user_'.$row['id'].'" value="'.$row['id'].'">
						<label class="custom-control-label" for="user_'.$row['id'].'"></label>
					</div>
				</td>

				<td>'.avatar($row['id'], 'small').' <span class="ml-2">'.$row['fullname'].'</span></td>
				<td>'.get_role($row['id']).'</td>
				<td>'.get_date($row['created']).'</td>
				<td>
					<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#UserDetail_'.$row['id'].'"><i class="far fa-eye mr-1"></i> View Detail</button>

					<!-- Modal -->
					<div class="modal fade text-wrap" id="UserDetail_'.$row['id'].'" tabindex="-1" role="dialog" aria-labelledby="UserDetail" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:700px !important">
							<div class="modal-content">
								<form action="'.site_url('awesome_admin/user').'" method="post">
								<div class="modal-body p-4">
									<div class="media">
										<span class="align-self-center">'.avatar($row['id'], 70).'</span>

										<div class="media-body ml-3">
											<h5 class="mt-0">'.$row['fullname'].'</h5>
											<span>'.$row['username'].'</span>
										</div>
									</div>

									<div class="pt-4 mt-4 border-top">
										<div class="row">
											<div class="col-md-6 mb-3">
												<i class="fas fa-baby fa-lg fa-fw mr-2"></i> '.get_info_client($row['id'], 'birthdate').'
											</div>

											<div class="col-md-6 mb-3">
												'.$get_gender.'
											</div>

											<div class="col-md-6 mb-3">
												<i class="fas fa-at fa-lg fa-fw mr-2"></i> '.get_client($row['id'], 'email').'
											</div>

											<div class="col-md-6 mb-3">
												<i class="fas fa-phone fa-lg fa-fw mr-2"></i> '.get_info_client($row['id'], 'phone_number').'
											</div>

											<div class="col-md-6">
												<i class="fas fa-user-friends fa-lg fa-fw mr-2"></i> Joined. '.get_date($row['created']).'
											</div>

											<div class="col-md-6">
												<i class="fas fa-globe-americas fa-lg fa-fw mr-2"></i> Last login, 1 minutes ago.
											</div>
										</div>
									</div>

									<div class="mt-5">
										<div class="row">
											<div class="col-md-12 mb-4">
												<div class="form-row">
													<label class="col-md-3 font-weight-bold">Account Role</label>

													<div class="col-md-9">
														<select name="roles" class="custom-select">
														');

								$res_role = $this->db->sql_prepare("select * from uc_roles", "select");
								while ($row_role = $this->db->sql_fetch_array($res_role))
								{
									$selected = $row['roles'] == $row_role['id'] ? 'selected' : NULL;

									section_content('<option value="'.$row_role['id'].'" '.$selected.'>'.$row_role['name'].'</option>');
								}

			section_content('
														</select>
													</div>
												</div>
											</div>

											<div class="col-md-12 mb-4">
												<div class="form-row">
													<label class="col-md-3 font-weight-bold">Account Status</label>

													<div class="col-md-9">
														<select name="status" class="custom-select">
															<option>None</option>
															<option value="0" '.$active.'>Activate</option>
															<option value="1" '.$deactive.'>Deactivate</option>
														</select>
													</div>
												</div>												
											</div>

										</div>
									</div>
								</div>
								
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

									<input type="hidden" name="step" value="post">
									<input type="hidden" name="user_id" value="'.$row['id'].'">
									<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
									<input type="submit" class="btn btn-primary" value="Save changes">
								</div>
								</form>
							</div>
						</div>
					</div>
				</td>
			</tr>
			');
		}

		if ( ! $this->db->sql_counts($res))
		{
			section_content('
						<tr>
							<td colspan="5" class="text-center">
								<div class="p-3">
									No user found
								</div>
							</td>
						</tr>
			');
		}

		// Total data from the content above for pagination and information
		$total = $this->db->num_rows('uc_accounts');

		// Pagination
		$this->pagination = load_lib('pagination', array($total));
		$this->pagination->paras = site_url('awesome_admin/user');

		section_content('
					</tbody>
				</table>

				<div class="border-top px-3">
					'.$this->pagination->whole_num_bar('justify-content-start').'
				</div>
			</div>
		</div>
		');
	}

	public function smtp()
	{
		set_title('SMTP Settings');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select * from uc_smtp where id = 1", "select");
		$row = $this->db->sql_fetch_array($res);

		section_content('
		<div class="card arv2-admin-card mb-4">
			<div class="card-header card-header-divider">
				<div class="mb-3 h5"><i class="fas fa-paper-plane mr-2"></i> SMTP Settings</div>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb m-0 p-0 bg-white" style="font-size:14px !important">
						<li class="breadcrumb-item"><a href="'.site_url('awesome_admin').'">Awesome Admin</a></li>
						<li class="breadcrumb-item active" aria-current="page">SMTP Settings</li>
					</ol>
				</nav>
			</div>

			<div class="card-body">
				<form action="'.site_url('awesome_admin/smtp').'" method="post">
					<div class="form-group row mb-3">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">SMTP Protocol</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" class="form-control" id="inputText3" value="'.$row['protocol'].'" readonly>
						</div>
					</div>

					<div class="form-group row mb-3">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Mail Type</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" class="form-control" id="inputText3" value="'.$row['mailtype'].'" readonly>
						</div>
					</div>

					<div class="form-group row mb-3">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">SMTP Host</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="smtp_host" class="form-control" id="inputText3" value="'.$row['smtp_host'].'">
						</div>
					</div>

					<div class="form-group row mb-3">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">SMTP Port</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="smtp_port" class="form-control" id="inputText3" value="'.$row['smtp_port'].'">
						</div>
					</div>

					<div class="form-group row mb-3">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">SMTP Crypto</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="smtp_crypto" class="form-control" id="inputText3" value="'.$row['smtp_crypto'].'">
						</div>
					</div>

					<div class="form-group row mb-3">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">SMTP User</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="smtp_user" class="form-control" id="inputText3" value="'.$row['smtp_user'].'">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">SMTP Pass</label>

						<div class="col-12 col-sm-8 col-lg-6">
							<input type="text" name="smtp_pass" class="form-control" id="inputText3" value="'.$row['smtp_pass'].'">
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

}

?>