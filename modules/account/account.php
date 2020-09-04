<?php

	/*
	 *  Aruna Development Project
	 *  IS NOT FREE SOFTWARE
	 *  Codename: Ardev Cassandra
	 *  Source: Based on Sosiaku Social Networking Software
	 *  Website: https://www.sosiaku.gq
	 *	Website: https://www.aruna-dev.id
	 *  Created and developed by Andhika Adhitia N
	 */

defined('MODULEPATH') OR exit('No direct script access allowed');

class account {

	// Create default variable Database
	protected $db;

	// Create default variable Session
	protected $session;

	// Create default variable Extension
	protected $ext;

	// Create default variable Input
	protected $input;

	// variable to activate CSRF Protection
	protected $csrf;

	protected $offset;

	protected $num_per_page;

	protected $error_display = NULL;

	public function __construct()
	{
		// Load Session
		$this->session = load_lib('session');

		// Load Input Library
		$this->input = load_lib('Input');

		// Connected to the database
		$this->db = load_db('default', 'MySQL');

		// Load URL Extension
		$this->ext = load_ext(['url']);

		// Active CSRF Protection
		get_data_global('SEC')->set_csrf(1);

		$this->csrf = [
			'name' => get_data_global('SEC')->get_csrf_token_name(),
			'hash' => get_data_global('SEC')->get_csrf_hash()
		];

		// Check login
		// This from Common Function
		check_login();

		// Set offset dan num per page
		$this->offset = offset();
		$this->num_per_page = num_per_page();

		// Register file JS
		register_js([
			'<script src="'.base_url('assets/js/jquery-profile-picture.js?v=0.0.2').'"></script>', 
			'<script src="'.base_url('assets/plugins/croppie/2.6.2/js/croppie.min.js').'"></script>',
			'<script src="'.base_url('assets/plugins/jquery-mask/dist/jquery.mask.min.js').'"></script>',
			'<script>$(document).ready(function() { $(".placeholder_date").mask("00/00/0000", {placeholder: "__/__/____"}); $(".placeholder_phone_number").mask("0000-0000-0000", {"translation": {0: {pattern: /[0-9*]/}}}); });</script>'
		]);
	}

