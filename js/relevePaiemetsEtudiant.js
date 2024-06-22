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


$(window).on('load', function(e){

	//Afficher la liste des versements pour l'etudiant
	var table_Versements = $('#table_Versements').DataTable({
			
        "ajax": "model/gestiEtdReglements.php?func=listeVersements",
        "destroy": true,
		//"scrollX": true, 
		"columns": [
            
            { "data": "Commentaire" },
            { "data": "date" },
            { "data": "Service" },
            { "data": "Type" },
            { "data": "N_cheque" },
            { "data": "Nom_banque" },
            { "data": "Date_encaissement" },
			{ "data": "Montant" }
        ],

        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [-1] }
        ],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]],
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "oPaginate": {
            "sFirst":        "Début",
            "sPrevious":   "Préc",
            "sNext":        "Suiv",
            "sLast":        "Fin"
            },
            "sLengthMenu":    "Enregistrements par page: _MENU_",
            "sInfo":          "Total de _TOTAL_ enregistrements (affichés _START_ à _END_)",
            "sInfoFiltered":  "(filtré à partir de _MAX_ enregistrements totaux)"
        }
    });
   }); 	
   
   $(window).on('load', function(e){
		e.preventDefault();
		
		var versement_etudiant = $("#versement_etudiant");
		
		var requete = $.ajax({
            url:          'model/gestiEtdReglements.php?func=listeEtudiantsReglement',
            cache:        false,
            dataType:     'json',
            data:         '',
            contentType:  'application/json; charset=utf-8',
            type:         'GET'
        });
		
		requete.done(function(output){
			
			if(output.result == 'success'){
				show_message("Les informations ont été chargé avec succès", " success");
				$.each(output.data, function(i, value) { 
				
					versement_etudiant.html('<p><FONT color= "red">Versement de  </FONT> : ' + value.Versement_de + '</p><p>Mode de paiement : ' + value.Mode_de_paiement + '</p><p>Montant à payer: ' + value.Montant + '</p><p>Réduction : ' + value.Reduction + '</p><p>Versement total : ' + value.Versement_total + '</p><p>Reste : ' + value.Reste + '</p><p>Etat : ' + value.Etat + '</p>');

				});
				
			}
			else {
                //hide_loading_message();
                show_message("Échec de la demande d'information", " error");
            }
                
	   });	
		
		requete.fail(function(jqXHR, textStatus){
			
            //hide_loading_message();
            show_message("Échec de la demande d'information: " + textStatus, 'error');
        });
   
	});
///////////////////////////////////////////////////  
