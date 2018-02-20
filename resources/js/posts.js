jQuery(function($) {

	$('a#deletePost').click(function() {
		var postId = $(this).data('postid');
		swal({
			title: 'Attention',
			text: "Êtes vous sûr de vouloir supprimer le Post?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Non',
			confirmButtonText: 'Oui',
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			buttonsStyling: false,
			reverseButtons: false
		}).then(function(data) {
			if (data.value) {
				$.ajax({
					url: '/posts/delete/' + postId,
					type: 'DELETE',
					dataType: 'JSON',
					success: function(result) {
						if (result === true) {
							swal({
								title: 'Supprimé!',
								text: 'Le Post a bien été supprimé',
								type: 'success',
								timer: 1500,
								showConfirmButton: false,
							}).then(function() {
								window.location.href = '/posts';
							});
						} else {
							swal({
								title: 'Oups...',
								text: 'Il y a eu un problème lors de la suppression dans la base de donnée',
								type: 'error',
								timer: 1500,
								showConfirmButton: false
							});
						}
					}
				});
				swal(
					'Supprimé!',
					'Your file has been deleted.',
					'success'
				);
				// result.dismiss can be 'cancel', 'overlay',
				// 'close', and 'timer'
			} else if (data.dismiss === 'cancel') {
				swal(
					'Annulé',
					'Your imaginary file is safe :)',
					'error'
				);
			}
		});
		event.preventDefault();
		return false;
	});

	$('form#PostCreateForm').submit(function(event) {
		if ($(this).find('ul#errorMessage').length > 0)
			$(this).find('ul#errorMessage').remove();
		var datas = formToJSON(this),
			error = false,
			messageError = '',
			button = $(this).find('button[type="submit"]');
		if (datas.titleField.length < 1) {
			error = true;
			messageError += '<li>Vous devez rentrer un titre</li>';
		}
		if (datas.contentField.length < 1) {
			error = true;
			messageError += '<li>Vous devez écrire du contenu</li>';
		}
		if (error === false) {
			$.ajax({
				url: window.location.href,
				type: 'POST',
				data: datas,
				dataType: 'JSON',
				success: function(result) {
					if (result === true) {
						swal({
							title: 'Sauvegardé!',
							text: 'Le Post a bien été créé',
							type: 'success',
							timer: 1500,
							showConfirmButton: false
						}).then(function() {
							window.location.href = '/posts';
						});
					} else {
						if (result === false) {
							swal({
								title: 'Oups...',
								text: 'Il y a eu un problème lors de la sauvegarde dans la base de donnée',
								type: 'error',
								timer: 1500,
								showConfirmButton: false
							});
						} else {
							messageError = '';
							$.each(result, function(index, value) {
								messageError += '<li>' + value + '</li>';
							});
							button.before('<ul id="errorMessage">' + messageError + '</ul>');
						}
					}
				}
			});
		} else {
			button.before('<ul id="errorMessage">' + messageError + '</ul>');
		}
		event.preventDefault();
		return false;
	});

	$('form#PostEditForm').submit(function(event) {
		if ($(this).find('ul#errorMessage').length > 0)
			$(this).find('ul#errorMessage').remove();
		var datas = formToJSON(this),
			error = false,
			messageError = '',
			button = $(this).find('button[type="submit"]');
		if (datas.titleField.length < 1) {
			error = true;
			messageError += '<li>Vous devez rentrer un titre</li>';
		}
		if (datas.contentField.length < 1) {
			error = true;
			messageError += '<li>Vous devez écrire du contenu</li>';
		}
		if (error === false) {
			$.ajax({
				url: window.location.href,
				type: 'PATCH',
				data: datas,
				dataType: 'JSON',
				success: function(result) {
					if (result === true) {
						swal({
							title: 'Sauvegardé!',
							text: 'Le Post a bien été édité',
							type: 'success',
							timer: 1500,
							showConfirmButton: false
						}).then(function() {
							window.location.href = '/posts';
						});
					} else {
						if (result === false) {
							swal({
								title: 'Oups...',
								text: 'Il y a eu un problème lors de la sauvegarde dans la base de donnée',
								type: 'error',
								timer: 1500,
								showConfirmButton: false
							});
						} else {
							messageError = '';
							$.each(result, function(index, value) {
								messageError += '<li>' + value + '</li>';
							});
							button.before('<ul id="errorMessage">' + messageError + '</ul>');
						}
					}
				}
			});
		} else {
			button.before('<ul id="errorMessage">' + messageError + '</ul>');
		}
		event.preventDefault();
		return false;
	});

});