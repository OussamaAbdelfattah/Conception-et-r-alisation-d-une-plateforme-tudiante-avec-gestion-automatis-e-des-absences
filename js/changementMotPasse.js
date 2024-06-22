	
function show_message(message_text, message_type){
		
        $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
        $('#message_container').show();
        if (typeof timeout_message !== 'undefined'){
            window.clearTimeout(timeout_message);
        }
        timeout_message = setTimeout(function(){
            hide_message();
        }, 1000);
    }
	
	function hide_message(){
        $('#message').html('').attr('class', '');
        $('#message_container').hide();
    }

    // Show loading message
    function show_loading_message(){
        $('#loading_container').show();
    }
    // Hide loading message
    function hide_loading_message(){
        $('#loading_container').hide();
    }

$(document).on('click', '#Retablir', function(e){

    $('#PasswordA').val('');
	$('#PasswordC').val('');

});

$(document).on('click', '#add', function(e){


	var mdp = $('#PasswordA').val();
	var confirmMdp = $('#PasswordC').val();

	if( mdp.length < 4 || confirmMdp.length < 4 ){
		show_message( 'Mot de passe trop court','error' );
			// alert( 'Mot de passe trop court' );
			return false;
		}
		
	if( mdp != confirmMdp ){
		show_message( 'les 2 mots de passe ne sont pas identiques','error' );
			//alert( 'les 2 mots de passe ne sont pas identiques' );
			return false;
	}
		
	var requete   = $.ajax({
                url:          'model/gestiUtilisateurs.php?func=modifierUtilisateurPassword',
                cache:        false,
                data:         'password=' + mdp,
                dataType:     'json',
                contentType:  'application/json; charset=utf-8',
                type:         'GET'
            });

            requete.done(function(output){
                //  alert(output);
                if (output.result == 'success'){
                    
                    // Reload datable
					show_message( 'votre mot de passe a été changé avec succès','success' );
					$('#PasswordA').val('');
					$('#PasswordC').val('');
                    
                }
                else {
                    //           hide_loading_message();

                    show_message("Échec de la demande changement mot de passe !", 'error');
                }
            });
            requete.fail(function(jqXHR, textStatus){
                //     hide_loading_message();

                show_message("Échec de la demande changement mot de passe: " + textStatus, 'error');
            });
	

});
     

	
///////////////////////////////////////////////////  
