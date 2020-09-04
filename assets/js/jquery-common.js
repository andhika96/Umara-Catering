$(document).ready(function() 
{
	$("input[type='file']").change(function(e) 
	{
		let parent = $(this).parents(".custom-file");
		let fileName = e.target.files[0].name;

		parent.find(".custom-file-label").html(fileName);
	});

	$(function() 
	{
		$("[data-toggle='tooltip']").tooltip();
	});

	$(document).on("submit", ".ug-form-submit", function() 
	{
		let thisform = $(this);
		let action = $(this).attr("action");
		let btn = $(this).find("[type=submit]");
		let formData = new FormData();

		let dialog = bootbox.dialog({ 
			message: '<div class="text-center"><div class="spinner-grow text-secondary mb-1" role="status"></div> <p class="mb-0">Submitting ...</p></div>', 
			closeButton: false,
		});

		$.each($(this).find("input[type='file']"), function(i, tag)
		{
			$.each($(tag)[0].files, function(i, file) {
				formData.append(tag.name, file);
			});
		});

		let params = $(this).serializeArray();

		$.each(params, function (i, val) 
		{
			formData.append(val.name, val.value);
		});

		$.ajax({
			url: action,
			type: 'POST',
			data: formData,
			async: true,
			cache: true,
			dataType: "JSON",
			processData: false,
			contentType: false,

			beforeSend: function() 
			{
				btn.addClass("disabled");
				btn.attr("aria-disabled", "true");
			},
			success: function(data) 
			{
				btn.removeClass("disabled");
				btn.attr("aria-disabled", "false");

				if (data.act == 'redirect') 
				{
					window.location.href = data.url;
				}
				else if (data.act == 'submitted') 
				{
					dialog.init(function() 
					{
						setTimeout(function()
						{
							dialog.find('.bootbox-body').html('<button type="button" class="bootbox-close-button close" aria-hidden="true">×</button><div class="text-center"><i class="fas fa-check text-success fa-2x mb-2"></i> <p class="mt-2 mb-0">'+data.message+'</p></div>');
						}, 1000);
					});

					$(".custom-file").find(".custom-file-label").html('');
					$(".ug-form-submit")[0].reset();
					
					// $(".ug-sys-notice").html('<div class="card card-full-color card-full-success mt-n1 mb-4" role="alert"><div class="card-body p-3"><i class="fas fa-exclamation-triangle mr-1"></i> '+data.message+'</div></div>');
				}
				else if (data.act == 'hold') 
				{
					dialog.init(function() 
					{
						setTimeout(function()
						{
							dialog.find('.bootbox-body').html('<button type="button" class="bootbox-close-button close" aria-hidden="true">×</button><div class="text-center"><i class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i> <p class="mt-2 mb-0">'+data.message+'</p></div>');
						}, 1000);
					});
					
					// $(".ug-sys-notice").html('<div class="card card-full-color card-full-danger mt-n1 mb-4" role="alert"><div class="card-body p-3"><i class="fas fa-exclamation-triangle mr-1"></i> '+data.message+'</div></div>');
				}
			}
		});

		return false;
	});
});