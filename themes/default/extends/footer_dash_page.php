<?php

	section_footer('
					</div>
				</div>
				<!--- End of Body --->

				<div class="arv2-admin-footer text-muted d-md-flex justify-content-md-between mt-4 pt-md-4 mb-4 mr-5 mr-xl-4">
					<div class="mb-3 mb-md-0">
						Made with <i class="fas fa-heart mx-1"></i> & <i class="fas fa-coffee mx-1"></i> by <a href="https://www.instagram.com/andhika_adhitia/" target="_blank">Andhika Adhitia N</a>
					</div>

					<div>
						&copy; 2019 Aruna Development Project
					</div>
				</div>
			</div>
		
			<!-- Optional JavaScript -->
			<!-- jQuery first, then Popper.js, then Bootstrap JS, and other -->
			<script src="'.base_url('assets/js/jquery-3.4.1.min.js').'"></script>
			<script src="'.base_url('assets/js/js.cookie-2.2.1.min.js').'"></script>
			<script src="'.base_url('assets/js/jquery-ui-1.12.1.min.js').'"></script>
			<script src="'.base_url('assets/js/popper.min.js').'"></script>
			<script src="'.base_url('assets/plugins/bootbox/bootbox.all.min.js').'"></script>
			<script src="'.base_url('assets/plugins/bootstrap/4.4.1/js/bootstrap.min.js').'"></script>
			<script src="'.base_url('assets/plugins/fontawesome/5.11.2/js/all.min.js').'"></script>
			<script src="'.base_url('assets/js/new_aruna_admin.js?v=0.0.1').'"></script>
			<script src="'.base_url('assets/plugins/perfect-scrollbar/1.4.0/dist/perfect-scrollbar.min.js').'"></script>

			<script>
			$(document).ready(function() 
			{ 
				$(".active-perfect-scrollbar").each(function() { const ps = new PerfectScrollbar($(this)[0]); });
				$baseurl = "'.site_url().'";
			});
			</script>

			'.load_js().'
		</body>
	</html>
	');

?>