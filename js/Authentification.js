$(document).ready(function(){

    var form_login = $("#login_form").validate({

        ignore: [],
        rules: {
            username: {required: true, minlength: 3},
            password: {required: true, minlength: 3}
        },
        messages: {
            username: "Le champ identifiant est obligatoire !",
            password: "Le champ mot de passe est obligatoire !"
        }

    });
       
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

		
    $(document).on('click', '#connecter', function(e){
        e.preventDefault();

        // Validate form
        //if ($('#login_form').valid() == true){

            // Send company information to database
            // hide_ipad_keyboard();
            //hide_lightbox();
            //  show_loading_message();
            var data = $('#login_form').serialize();

            var requete   = $.ajax({
                url:          'model/gestiUtilisateurs.php?func=authentification',
                cache:        false,
                data:         data,
                dataType:     'json',
                contentType:  'application/json; charset=utf-8',
                type:         'GET'
            });

            requete.done(function(output){

                //  alert(output);
                if (output.result == 'success'){                 
                      show_message("la connexion a été établie avec succès.", 'success');
                       // $.redirect("./");
                  //  window.location = 'view/dashboard.php';     
					$.redirect("",
                        {
                            page : "dashboard"
                        },
                        "POST");
                }
                else if (output.result == 'attempt'){
                    show_message("Nombre de tentative expiré !", 'error');
                    window.location.replace("https://www.isga.ma");
                }
                else {
                  
                    show_message("Login/password : incorrect", 'error');
                }
            });
            requete.fail(function(jqXHR, textStatus){

                show_message("Échec de la connexion: " + textStatus, 'error');
            });

        //}
    });



});
