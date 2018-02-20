jQuery(function($){

	/**
	 * Envoi du formulaire d'édition d'utilisateur
	 */
	$('form#edit-user-form').submit(function(event){
		event.preventDefault();
		if ($(this).find('ul#errorMessage').length > 0){ 
			$(this).find('ul#errorMessage').remove();
		}

		var datas = formToJSON(this),
			method = $(this).attr('method'),
			error = false,
			messageError = '',
			regExMail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			button = $(this).find('button[type="submit"]');

		// On valide le formulaire 
		if (datas.prenom == '') {
			error = true;
			messageError += '<li>Vous devez renseigner un prenom</li>';
		}
		if (datas.nom == '') {
			error = true;
			messageError += '<li>Vous devez renseigner un nom</li>';
		}
		if (datas.email == '') {
			error = true;
			messageError += '<li>Vous devez renseigner un email</li>';
		} else if (!regExMail.test(datas.email)) {
			error = true;
			messageError += "<li>L'email n'est pas valide</li>";
		}
		if(method === "POST"){
			if (datas['mot-passe'] == '') {
				error = true;
				messageError += '<li>Vous devez renseigner un mot de passe</li>';
			} else if (datas['mot-passe'] != datas['mot-passe-verif']) {
				error = true;
				messageError += '<li>Les mots de passe ne correspondent pas!</li>';
			}
		}

		// S'il y a erreur alors on n'envoit pas le form et on affiche les messages d'erreurs
		if (error !== false) {
			button.before('<ul id="errorMessage">' + messageError + '</ul>');
		} else {
			// Sinon on envoit le formulaire en ajax
			$.ajax({
				url: window.location.href,
				type: method,
				data: datas,
				dataType: 'JSON'
			}).done(function(data){
				if (data === true) {
					swal({
						title: 'Sauvegardé!',
						text: "Les informations ont bien été sauvegardé",
						type: 'success',
						timer: 1500,
						showConfirmButton: false
					}).then(function() {
						window.location.href = '/users';
					});
				} else {
					if (data === false) {
						swal({
							title: 'Oups...',
							text: 'Il y a eu un problème lors de la sauvegarde dans la base de donnée',
							type: 'error',
							timer: 1500,
							showConfirmButton: false
						});
					} else {
						messageError = '';
						$.each(data, function(index, value) {
							messageError += '<li>' + value + '</li>';
						});
						button.before('<ul id="errorMessage">' + messageError + '</ul>');
					}
				}
			});
		}

		return false;
	});

});