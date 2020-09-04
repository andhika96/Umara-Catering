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

class auth {

	protected $session;

	protected $input;

	protected $db;

	protected $csrf;

	protected $error_display = NULL;

	// Default variable for load extension
	protected $ext;
	
	public function __construct() 
	{
		// Load Session
		$this->session = load_lib('session');

		// Load Input Library
		$this->input = load_lib('input');

		// Connected to the database
		$this->db = load_db('default', 'MySQL');

		// Active CSRF Protection
		get_data_global('SEC')->set_csrf(1);

		$this->csrf = [
			'name' => get_data_global('SEC')->get_csrf_token_name(),
			'hash' => get_data_global('SEC')->get_csrf_hash()
		];

		// Load extension
		$this->ext = load_ext(['url', 'string']);

		section_content('
		<style>
		body
		{
			font-size: 14px;
		}
		.ar-card,
		.ar-card .card-header:first-child 
		{
			max-width: 450px !important;
			border-radius: .5rem;
		}

		.ar-card .card-body 
		{
			padding: 1.5rem 2rem;
		}
		</style>');
	}

	public function index()
	{
		redirect('auth/login');
	}

	public function login()
	{
		load_extend_view('default', ['header_auth_page', 'footer_auth_page']);

		set_title('Login');

		if ($this->session->userdata('id'))
		{
			redirect('dashboard');
		}

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$res = $this->db->sql_prepare("select * from uc_accounts where (email = :username1 or username = :username2) limit 1");
			$bindParam = $this->db->sql_bindParam(['username1' => $this->input->post('email'), 'username2' => $this->input->post('email')], $res);
			$row = $this->db->sql_fetch_array($bindParam);

			if (password_verify($this->input->post('password'), $row['password'])) 
			{
				$set_data = ['username' => $row['username'], 'id' => $row['id']];

				$this->session->set_userdata($set_data);
				$this->error_display = NULL;

				redirect('dashboard');
			}
			else
			{
				$this->error_display = '<div class="card card-full-color card-full-danger mt-n1 mb-4" role="alert"><div class="card-body p-3"><i class="fas fa-exclamation-triangle mr-1"></i> The email address or password you entered is incorrect, please try again</div></div>';
			}
		}

		section_content('
		<div class="container">
			<div class="row d-flex align-items-center" style="min-height: 100vh">
				<div class="col-md-6 mx-auto">
					<div class="card ar-card shadow mx-auto">
						<div class="card-header card-border-color uc-border-color-brown bg-white border-bottom-0 text-center px-5 pt-4 pb-3">
							<a href="'.site_url().'"><img src="'.base_url('assets/images/logo_brown.png').'" class="mb-3" style="width: 150px"></a>
							<p class="text-muted text-small mb-0">Please enter your user information.</p>
						</div>

						<div class="card-body">
							'.$this->error_display.'

							<form action="'.site_url('auth/login').'" method="post">
								<div class="form-group mb-4">
									<label>Email Address</label>
									<input type="text" name="email" placeholder="Email Address" class="form-control" required>
								</div>

								<div class="form-group">
									<label>Password</label>
									<input type="password" name="password" placeholder="Password" class="form-control">
								</div>

								<div class="form-group row mt-4">
									<div class="col-6">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" name="remember_me" class="custom-control-input" id="CheckRememberMe">
											<label class="custom-control-label" for="CheckRememberMe">Remember Me</label>
										</div>
									</div>

									<div class="col-6 text-right">
										<a href="'.site_url('auth/forgotpwd').'">Forgot Password</a>
									</div>
								</div>

								<div class="form-group row mt-4">
									<div class="col-12">
										<input type="hidden" name="step" value="post">
										<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
										<input type="submit" class="btn btn-brown btn-lg btn-block" value="Login">
									</div>
								</div>					
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		');
	}

	public function forgotpwd()
	{
		load_extend_view('default', ['header_auth_page', 'footer_auth_page']);

		section_content('
		<div class="container">
			<div class="row d-flex align-items-center" style="min-height: 100vh">
				<div class="col-md-6 mx-auto">
					<div class="card ar-card shadow mx-auto">
						<div class="card-header card-border-color uc-border-color-brown bg-white border-bottom-0 text-center px-4 pt-4 pb-3">
							<a href="'.site_url().'"><img src="'.base_url('assets/images/logo_brown.png').'" class="mb-3" style="width: 150px"></a>
							<p class="text-muted text-small mb-0">Please enter the email address registered with your account.</p>
						</div>

						<div class="card-body">

							<form action="'.site_url('auth/forgotpwd').'" method="post">
								<div class="form-group mb-3">
									<label>Email Address</label>
									<input type="text" name="email" placeholder="Email Address" class="form-control">
								</div>

								<div>
									<i>* Don\'t worry, we\'ll send you an email to reset your password.</i>
								</div>

								<div class="form-group row mt-5">
									<div class="col-12">
										<input type="hidden" name="step" value="post">
										<input type="submit" class="btn btn-brown btn-lg btn-block" value="Reset Password">
									</div>
								</div>	

								<div class="form-group row mb-0 mt-4">
									<div class="col-6">
										<a href="'.site_url('auth/login').'"><i class="fas fa-long-arrow-alt-left mr-2"></i> Back to Login Page</a>
									</div>
								</div>				
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		');
	}

	public function logout()
	{
		load_extend_view('default', ['header_auth_page', 'footer_auth_page']);

		if ($this->session->userdata('username') && $this->session->userdata('id'))
		{
			$this->session->unset_userdata(['username', 'id']);
			redirect('auth/login');
		}

		if ( ! $this->session->userdata('username') && ! $this->session->userdata('id'))
		{
			redirect('auth/login');
		}
	}
}

?>