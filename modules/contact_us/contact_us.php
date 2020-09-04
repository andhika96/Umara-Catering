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

class contact_us {

	// Default variable for load extension
	protected $ext;

	protected $input;

	protected $csrf;

	protected $db;

	protected $email;
	
	public function __construct() 
	{
		$this->ext = load_ext(['url']);

		// Load Input
		$this->input = load_lib('input');

		// Active CSRF Protection
		get_data_global('SEC')->set_csrf(1);

		$this->csrf = [
			'name' => get_data_global('SEC')->get_csrf_token_name(),
			'hash' => get_data_global('SEC')->get_csrf_hash()
		];

		// Connected to the database
		$this->db = load_db('default', 'MySQL');

		// Load Email
		$this->email = load_lib('email');
	}

	public function index()
	{
		set_title('Contact Us');

		$res = $this->db->sql_prepare("select * from uc_contact_us where id = 1", "select");
		$row = $this->db->sql_fetch_array($res);

		$res_stmp = $this->db->sql_prepare("select * from uc_smtp where id = 1", "select");
		$row_stmp = $this->db->sql_fetch_array($res_stmp);

		if ($this->input->post('step') && $this->input->post('step') == 'post') 
		{
			$error = NULL;

			if ( ! $this->input->post('fullname') || ! $this->input->post('email') || ! $this->input->post('subject') || ! $this->input->post('message')) 
			{
				$error = 'Please fill all the required blank';
			}

			if ( ! strlen($error))
			{
				$config['protocol']		= $row_stmp['protocol'];
				$config['smtp_host'] 	= $row_stmp['smtp_host'];
				$config['smtp_port'] 	= $row_stmp['smtp_port']; // 8025, 587 and 25 can also be used. Use Port 465 for SSL.
				$config['smtp_user'] 	= $row_stmp['smtp_user'];
				$config['smtp_pass'] 	= $row_stmp['smtp_pass'];
				$config['mailtype']		= $row_stmp['mailtype'];
				$config['charset']		= 'utf-8';
				$config['crlf']			= '\r\n';
				$config['newline']		= '\r\n';
				$config['wordwrap']		= TRUE;

				$this->email->initialize($config);
				$this->email->from($this->input->post('email'), $this->input->post('fullname'));
				$this->email->to($row['master_email']);

				$this->email->subject($this->input->post('subject'));
				$this->email->message($this->input->post('message'));

				$this->email->send();

				// Redirect after succesfuly to login
				echo json_encode(['act' => 'submitted', 'message' => 'Your message has been sent']);
				exit;
			}
			else
			{
				echo json_encode(['act' => 'hold', 'message' => $error]);
				exit;

				// $this->error_display = '<div class="alert alert-danger mt-n3 mb-4" role="alert"><i class="fas fa-exclamation-triangle mr-1"></i> '.$error.'</div>';
			}
		}

		section_header('
		<style>
		body 
		{
			background: #efefef;
		}
		</style>

		<div class="content-header position-relative">
			<div class="page-header header-filter header-small lazy" data-bg="url('.base_url(get_cover_image('contact-us', 'cover-image', 'image')).')">
				<div class="container">
					<div class="row mt-4">
						<div class="col-md-9 ml-auto mr-auto text-center" style="text-shadow: 2px 2px 3px rgba(0,0,0,0.7) !important">
							<h2 class="uc-h3 uc-heading-cg">'.get_cover_image('contact-us', 'cover-image', 'title').'</h2>
							<h5 class="font-weight-light">'.get_cover_image('contact-us', 'cover-image', 'caption').'</h5>
						</div>
					</div>
				</div>
			</div>
		</div>');

		section_content('
		<div class="container my-5 py-4">
			<div class="row">
				<div class="col-md-5">
					<div class="bg-white p-4 rounded border shadow">
						<div class="h5 border-bottom pb-3 mb-4">Send us a message</div>

						<form action="'.site_url('contact_us').'" method="post" class="ug-form-submit">
							<div class="form-group mb-3">
								<label>Email Address</label>
								<input type="text" name="email" class="form-control" placeholder="Email Address">
							</div>

							<div class="form-group mb-3">
								<label>Subject</label>
								<input type="text" name="subject" class="form-control" placeholder="Subject">
							</div>

							<div class="form-group mb-3">
								<label>Fullname</label>
								<input type="text" name="fullname" class="form-control" placeholder="Fullname">
							</div>

							<div class="form-group mb-3">
								<label>Your Message</label>
								<textarea name="message" rows="4" class="form-control" placeholder="Message"></textarea>
							</div>

							<div>
								<input type="hidden" name="step" value="post">
								<input type="hidden" name="'.$this->csrf['name'].'" value="'.$this->csrf['hash'].'">
								<input type="submit" class="btn btn-brown btn-block" value="Submit">
							</div>
						</form>
					</div>
				</div>

				<div class="col-md-7">
					<div class="text-center mb-5">
						<img src="'.base_url('assets/images/undraw_contact_us_15o2.svg').'" class="img-fluid d-none" style="width: 500px">

						<div style="height: 300px">
							<iframe data-src="'.$row['embed_code'].'" frameborder="0" class="lazy" style="border: 0;width: 100%;height: 100%" allowfullscreen=""></iframe>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<h6 class="font-weight-bold mb-3">Address</h6>
							'.$row['detail_address'].'
						</div>

						<div class="col-md-6">
							<h6 class="font-weight-bold mb-3">Information</h6>
							
							<div class="mb-3">
								<i class="fas fa-phone fa-lg fa-fw text-muted mr-2"></i> '.$row['phone_number'].'
							</div>

							<div class="mb-3">
								<i class="fas fa-at fa-lg fa-fw text-muted mr-2"></i> '.$row['master_email'].'
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		');
	}

}

?>