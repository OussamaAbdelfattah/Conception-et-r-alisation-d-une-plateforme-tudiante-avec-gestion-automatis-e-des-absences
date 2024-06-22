	
$(window).on('load', function(e){
	
		e.preventDefault();
		
		var photo_utilisateur = $("#photo_utilisateur");
		var photo_utilisateur_mini = $("#photo_utilisateur_mini");
		
		var requete = $.ajax({
            url:          'model/gestiEtudiants.php?func=etudiantPhoto',
            cache:        false,
            data:         '',
            dataType:     'json',
            contentType:  'application/json; charset=utf-8',
            type:         'GET'
        });
		
		requete.done(function(output){
			
			if(output.result == 'success'){
				$.each(output.data, function(i, value) { 
                   // document.getElementById('photo_utilisateur').innerHTML = '<img src="photos/' + value.photo +'" />';
					//document.getElementById('photo_utilisateur_mini').innerHTML = '<img src="photos/' + value.photo +'" />';
					photo_utilisateur.html('<img src="photos/' + value.photo +'" />');
					photo_utilisateur_mini.html('<img src="photos/' + value.photo +'" />');

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
	
	//charger liste deroulante formation

$(window).on('load', function(e){	
	    
		var formation = $('#formation'); 
		
        var requete = $.ajax({
            url:          'model/gestiUtilisateurs.php?func=listeFormation',
            cache:        false,
            dataType:     'json',
            data:          '',
            contentType:  'application/json; charset=utf-8',
            type:         'get'
        });
        requete.done(function(output){
            if (output.result == 'success'){
				
				formation.empty();  
				formation.append('<option value=""></option>');

                $.each(output.data, function(i, value) { // pour chaque noeud JSON
                    // on ajoute l option dans la liste 
				    
					formation.append('<option value="'+ value.formation_id  +'">'+ value.formation_intitule +'</option>');

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
	
	
	//charger liste deroulante filiere

$(document).on('change', '#formation', function(e){	
	    
		var formation_id = $('#formation').val();
		var filiere = $('#filiere');
		var etudiant_niveau = $('#etudiant_niveau');
			
		var requete = $.ajax({
            url:          'model/gestiUtilisateurs.php?func=listeFiliere&formation_id=' + formation_id,
            cache:        false,
            dataType:     'json',
            data:          '',
            contentType:  'application/json; charset=utf-8',
            type:         'get'
        });
        requete.done(function(output){
            if (output.result == 'success'){
				
				etudiant_niveau.empty(); 
				filiere.empty(); 
				filiere.append('<option value=""></option>');

                $.each(output.data, function(i, value) { // pour chaque noeud JSON
                    // on ajoute l option dans la liste 
				   
               filiere.append('<option value="'+ value.filiere_id  +'">'+ value.filiere_intitule +'</option>');

                });

            } else {
                //hide_loading_message();

                show_message("Échec de la demande d'information", " error");
            }
        });
        requete.fail(function(jqXHR, textStatus){
            //hide_loading_message();

            show_message("Échec de la demande d'information: " + textStatus, 'error');
        });
    });
	
	//charger liste deroulante niveau

$(document).on('change', '#filiere', function(e){	 
	    
		var filiere_id = $('#filiere').val();
		var etudiant_niveau = $('#etudiant_niveau');
			
		if(filiere_id == '' ){  
            etudiant_niveau.empty();
			return false;
		}

		$('#etudiant_niveau_groupe').show();		

        var requete = $.ajax({
            url:          'model/gestiUtilisateurs.php?func=listeNiveaux&filiere_id=' + filiere_id,
            cache:        false,
            dataType:     'json',
            data:          '',
            contentType:  'application/json; charset=utf-8',
            type:         'get'
        });
        requete.done(function(output){
            if (output.result == 'success'){
				
				etudiant_niveau.empty(); 
				etudiant_niveau.append('<option value=""></option>');
 
                $.each(output.data, function(i, value) { // pour chaque noeud JSON
                    // on ajoute l option dans la liste 
				   
               etudiant_niveau.append('<option value="'+ value.niveau_id  +'">'+ value.niveau_intitule +'</option>');

                });

            } else {
                //hide_loading_message();

                show_message("Échec de la demande d'information", " error");
            }
        });
        requete.fail(function(jqXHR, textStatus){
            //hide_loading_message();

            show_message("Échec de la demande d'information: " + textStatus, 'error');
        });
    });

$(document).on('click', '#rechercherUtilisateur', function(e){
 e.preventDefault();
	var data = $('#formListeUtilisateur').serialize();
	//alert('model/gestiUtilisateurs.php?func=listeUtilisateur&'+ data);
	
var table_liste_utilisateur = $('#table_liste_utilisateur').DataTable({ 
   
 	   "ajax": 'model/gestiUtilisateurs.php?func=listeUtilisateur&'+ data,
           "destroy": true,
		
	    "columns": [
            { "data": "utilisateur_nom_prenom" },
			{ "data": "utilisateur_login" },
            { "data": "utilisateur_etat" },
            { "data": "activer_desactiver_user" },
			{ "data": "reinitialiser_password" }
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
     },
  });
});

/////////// fonction activer désactiver utilisateur /////////////

  function activerDesactiverUtilisateur(id,etat){
	
  var table_liste_utilisateur = $('#table_liste_utilisateur').DataTable();
  
  var utilisateur_id = id;

  var etat_utilisateur  = etat;
 
		$.ajax({
				type: "POST",
				url:  "model/gestiUtilisateurs.php?func=activerDesactiverUtilisateur",
				data: "utilisateur_id=" + utilisateur_id + "&etat_utilisateur=" + etat_utilisateur,
				
				success: function( msg ){
				
					if( msg == 1 ){
						
						table_liste_utilisateur.ajax.reload();		 				
						show_message("Opération terminé avec succès.", 'success');	
					
					}
					
					else{
						
						 show_message("Échec de l'Opération. ", " error");
						
					}
				
				}
				
			});
	
	}
	
/////////// fonction activer désactiver utilisateur /////////////

  function reinitialiserPWD(id){
	
		var table_liste_utilisateur = $('#table_liste_utilisateur').DataTable();
  
		var utilisateur_id = id;
		
//alert("model/gestiUtilisateurs.php?func=reinitialiserPWD&utilisateur_id=" + utilisateur_id);
 
		$.ajax({
				type: "POST",
				url:  "model/gestiUtilisateurs.php?func=reinitialiserPWD",
				data: "utilisateur_id=" + utilisateur_id,
				
				success: function( msg ){
				
					if( msg == 1 ){
						
						table_liste_utilisateur.ajax.reload();		 				
						show_message("Opération terminé avec succès.", 'success');
					}
					 
					else{
						
						table_liste_utilisateur.ajax.reload();		 				
						show_message("Opération terminé avec succès.", 'success');
						
					}
				
				}
				
			});
	
	}	
///////////////////////////////////////////////////  
