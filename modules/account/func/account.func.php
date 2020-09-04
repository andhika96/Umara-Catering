<?php

	/*
	 *  Aruna Project
	 *  IS NOT FREE SOFTWARE
	 *  Codename: Aruna Personal Site
	 *  Source: Based on Sosiaku Social Networking Software
	 *  Website: https://www.sosiaku.gq
	 *  Created by Andhika Adhitia N
	 */

	defined('MODULEPATH') OR exit('No direct script access allowed');

	function menu_settings()
	{
		// Load URL Extension
		$url = load_ext('url');

		$output = '
				<div class="card ar-card">
					<div class="card-header card-header-divider m-0 p-4">
						Settings
					</div>

					<div class="list-group list-group-flush">
						<a href="'.site_url('account').'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><div><i class="far fa-user-circle mr-2"></i> Profile</div> <span><i class="fas fa-angle-right"></i></span></a>
						<a href="'.site_url('account/settings').'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><div><i class="fas fa-cog mr-2"></i> Settings</div> <span><i class="fas fa-angle-right"></i></span></a>
						<a href="'.site_url('account/avatar').'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><div><i class="fas fa-camera mr-2"></i> Avatar</div> <span><i class="fas fa-angle-right"></i></span></a>
					</div>
				</div>
		';

		return $output;
	}

?>