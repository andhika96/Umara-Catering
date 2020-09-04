$(document).ready(function() {

	/**
	 * AJAX JQuery Upload for User or Candidate
	 */

	$uploadCrop = $('#upload-demo').croppie({
		enableExif: true,
		url: $baseurl+'/assets/images/undraw_profile_pic_ic5t.svg',
		viewport: {
			width: 200,
			height: 200,
			type: 'square'
		},
		boundary: {
			width: 220,
			height: 220
		}
	});

	function clearImage(ctrl) 
	{
		if ($(ctrl).val() == "") 
		{
			$('#upload-demo').croppie('destroy');
			$uploadCrop = $('#upload-demo').croppie(opts);
		}
	}

	$('#upload').on('change', function() 
	{
		$("#overlay_profile_picture").css("display", "block");
		$("#overlay_profile_picture").css("top", "93px");

		if (this.files && this.files[0]) 
		{
			if (/^image/.test(this.files[0].type)) 
			{
				var reader = new FileReader();
				reader.onload = function(e) 
				{
					$uploadCrop.croppie('bind', 
					{
						url: e.target.result
					}).then(function() 
					{
						console.log('jQuery bind complete');
						$("#overlay_profile_picture").css("display", "none");
					});
					$(".alert").remove();
					$(".upload-result").css("display", "block");
				}
				
				reader.readAsDataURL(this.files[0]);
			}
			else 
			{
				$(".upload-result").css("display", "none");
				$("#overlay_profile_picture").css("display", "none");
				$(".warning-effect").html('<div class="alert alert-danger" role="alert" style="margin-bottom: 2rem;"><i class="fas fa-exclamation-triangle fa-fw" aria-hidden="true"></i> Errors: You may only select image files</div>');
			}
		}
		else 
		{
			$(".upload-result").css("display", "none");
			$("#overlay_profile_picture").css("display", "none");
			$(".warning-effect").html('<div class="alert alert-danger" role="alert" style="margin-bottom: 2rem;"><i class="fas fa-exclamation-triangle fa-fw" aria-hidden="true"></i> Errors: Sorry - you\'re browser doesn\'t support the FileReader API</div>');
		}
	});

	$('.upload-result').on('click', function(ev) 
	{
		let post = 'post';
		let csrfName = $(".btn-token").attr("name");
		let csrfHash = Cookies.get("ardev_ucpitaloka");

		$("#overlay_profile_picture").css("display", "block");

		$uploadCrop.croppie('result', 
		{
			type: 'canvas',
			size: 'viewport'
		}).then(function(resp) 
		{
			let file_input = $('#upload').val();

			$.ajax({
				url: $baseurl+"/index.php?p=account/avatar",
				type: "POST",
				dataType: "JSON",
				data: {"image":resp, "file":file_input, "step":post, [csrfName]: csrfHash},
				success: function (data) 
				{
					csrfName = data.csrfName;
					csrfHash = data.csrfHash;

					if (data.status == 'success') 
					{
						window.location.href = $baseurl+"/index.php?p=account/avatar";

						$("#overlay_profile_picture").css("display", "none");
					}
					else if (data.status == 'failed') 
					{
						console.log(data.message);

						$("#overlay_profile_picture").css("display", "none");
						$("#overlay_profile_picture").css("top", "172px");
						$(".warning-effect").html('<div class="alert alert-danger" role="alert" style="margin-bottom: 2rem;"><i class="fas fa-exclamation-triangle mr-2"></i> Errors: '+data.message+'</div>');
					}					
				}
			});

			return false;
		});	
	});

});