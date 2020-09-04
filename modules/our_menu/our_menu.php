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

class our_menu {

	// Default variable for load extension
	protected $ext;

	protected $db;
	
	public function __construct() 
	{
		$this->ext = load_ext(['url']);

		// Connected to the database
		$this->db = load_db('default', 'MySQL');
	}

	public function index()
	{
		set_title('Our Menu');

		section_header('
		<div class="content-header position-relative">
			<div class="page-header header-filter header-small lazy" data-bg="url('.base_url(get_cover_image('our-menu', 'cover-image', 'image')).')">
				<div class="container">
					<div class="row mt-4">
						<div class="col-md-9 ml-auto mr-auto text-center" style="text-shadow: 2px 2px 3px rgba(0,0,0,0.7) !important">
							<h2 class="uc-h3 uc-heading-cg">'.get_cover_image('our-menu', 'cover-image', 'title').'</h2>
							<h5 class="font-weight-light">'.get_cover_image('our-menu', 'cover-image', 'caption').'</h5>
						</div>
					</div>
				</div>
			</div>
		</div>');

		section_content('
		<style>
		.uc-list-our-menu .list-group-item
		{
			color: #333;
			font-weight: 600;
			margin-bottom: .5rem;
			border-color: transparent;
		}

		.uc-list-our-menu .list-group-item.active
		{
			color: #fff;
			border-radius: .25rem;
			text-decoration: none;
			background: #caa04a !important;
		}

		.uc-list-our-menu .list-group-item:hover,
		.uc-list-our-menu .list-group-item:focus
		{
			color: #fff;
			border-radius: .25rem;
			text-decoration: none;
			background: #caa04a !important;
		}
		</style>

		<div class="container my-5">
			<div class="row">
				<div class="col-md-3">
					<div class="bg-grey-alt shadow-sm rounded p-2">
						<div class="list-group list-group-flush uc-list-our-menu nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
							<!---
							<a href="#v-pills-home" class="list-group-item bg-transparent active" id="v-pills-home-tab" data-toggle="pill" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fas fa-drumstick-bite fa-lg fa-fw mr-2"></i> Buffet</a>
							<a href="#v-pills-profile" class="list-group-item bg-transparent" id="v-pills-profile-tab" data-toggle="pill" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fas fa-pepper-hot fa-lg fa-fw mr-2"></i> Aneka Nusantara</a>
							<a href="#!" class="list-group-item bg-transparent"><i class="fas fa-fish fa-lg fa-fw mr-2"></i> Stall Menu</a>
							<a href="#!" class="list-group-item bg-transparent"><i class="fas fa-pizza-slice fa-lg fa-fw mr-2"></i> Others</a>
							<a href="#!" class="list-group-item bg-transparent"><i class="fas fa-stroopwafel fa-lg fa-fw mr-2"></i> Canape</a>
							--->');

					$i = 1;
					$res = $this->db->sql_prepare("select * from uc_menu_category order by id asc", "select");
					while ($row = $this->db->sql_fetch_array($res))
					{
						if ($i == 1)
						{
							section_content('
								<a class="list-group-item bg-transparent active" id="v-pills-'.$row['uri'].'-tab" data-toggle="pill" href="#v-pills-'.$row['uri'].'" role="tab" aria-controls="v-pills-'.$row['uri'].'" aria-selected="true">'.$row['name'].'</a>');
						}
						else
						{
							section_content('
								<a class="list-group-item bg-transparent" id="v-pills-'.$row['uri'].'-tab" data-toggle="pill" href="#v-pills-'.$row['uri'].'" role="tab" aria-controls="v-pills-'.$row['uri'].'" aria-selected="false">'.$row['name'].'</a>');							
						}

						$i++;
					}						

		section_content('
						</div>
					</div>
				</div>

				<div class="col-md-9">
					<div class="bg-grey-alt shadow-sm rounded p-4">
						<div class="tab-content" id="v-pills-tabContent">');

					$i2 = 1;
					$i_menu_cat = 1;
					$res2 = $this->db->sql_prepare("select * from uc_menu_category order by id asc", "select");
					while ($row2 = $this->db->sql_fetch_array($res2))
					{
						if ($i2 == 1)
						{
							section_content('
								<div class="tab-pane fade show active" id="v-pills-'.$row2['uri'].'" role="tabpanel" aria-labelledby="v-pills-'.$row2['uri'].'-tab">');

							$res_menu_cat = $this->db->sql_prepare("select * from uc_menu where menu_cat = :menu_cat order by id asc");
							$bindParam_menu_cat = $this->db->sql_bindParam(['menu_cat' => $row2['id']], $res_menu_cat);
							while ($row_menu_cat = $this->db->sql_fetch_array($bindParam_menu_cat))
							{
								// $content 	= trim(preg_replace('/\s+/', ' ', $row_menu_cat['content']));
								$content 	= str_replace("\r", "", $row_menu_cat['content']);
								$content 	= str_replace("\n", "", $row_menu_cat['content']);
								$content 	= explode(' | ', $row_menu_cat['content']);
								$content 	= explode('|', $row_menu_cat['content']);
								$total 		= count($content);

								section_content('
											<div class="mb-4">
												<div class="pb-3 mb-4 border-bottom font-weight-bold text-brown h6"><i class="fas fa-caret-right fa-lg fa-fw"></i> '.$row_menu_cat['title'].'</div>

												<div class="row px-1">');

								foreach ($content as $key => $value)
								{
									section_content('
													<div class="col-md-4 mb-3">
														<div class="pb-2 uc-border-bottom-td" style="color: #424242;font-weight: 600">
															'.$value.'
														</div>
													</div>');
								}

								section_content('
												</div>
											</div>');
							}

							section_content('
								</div>');
						}
						else
						{
							section_content('
								<div class="tab-pane fade" id="v-pills-'.$row2['uri'].'" role="tabpanel" aria-labelledby="v-pills-'.$row2['uri'].'-tab">');

							$res_menu_cat = $this->db->sql_prepare("select * from uc_menu where menu_cat = :menu_cat order by id asc");
							$bindParam_menu_cat = $this->db->sql_bindParam(['menu_cat' => $row2['id']], $res_menu_cat);
							while ($row_menu_cat = $this->db->sql_fetch_array($bindParam_menu_cat))
							{
								// $content 	= trim(preg_replace('/\s+/', ' ', $row_menu_cat['content']));
								$content 	= str_replace("\r", "", $row_menu_cat['content']);
								$content 	= str_replace("\n", "", $row_menu_cat['content']);
								$content 	= explode(' | ', $row_menu_cat['content']);
								$content 	= explode('|', $row_menu_cat['content']);
								$total 		= count($content);

								section_content('
											<div class="mb-4">
												<div class="pb-3 mb-4 border-bottom font-weight-bold text-brown h6"><i class="fas fa-caret-right fa-lg fa-fw mr"></i> '.$row_menu_cat['title'].'</div>

												<div class="row px-1">');

								foreach ($content as $key => $value)
								{
									section_content('
													<div class="col-md-4 mb-3">
														<div class="pb-2 uc-border-bottom-td" style="color: #424242;font-weight: 600">
															'.$value.'
														</div>
													</div>');
								}

								section_content('
												</div>
											</div>');
							}

							section_content('
								</div>');							
						}
					
						$i2++;
					}

		section_content('
							<!---
							<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
								<div class="mb-4">
									<div class="pb-3 mb-4 border-bottom font-weight-bold text-muted h6"><i class="fas fa-caret-right fa-lg fa-fw"></i> APPETIZERS</div>

									<div class="row px-1">
										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Selada Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Lawar Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Asinan Jakarta
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Selada Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Lawar Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Asinan Jakarta
											</div>
										</div>
									</div>
								</div>

								<div class="mb-4">
									<div class="pb-3 mb-4 border-bottom font-weight-bold text-muted h6"><i class="fas fa-caret-right fa-lg fa-fw"></i> RICE</div>

									<div class="row px-1">
										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Selada Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Lawar Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Asinan Jakarta
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Selada Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Lawar Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Asinan Jakarta
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
								<div class="mb-4">
									<div class="pb-3 mb-4 border-bottom font-weight-bold text-muted h6"><i class="fas fa-caret-right fa-lg fa-fw"></i> Nasi Umara</div>

									<div class="row px-1">
										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Selada Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Lawar Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Asinan Jakarta
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Selada Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Lawar Ayam Bali
											</div>
										</div>

										<div class="col-md-4 mb-3">
											<div class="pb-2 font-weight-bold uc-border-bottom-td">
												Asinan Jakarta
											</div>
										</div>
									</div>
								</div>
							</div>
							--->

						</div>
					</div>
				</div>
			</div>
		</div>
		');
	}

	public function test()
	{
		// $data 		= 'Selada Ayam Bali: 85 | Lawar Ayam Bali: 50 | Asinan Jakarta: 35';
		$data 		= '
Mie Ayam | Mie Yamin | Siomay Bandung | Lumpia Semarang | Mie Ayam Bakso 
Mie Ayam Jamur | Mie Godok Jawa | Mie Hijau Ayam Jamur | Mie Kocok Bandung 
Bakso Cabe Rawit | Bakwan Malang | Baso Urat Sapi | Bakso Rusuk Iga Sapi 
Bakso Urat Kikil Sapi | Bakso Campur Umara | Tahu Gimbal Semarang | Otak-otak Palembang 
Laksa Bogor | Soto Mie Bogor | Selat Solo | Tekwan 
Wonton Lo Mie Kangkung | Bakso Iga Sapi | Tengkleng Iga Sapi | Tengkleng Kambing 
Pempek Palembang | Baso Sapi Komplit (urat, babat)';

		$data 		= trim(preg_replace('/\s+/', ' ', $data));
		$explode 	= explode(" | ", $data);

		echo '<div class="row px-1">';

		foreach ($explode as $key => $value) 
		{
			// echo $value.' <br/>';

			$get_item_price = preg_replace("/[^0-9]/", " ", $value);
			$get_item_name = preg_replace("/[^a-zA-Z]/", " ", $value);

			echo '
			<div class="col-md-4 mb-3">
				<div class="pb-2 font-weight-bold uc-border-bottom-td">
					'.$get_item_name.' - '.$get_item_price.'
				</div>
			</div>';
		}

		echo '</div>';

		// var_dump($explode);
	}
}

?>