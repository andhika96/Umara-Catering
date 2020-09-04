$(document).ready(function() 
{
	$('input[type="file"]').change(function(e) 
	{
		let parent = $(this).parents(".custom-file");
		let fileName = e.target.files[0].name;

		parent.find(".custom-file-label").html(fileName);
	});

	$(function() 
	{
		$('[data-toggle="tooltip"]').tooltip();
	});

	function showDiv(divId, element)
	{
		document.getElementById(divId).style.display = element.value == 1 ? 'block' : 'none';
	}

	$(".ug-select").change(function() 
	{
		if ($(this).val() == 4)
		{	
			// alert($(this).val());
			$(".ug-message-fmail").css("display", "block");
		}
		else
		{
			$(".ug-message-fmail").css("display", "none");
		}
	});

	// Listen for click on toggle checkbox
	$("#fileSelectAll").click(function(event) 
	{
		if (this.checked) 
		{
			// Iterate each checkbox
			$(".checkids").each(function() 
			{
				this.checked = true;                        
			});
		} 
		else 
		{
			$(".checkids").each(function() 
			{
				this.checked = false;                       
			});
		}
	});

	$("input.arv2-admin-form-search").bind("focus blur", function () 
	{
	 	$(".arv2-admin-search-box").toggleClass("focus");
	});

	if (window.pageYOffset != 0)
	{
		$(".arv2-admin-navbar-nav").addClass("arv2-admin-navbar-nav-white");
	}

	$(window).scroll(function() 
	{
		if ($(window).scrollTop() == 0) 
		{
			$(".arv2-admin-navbar-nav").removeClass("arv2-admin-navbar-nav-white");
		}
		else if (window.pageYOffset != 0)
		{
			$(".arv2-admin-navbar-nav").addClass("arv2-admin-navbar-nav-white");
		}
	});

	var main = function() 
	{
		$(".icon-open").click(function() 
		{
			$(".arv2-admin-sidebar").animate({left: "0px"}, "easing");
			$(".arv2-admin-main-content").animate({marginLeft: "18rem"}, "easing");
			$("body").css("overflow-x","hidden");

			$(".menu-close").removeClass('d-none');
			$(".menu-open").toggleClass('d-block d-none');
		});

		$(".icon-close").click(function() 
		{
			$(".arv2-admin-sidebar").animate({left: "-18rem"}, "easing");
			$(".arv2-admin-main-content").animate({marginLeft: "1.5rem"}, "easing");
			$("body").css("overflow-x", "none");

			$(".menu-close").addClass('d-none');
			$(".menu-open").toggleClass('d-none d-block');
		});

		$(".icon-open-mobile").click(function() 
		{
			$(".arv2-admin-sidebar").animate({left: "0px"}, "easing");
			$(".arv2-admin-main-content").animate({left: "18rem"}, "easing");
			$("body").css("overflow-x","hidden");

			$(".menu-open-mobile").addClass('d-none');
			$(".menu-close-mobile").toggleClass('d-block d-none');
		});

		$(".icon-close-mobile").click(function() 
		{
			$(".arv2-admin-sidebar").animate({left: "-18rem"}, "easing");
			$(".arv2-admin-main-content").animate({left: "1.5rem"}, "easing");
			$("body").css("overflow-x", "none");

			$(".menu-open-mobile").removeClass('d-none');
			$(".menu-close-mobile").toggleClass('d-none d-block');
		});
	};

	$(document).ready(main);

	// JQuery Plugin Bootbox
	$(document).on("click", ".ar-show-alert-del", function(e) 
	{
		let data = $(this).attr("data-url");

		bootbox.confirm(
		{
			title: "Confirmation Message",
			message: "Are you sure, do you want to delete this item?",
			buttons: 
			{
				cancel: 
				{
					className: 'btn-danger',
					label: '<i class="fas fa-times fa-fw mr-1"></i> Cancel'
				},
				confirm: 
				{
					className: 'btn-success',
					label: '<i class="fas fa-check fa-fw mr-1"></i> Confirm'
				}
			},
			callback: function (result) 
			{
				console.log(result);

				if (result == true)
				{
					window.location.href = data;
				}
			}
		});
	});

	$(document).on("click", ".ar-show-alert", function(e) 
	{
		bootbox.confirm(
		{
			title: "<i class=\"fas fa-exclamation-triangle text-danger mr-2\"></i> Notice Message",
			message: "<div class=\"text-danger w-100\" style=\"font-size: 1rem\">You can't do this right now</div>",
			buttons: 
			{
				cancel: 
				{
					className: 'btn-danger d-none',
					label: '<i class="fas fa-times fa-fw mr-1"></i> Cancel'
				},
				confirm: 
				{
					className: 'btn-primary',
					label: '<i class="fas fa-check fa-fw mr-1"></i> Confirm'
				}
			},
			callback: function (result) 
			{
				console.log(result);
			}
		});
	});

	var maxAppend = 0;

	$(document).on("click", "#add_another_photo", function() 
	{
		if (maxAppend > 8) 
		{
			$("#add_another_photo").css("display", "none");
		}

		if (maxAppend >= 9) 
		{
			return;
		}

		var addinput = $(
		'<div class="custom-file mb-2">'+
		'<input type="file" name="images[]" class="custom-file-input" id="customFile'+maxAppend+'">'+
		'<label class="custom-file-label" for="upload">Choose file</label>'+
		'</div>');

		maxAppend++;

		$("#add_another_photo").before(addinput);

		$('input[type="file"]').change(function(e) 
		{
			let parent = $(this).parents(".custom-file");
			let fileName = e.target.files[0].name;

			parent.find(".custom-file-label").html(fileName);
		});
	});

	var chatupdatehandleProfile = 0;

	$("#DropDown_Notification").click(function()
	{
		var test = $(this).parents(".show");

		if (test)
		{
			$(".notification-modal").html("<div class=\"text-center p-4\"><div class=\"spinner-grow text-info\" role=\"status\"><span class=\"sr-only\">Loading...</span></div></div>");

			setTimeout(function() 
			{
				$(".notification-modal").load($baseurl+"notifications/modal_notifications", function()
				{
					var data = $(".list-group-modal").map(function() 
					{
						return $(this).data("item");
					}).get();

					$.each(data, function(index, val) 
					{
						if (val == 1)
						{
							setTimeout(function() 
							{
								$(".cg_"+val).css("background-color", "#fff");
							}, 500);

							$(".cg_"+val).css("transition", "all 0.65s ease-in");
						}								
					});

					$(".notification-modal").animate({ scrollBottom: $(".notification-modal")[0].scrollHeight}, "slow");
				});

			}, 500);
		}

		chatupdatehandleProfile = setInterval(function() 
		{
			modal_notif();
		}, 3500);
	});

	function modal_notif()
	{
		$(".notification-modal").load($baseurl+"notifications/modal_notifications", function()
		{
			var data = $(".list-group-modal").map(function() 
			{
				return $(this).data("item");
			}).get();

			$.each(data, function(index, val) 
			{
				if (val == 1)
				{
					setTimeout(function() 
					{
						$(".cg_"+val).css("background-color", "#fff");
					}, 500);

					$(".cg_"+val).css("transition", "all 0.65s ease-in");
				}								
			});
		});
	}

	setInterval(function() 
	{
		$("#DropDown_Notification").load(location.href+" #DropDown_Notification>*",""); 
	}, 3000);

});