	public function index()
	{
		set_title('Account');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select a.*, a.id as uid, u.* from uc_accounts as a left join uc_user_information as u on u.user_id = a.id where a.id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $this->session->userdata('id')], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['uid'])
		{
			error_page('User ID not found');
		}

		$selected_0 = $row['gender'] == 0 ? 'selected' : FALSE;
		$selected_1 = $row['gender'] == 1 ? 'selected' : FALSE;
		$selected_2 = $row['gender'] == 2 ? 'selected' : FALSE;

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			if ( ! $this->input->post('fullname'))
			{
				$error = 'Please enter your full name';
			}

			if ( ! $this->input->post('email'))
			{
				$error = 'Please enter your email address';
			}

			if ( ! $this->input->post('birthdate'))
			{
				$error = 'Please enter your birthdate';
			}

			if ( ! $this->input->post('gender'))
			{
				$error = 'Please enter your gender';
			}

			if ( ! strlen($error))
			{
				$update_account = [
					'fullname' 	=> $this->input->post('fullname'),
					'email'		=> $this->input->post('email')
				];

				$update_user_information = [
					'birthdate'		=> $this->input->post('birthdate'),
					'gender'		=> $this->input->post('gender'),
					'phone_number'	=> $this->input->post('phone_number'),
					'about'			=> $this->input->post('about')
				];

				$this->db->sql_update($update_account, 'uc_accounts', ['id' => $row['uid']]);
				$this->db->sql_update($update_user_information, 'uc_user_information', ['user_id' => $row['uid']]);

				// Refresh page after successfuly update user information
				redirect('account', 1);
			}
			else 
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="row">
			<div class="col-md-12 col-xl-4 mb-4">
				'.menu_settings().'
			</div>

			<div class="col-md-12 col-xl-8">
				<div class="card ar-card">
					<div class="card-body">

						<!--- Change Profile Picture --->
						<div class="card bg-gradient-warning border-0 shadow-none mb-4" style="background: linear-gradient(80deg,#ff9500 0,#ff6200 100%) !important;">
							<div class="card-body py-3">
								<div class="row row-grid align-items-center">
									<div class="col-lg-12">
										<div class="media align-items-center">
											<span class="mr-3 text-white">
												'.avatar($row['id'], 70).'
											</span>
											
											<div class="media-body">
												<h5 class="text-white mb-0">'.$row['fullname'].'</h5>
												<div>
													<span class="text-white">'.get_role($row['id']).'</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--- / End Change Profile Picture --->

						<!--- General --->
						<form action="'.site_url('account/index').'" method="post">
							<div class="page-inner-header mb-4">
								<h5 class="mb-1">General information</h5>
								<p class="text-muted mb-0">You can help us, by filling your data, create you a much better experience using our website.</p>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>Username</label>
										<input type="text" placeholder="Enter your username" class="form-control" value="'.$row['username'].'" disabled>
									</div>
								</div>

								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>Fullname</label>
										<input type="text" name="fullname" placeholder="Enter your fullname" class="form-control" value="'.$row['fullname'].'">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>Birthdate</label>
										<input type="text" name="birthdate" placeholder="Enter your birthdate" class="form-control placeholder_date" value="'.$row['birthdate'].'">
									</div>
								</div>

								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label for="inputGender">Gender</label>
										<select name="gender" class="custom-select" id="inputGender">
											<option value="0" '.$selected_0.'>Pilih</option>
											<option value="1" '.$selected_1.'>Laki-laki</option>
											<option value="2" '.$selected_2.'>Perempuan</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>Email Address</label>
										<input type="text" name="email" placeholder="Enter your email address" class="form-control" value="'.$row['email'].'">
									</div>
								</div>

								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>Phone Number</label>
										<input type="text" name="phone_number" placeholder="Enter your phone number" class="form-control placeholder_phone_number" value="'.$row['phone_number'].'">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-12">
									<div class="form-group mb-4">
										<label>About</label>
										<textarea name="about" rows="4" placeholder="Explain about you" class="form-control">'.$row['about'].'</textarea>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-12 text-right">
									<input type="hidden" name="step" value="post">
									<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
									<input type="submit" class="btn btn-success" value="Save changes">
								</div>
							</div>
						</form>
						<!--- / End General --->

					</div>
				</div>
			</div>
		</div>
		');
	}

	public function settings()
	{
		set_title('Change Password');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select a.*, a.id as uid, u.* from uc_accounts as a left join uc_user_information as u on u.user_id = a.id where a.id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $this->session->userdata('id')], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['uid'])
		{
			error_page('User ID not found');
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$error = NULL;

			if ( ! $this->input->post('old_password'))
			{
				$error = 'Please enter your current password';
			}

			if ( ! $this->input->post('new_password'))
			{
				$error = 'Please enter your new password';
			}

			if ( ! $this->input->post('confirm_password'))
			{
				$error = 'Please enter your confirm password';
			}

			if ( ! password_verify($this->input->post('old_password'), $row['password']))
			{
				$error = 'The old password you entered is incorrect';
			}

			if ($this->input->post('new_password') != $this->input->post('confirm_password'))
			{
				$error = 'The new password does not match the confirmation password';
			}

			if ( ! strlen($error))
			{
				// Hashing password for security reason
				$password = password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);

				$update_password = ['password'	=> $password];

				$this->db->sql_update($update_password, 'uc_accounts', array('id' => $row['uid']));

				redirect('account/settings', 1);
			}
			else 
			{
				sys_notice($error);
			}
		}

		section_content('
		<div class="row">
			<div class="col-md-12 col-xl-4 mb-4">
				'.menu_settings().'
			</div>

			<div class="col-md-12 col-xl-8">
				<div class="card ar-card">
					<div class="card-body">

						<!--- General --->
						<form action="'.site_url('account/settings').'" method="post">
							<div class="page-inner-header mb-4">
								<h5 class="mb-1">Change password</h5>
								<p class="text-muted mb-0">You can help us, by filling your data, create you a much better experience using our website.</p>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>Old Password</label>
										<input type="password" name="old_password" class="form-control">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>New Password</label>
										<input type="password" name="new_password" class="form-control">
									</div>
								</div>

								<div class="col-md-12 col-xl-6">
									<div class="form-group mb-4">
										<label>Confirm Password</label>
										<input type="password" name="confirm_password" class="form-control">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xl-12 text-right">
									<input type="hidden" name="step" value="post">
									<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
									<input type="submit" class="btn btn-success" value="Update password">
								</div>
							</div>
						</form>
						<!--- / End General --->

					</div>
				</div>
			</div>
		</div>
		');
	}

	public function avatar()
	{
		set_title('Change Avatar');

		load_extend_view('default', ['header_dash_page', 'footer_dash_page']);

		$res = $this->db->sql_prepare("select a.*, a.id as uid, u.* from uc_accounts as a left join uc_user_information as u on u.user_id = a.id where a.id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $this->session->userdata('id')], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['uid'])
		{
			error_page('User ID not found');
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post')
		{
			$errors = array();
			$data = $_POST['image'];

			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);

 			$temp_file_path = tempnam(sys_get_temp_dir(), 'contents'); // might not work on some systems, specify your temp path if system temp dir is not writeable
 			file_put_contents($temp_file_path, base64_decode($data));
 			$image_info = getimagesize($temp_file_path);

 			if ( ! empty($this->input->post('file')))
			{
				$_FILES['userfile'] = [
					'name'		=> uniqid().'.'.preg_replace('!\w+/!', '', $image_info['mime']),
					'tmp_name'	=> $temp_file_path,
					'size'		=> filesize($temp_file_path),
					'type'		=> $image_info['mime']
				];
			
				$dir = date("Ym", time());
				$s_folder = './contents/userfiles/avatars/'.$dir.'/';

				// For database only without dot and slash at the front folder
				$x_folder = 'contents/userfiles/avatars/'.$dir.'/';

				if ( ! is_dir($s_folder)) 
				{
					mkdir($s_folder, 0777);
				}
				
				$config['upload_path']		= $s_folder;
				$config['allowed_types']	= 'gif|png|jpg';
				$config['max_size']			= 1000;
				$config['max_width']		= 4096;
				$config['max_height']		= 2160;
				$config['encrypt_name']		= TRUE;

				$upload = load_lib('upload', $config);

				if ( ! $upload->do_upload('userfile', TRUE))
				{
					$error = array('error' => $upload->display_errors('<span>', '</span>'));

					foreach ($error as $key => $value) 
					{
						$errors = $value;
					}

					echo json_encode(array('status' => 'failed', 'message' => $errors));
					exit;
				}
				else
				{
					if ($row['avatar'])
					{
						unlink($row['avatar']);
					}

					$data = array('avatar' => $x_folder.$upload->data('file_name'));
					$this->db->sql_update($data, 'uc_user_information', ['user_id' => $row['uid']]);

					echo json_encode(array('status' => 'success', 'message' => 'Ok'));
					exit;
				}
			}
			else
			{
				echo json_encode(array('status' => 'failed', 'message' => 'You did not select a file to upload.'));
				exit;
			}
		}

		section_content('
		<div class="row">
			<div class="col-md-12 col-xl-4 mb-4">
				'.menu_settings().'
			</div>

			<div class="col-md-12 col-xl-8">
				<div class="card ar-card">
					<div class="card-body">
						<div class="page-inner-header mb-4">
							<h5 class="mb-1">Change avatar</h5>
							<p class="text-muted mb-0">You can help us, by filling your data, create you a much better experience using our website.</p>
						</div>

						<span class="warning-effect"></span>

						<div class="rounded">
							<div id="upload-demo" class="mx-auto" style="width:350px">
								<div id="overlay_profile_picture" style="display:none;width:220px;height:220px;"><div class="text-center" style="margin-top:40%"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="color:#FFF"></i></div></div>
							</div>
						</div>

						<div class="p-3">
							<div class="row mb-2">
								<div class="col-md-6 offset-md-3">
									<div class="custom-file">
										<input type="file" name="userfile" class="custom-file-input" id="upload" accept="image/*" required>
										<label class="custom-file-label" for="upload">Choose file</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 offset-md-3">
									<input type="hidden" class="btn-token" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
									<button class="btn btn-success upload-result float-right" onchange="clearImage(this)">Upload</button>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		');
	}

	public function preview_cover_image() 
	{
		$res = $this->db->sql_prepare("select a.*, a.id as uid, u.* from ar_accounts as a left join ar_user_information as u on u.user_id = a.id where a.id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $this->session->userdata('id')], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['uid'])
		{
			error_page('User ID not found');
		}

		if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST") 
		{
			$dir = date("Ym", time());
			$s_folder = './contents/covers/'.$dir.'/';

			// For database only without dot and slash at the front folder
			$x_folder = 'contents/covers/'.$dir.'/';

			if ( ! is_dir($s_folder)) 
			{
				mkdir($s_folder, 0777);
			}

			$configs['upload_path']		= $s_folder;
			$configs['allowed_types']	= 'gif|jpg|jpeg|png';
			$configs['max_size']		= 10000;
			$configs['max_width']		= 4096;
			$configs['max_height']		= 2160;
			$configs['encrypt_name']	= TRUE;

			$upload = load_lib('upload', $configs);

			if ( ! $upload->do_upload('userfile'))
			{
				$error = array('error' => $upload->display_errors());

				foreach ($error as $key => $value) 
				{
					$notice = $value;
				}

				echo '
				<script>
				jQuery(function($) 
				{ 
					alert("'.$notice.'");

					$(".btn-upload").addClass("d-block");
					$(".btn-option").addClass("d-none");
					$(".btn-option").removeClass("d-block");
				});
				</script>
				
				<div class="ar-cover-image ar-preview-cover-image" style="background-image: url('.base_url($row['cover_image']).');background-position:'.$row['cover_image_position'].' !important"></div>';
				
				exit;
			}
			else 
			{
				echo '
				<script>
				jQuery(function($) 
				{ 
					$(".ar-preview-cover-image").backgroundDraggable({ bound: true, axis: "y" });
					$(".btn-upload").addClass("d-none");
					$(".btn-upload").removeClass("d-block");
				});
				</script>
				<span class="cfmlvpage" style="display:none"></span>
				<div class="ar-cover-image ar-preview-cover-image" style="background-image: url('.base_url($x_folder.$upload->data('file_name')).');"></div>';

				exit;
			}
		}
		else 
		{
			error_page();
		}
	}

	public function update_cover_image() 
	{
		$res = $this->db->sql_prepare("select a.*, a.id as uid, u.* from ar_accounts as a left join ar_user_information as u on u.user_id = a.id where a.id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $this->session->userdata('id')], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['uid'])
		{
			error_page('User ID not found');
		}

		if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST") 
		{
			if ($row['cover_image'])
			{
				unlink($row['cover_image']);
			}

			$_POST['image'] = str_replace('url("', '', $_POST['image']);
			$_POST['image'] = str_replace('")', '', $_POST['image']);
			$_POST['image'] = str_replace('http://'.$_SERVER['HTTP_HOST'].'/', '', $_POST['image']);

			$data = array('cover_image' => $_POST['image'], 'cover_image_position' => $_POST['position']);
			$this->db->sql_update($data, 'ar_user_information', array('user_id' => $row['uid']));

			echo json_encode(array('status' => 'success', 'message' => 'Cover updated'));		
			exit;
		}
		else 
		{
			error_page();
		}
	}

	public function cancel_cover_image() 
	{
		$res = $this->db->sql_prepare("select a.*, a.id as uid, u.* from ar_accounts as a left join ar_user_information as u on u.user_id = a.id where a.id = :id");
		$bindParam = $this->db->sql_bindParam(['id' => $this->session->userdata('id')], $res);
		$row = $this->db->sql_fetch_array($bindParam);

		if ( ! $row['uid'])
		{
			error_page('User ID not found');
		}

		if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST") 
		{
			$_POST['image'] = str_replace('url("', '', $_POST['image']);
			$_POST['image'] = str_replace('")', '', $_POST['image']);
			$_POST['image'] = str_replace('http://'.$_SERVER['HTTP_HOST'].'/', '', $_POST['image']);

			unlink($_POST['image']);
			echo json_encode(array('status' => 'cancel', 'message' => 'Cover canceled'));
			exit;
		}
		else 
		{
			error_page();
		}
	}

}

?>