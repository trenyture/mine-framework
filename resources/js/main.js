/*GENERAL SCRIPT*/
jQuery(function($){
	if($('meta[charset="toastr-alert"]').length > 0){
		var alert = $('meta[charset="toastr-alert"]'),
			type = alert.data('typealert') || 'info',
			title = alert.data('titlealert') || '',
			content = alert.data('contentalert') || '',
			timer = parseInt(alert.data('timeralert')) || 3000;
		
		$.toast({
			heading: title,
			text: content,
			icon: type,
			hideAfter: timer,
			position: 'top-right',
			showHideTransition: 'slide'
		});
		$('meta[charset="toastr-alert"]').remove();
	}

});