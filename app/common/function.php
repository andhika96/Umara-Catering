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

	defined('APPPATH') OR exit('No direct script access allowed');

	// ------------------------------------------------------------------------

	/**
	 * User Authentication
	 * 
	 * Berfungsi untuk otentikasi pengguna untuk mengakses halaman
	 *
	 * @return boolean
	 */

	function user_auth($uid = 0, $ids, $force_uid = 0, $username = '')
	{
		$roles = array();

		// Connected to the database
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select * from uc_accounts where username = :username and id = :id");
		$bindParam = $db->sql_bindParam(['username' => $username, 'id' => $uid], $res);
		while ($row = $db->sql_fetch_array($bindParam))
		{
			$roles[] = $row['roles'];
		}

		if ($force_uid)
		{
			$id	= isset($_SESSION['id']) ? $_SESSION['id'] : NULL;

			if ($force_uid == $id)
			{
				return TRUE;
			}
		}

		if (is_array($ids))
		{
			foreach ($ids as $key) 
			{
				if (in_array($key, $roles))
				{
					return TRUE;
				}
			}
		}
		else 
		{
			if (is_array($roles) && in_array($ids, $roles))
			{
				return TRUE;
			}
		}	

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Avatar
	 * 
	 * Berfungsi untuk menampilkan foto avatar pengguna
	 *
	 * @return string
	 */

	function avatar($id, $size = '', $border = NULL, $class = NULL)
	{
		// Load URL Extension
		$url = load_ext('url');

		// Connected to the database
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select avatar from uc_user_information where user_id = :user_id");
		$bindParam = $db->sql_bindParam(['user_id' => $id], $res);
		$row = $db->sql_fetch_array($bindParam);

		if ($border == 1)
		{
			$border = 'border: 3px #fff solid;';
		}

		if (empty($size))
		{
			if ( ! $row['avatar'])
			{
				$resize = 'style="font-size: 5em;"';
			}
			else 
			{
				$resize = 'style="width: 70px;height: 70px;'.$border.'"';
			}
		}
		else
		{
			if ( ! $row['avatar'])
			{
				if ( ! is_numeric($size) && $size == 'small')
				{
					$resize = 'style="width: 32px;height: 32px;vertical-align: middle;"';
				}
				else 
				{
					$resize = 'style="width: '.$size.'px;height: '.$size.'px;vertical-align: middle;"';
				}
			}
			else 
			{
				if ( ! is_numeric($size) && $size == 'small')
				{
					$resize = 'style="width: 32px;height: 32px;'.$border.'"';
				}
				else 
				{
					$resize = 'style="width: '.$size.'px;height: '.$size.'px;'.$border.'"';
				}
			}
		}

		if ( ! $row['avatar'])
		{
			$avatar = '<i class="fas fa-user-circle '.$class.'" '.$resize.'></i>';
		}
		else 
		{
			$avatar = '<img src="'.base_url($row['avatar']).'" class="rounded-circle '.$class.'" '.$resize.'>';
		}

		return $avatar;
	}

	// ------------------------------------------------------------------------

	/**
	 * Do Access
	 * 
	 * Berfungsi untuk otentikasi pengguna untuk mengakses halaman
	 * 
	 * @return string
	 */

	function do_access($uid = 0, $ids, $force_uid = 0)
	{
		$username = isset($_SESSION['username']) ? $_SESSION['username'] : NULL;

		if ( ! user_auth($uid, $ids, $force_uid, $username))
		{
			section_close('<div class="card card-full-color card-full-warning" role="alert"><div class="card-body"><i class="fas fa-exclamation-triangle mr-1"></i> The page you requested cannot be displayed right now. It may be temporarily unavailable, the link you clicked on may be broken or expired, or you may not have permission to view this page.</div></div>');
		}
		else 
		{
			section_content();
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Allow Access
	 * 
	 * Berfungsi untuk membatasi siapa saja pengguna yang dapat melihat fitur atau modul
	 *
	 * @return boolean
	 */

	function allow_access($ids, $force_uid = 0)
	{
		$username	= isset($_SESSION['username']) ? $_SESSION['username'] : NULL;
		$id 		= isset($_SESSION['id']) ? $_SESSION['id'] : NULL;

		if ( ! user_auth($id, $ids, $force_uid, $username))
		{
			return FALSE;
		}
		else 
		{
			return TRUE;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Role
	 * 
	 * Berfungsi untuk mendapatkan atau menampilkan status atau peran akun pengguna.
	 *
	 * @return string
	 */

	function get_role($id = 0)
	{
		// Connected to the database
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select a.*, a.id as uid, r.* from uc_accounts as a join uc_roles as r on r.id = a.roles where r.code_name = a.role_code and a.id = :id");
		$bindParam = $db->sql_bindParam(['id' => $id], $res);
		$row = $db->sql_fetch_array($bindParam);
	
		return $row['name'];
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Config Site
	 * 
	 * Berfungsi untuk mendapatkan atau menampilkan konfigurasi halaman situs
	 * seperti, nama situs, slogan, kata kunci, dsb.
	 *
	 * @return string
	 */

	function get_csite($key)
	{
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select * from uc_site_config where id = :id");
		$bindParam = $db->sql_bindParam(['id' => 1], $res);
		$row = $db->sql_fetch_array($bindParam);

		return $row[$key];
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Config Title
	 * 
	 * Berfungsi untuk mendapatkan judul halaman situs
	 * 
	 * @return string
	 */

	function get_ctitle()
	{
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select * from uc_site_config where id = :id");
		$bindParam = $db->sql_bindParam(['id' => 1], $res);
		$row = $db->sql_fetch_array($bindParam);

		if ( ! get_data_global('title'))
		{
			return $row['site_name'].' - '.$row['site_slogan'];
		}
		else 
		{
			return get_data_global('title');
		}	
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Client Value
	 * 
	 * Hampir sama fungsinya dengan fungsi get_client() fungsi ini menampilkan
	 * informasi data pengguna nama kolom yang dimasukkan.
	 * 
	 * @return string
	 */

	function get_client_value($key = '', $value = '',  $coloum = '')
	{
		// Connected to the database
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select * from uc_accounts where $key = :$key");
		$bindParam = $db->sql_bindParam([$key => $value], $res);
		$row = $db->sql_fetch_array($bindParam);
	
		return $row[$coloum];
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Client
	 * 
	 * Hampir sama fungsinya dengan fungsi get_user() fungsi ini menampilkan
	 * informasi data pengguna per id akun bukan per session pengguna.
	 * 
	 * @return string
	 */

	function get_client($key = '',  $coloum = '')
	{
		// Connected to the database
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select * from uc_accounts where id = :id");
		$bindParam = $db->sql_bindParam(['id' => $key], $res);
		$row = $db->sql_fetch_array($bindParam);
	
		return $row[$coloum];
	}

	function get_info_client($key = '',  $coloum = '')
	{
		// Connected to the database
		$db = load_db('default', 'MySQL');

		$res = $db->sql_prepare("select * from uc_user_information where user_id = :user_id");
		$bindParam = $db->sql_bindParam(['user_id' => $key], $res);
		$row = $db->sql_fetch_array($bindParam);
	
		return $row[$coloum];
	}

	// ------------------------------------------------------------------------

	/**
	 * Get User
	 * 
	 * Berfungsi untuk mendapatkan informasi data pengguna per session
	 * 
	 * @return string
	 */

	function get_user($key)
	{
		// Connected to the database
		$db = load_db('default', 'MySQL');

		$username	= isset($_SESSION['username']) ? $_SESSION['username'] : NULL;
		$id 		= isset($_SESSION['id']) ? $_SESSION['id'] : NULL;

		$res = $db->sql_prepare("select * from uc_accounts where username = :username and id = :id");
		$bindParam = $db->sql_bindParam(['username' => $username, 'id' => $id], $res);
		$row = $db->sql_fetch_array($bindParam);
	
		$row[$key] = isset($row[$key]) ? $row[$key] : NULL;
		return $row[$key];
	}

	// ------------------------------------------------------------------------

	/**
	 * Check Login
	 * 
	 * Berfungsi untuk memeriksa penggun sudah login atau tidak
	 * 
	 * @return boolean
	 */

	function check_login()
	{
		// Load URL Extension
		$url = load_ext('url');
	
		if (empty($_SESSION['username']) && empty($_SESSION['id']))
		{
			redirect('auth/login');
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Error Page
	 * 
	 * Berfungsi untuk menampilkan halaman error
	 * 
	 * @return string
	 */

	function error_page($msg = '')
	{
		if (empty($msg))
		{
			$msg = 'The page you requested cannot be displayed right now. It may be temporarily unavailable, the link you clicked on may be broken or expired, or you may not have permission to view this page.';
		}

		section_content('<div class="card card-full-color card-full-warning mx-3 mt-3" role="alert"><div class="card-body"><i class="fas fa-exclamation-triangle mr-1"></i> '.$msg.'</div></div>');
		stop_here();
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Date
	 * 
	 * Berfungsi untuk mengubah waktu sistem UNIX dan menampilkan waktu umum
	 * 
	 * @return string
	 */

	function get_date($timeo, $type = 'time') 
	{
		// Default set timezone is +7 for Jakarta, Indonesia
		$timezone = +7;

		// Default set for some settings
		$settings = [
			'time_format' 	 => 'g:i a',
			'date_format' 	 => 'M jS Y',
			'date_today' 	 => 'Today',
			'date_yesterday' => 'Yesterday'
		];

		$timeline = $timeo+$timezone*3600;
		$current = time()+$timezone*3600;
		$it_s = intval($current - $timeline);
		$it_m = intval($it_s/60);
		$it_h = intval($it_m/60);
		$it_d = intval($it_h/24);
		$it_y = intval($it_d/365);

		$timec = time()-$timeo;

		if ($timec < 3600 && $timec >= 0) 
		{
			return ceil($timec/60).' menit lalu';
		}
		elseif ($timec < 12*3600 && $timec >= 0) 
		{
			return ceil($timec/3600).' jam lalu';
		}
		else 
		{
			if ($type == 'time') 
			{
				return gmdate($settings['date_format'].', '.$settings['time_format'], $timeline);
			}
			else 
			{
				return gmdate($settings['date_format'], $timeline);
			}
		}

		if ($type == 'date') 
		{
			return gmdate($settings['date_format'], $timeline);
		}
		else 
		{
			if (gmdate("j", $timeline) == gmdate("j", $current)) 
			{
				return $settings['date_today'].', '.gmdate($settings['time_format'], $timeline);
			}
			elseif (gmdate("j", $timeline) == gmdate("j", ($current-3600*24))) 
			{
				return $settings['date_yesterday'].', '.gmdate($settings['time_format'], $timeline);
			}
			return gmdate($settings['date_format'].', '.$settings['time_format'], $timeline);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Format Size
	 * 
	 * Berfungsi untuk menampilkan ukuran berkas
	 * dalam satuan KB, MB, GB, TB, dsb.
	 * 
	 * @return string
	 */

	function format_size($file) 
	{
		if ( ! file_exists($file)) 
		{
			$bytes = '';
		}
		else 
		{
			$bytes = filesize($file);
		}

		if ($bytes < 1024) 
		{
			return $bytes.' B';
		} 
		elseif ($bytes < 1048576) 
		{
			return round($bytes / 1024, 2).' KB';
		}
		elseif ($bytes < 1073741824) 
		{
			return round($bytes / 1048576, 2).' MB';
		}
		elseif ($bytes < 1099511627776) 
		{
			return round($bytes / 1073741824, 2).' GB';
		}
		elseif ($bytes < 1125899906842624) 
		{
			return round($bytes / 1099511627776, 2).' TB';
		}
		elseif ($bytes < 1152921504606846976) 
		{
			return round($bytes / 1125899906842624, 2).' PB';
		}
		elseif ($bytes < 1180591620717411303424) 
		{
			return round($bytes / 1152921504606846976, 2).' EB';
		}
		elseif ($bytes < 1208925819614629174706176) 
		{
			return round($bytes / 1180591620717411303424, 2).' ZB';
		}
		else 
		{
			return round($bytes / 1208925819614629174706176, 2).' YB';
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Register JS
	 * 
	 * Berfungsi untuk mendaftarkan file javascript per module
	 * 
	 * @return string
	 */

	function register_js($file = array())
	{
		$GLOBALS['register_js'] = implode("\r\n 			", $file);

		return $GLOBALS['register_js'];
	}

	// ------------------------------------------------------------------------

	/**
	 * Load JS
	 * 
	 * Berfungsi untuk menampilkan berkas javascript yang telah didaftarkan
	 * fungsi diletakan difolder tema
	 * 
	 * @return string
	 */

	function load_js()
	{
		return get_data_global('register_js');	
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('cs_offset'))
	{
		function cs_offset()
		{
			return cs_num_per_page()*(get_data_global('page')-1);
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('cs_num_per_page'))
	{
		function cs_num_per_page()
		{
			$cs_config = load_cs_config('config');

			return $cs_config->item('num_per_page_exam');
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('get_content_page'))
	{
		function get_content_page($parent, $type, $key)
		{
			// Connected to the database
			$db = load_db('default', 'MySQL');

			$res = $db->sql_prepare("select * from uc_page_content where parent = :parent and type = :type");
			$bindParam = $db->sql_bindParam(['parent' => $parent, 'type' => $type], $res);
			$row = $db->sql_fetch_array($bindParam);

			return $row[$key];
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('get_cover_image'))
	{
		function get_cover_image($uri, $type, $key)
		{
			// Connected to the database
			$db = load_db('default', 'MySQL');

			$res = $db->sql_prepare("select * from uc_cover_image where uri = :uri and type = :type");
			$bindParam = $db->sql_bindParam(['uri' => $uri, 'type' => $type], $res);
			$row = $db->sql_fetch_array($bindParam);

			$row[$key] = isset($row[$key]) ? $row[$key] : NULL;
			return $row[$key];
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('send_mail'))
	{
		function send_mail($key = array())
		{
			// Connected to the database
			$db = load_db('default', 'MySQL');

			// Load Email
			$email = load_lib('email');

			$res = $db->sql_prepare("select * from uc_smtp where id = 1", "select");
			$row = $db->sql_fetch_array($res);

			$config['protocol'] = $row['protocol'];
			$config['smtp_host'] = $row['smtp_host'];
			$config['smtp_port'] = $row['smtp_port']; // 8025, 587 and 25 can also be used. Use Port 465 for SSL.
			$config['smtp_crypto'] = $row['smtp_crypto'];
			$config['smtp_user'] = $row['smtp_user'];
			$config['smtp_pass'] = $row['smtp_pass'];
			$config['mailtype']	= $row['mailtype'];
			$config['charset']	= 'utf-8';
			$config['crlf']		= '\r\n';
			$config['newline']	= '\r\n';
			$config['wordwrap']	= TRUE;

			$email->initialize($config);
			$email->from($key['email'], $key['name']);
			$email->to($key['destination_email']);
			$email->subject($key['subject']);
			$email->message($key['message']);

			if (isset($key['attachement_0']))
			{
				$email->attach(base_url($key['attachement_0']));
			}

			if (isset($key['attachement_1']))
			{
				$email->attach(base_url($key['attachement_1']));
			}

			return $email->send();
		}
	}

?>