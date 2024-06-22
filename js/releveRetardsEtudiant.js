$(window).on('load', function(e){

	//Afficher la liste des versements pour l'etudiant
	var table_retards_etudiant = $('#table_retards_etudiant').DataTable({
			
        "ajax": "model/gestiEtdAbsencesRetards.php?func=listeRetardsEtudiant",
        "destroy": true,
		//"scrollX": true,
		"columns": [

            { "data": "Seance_type" },
            { "data": "Matiere" },
            { "data": "Date" },
            { "data": "Justification" },
            { "data": "Heure_debut" },
            { "data": "Heure_fin" },
            { "data": "La_duree" }
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
		var requete = $.ajax({
            url:          'model/gestiEtdAbsencesRetards.php?func=listeEtudiantsRetards',
            cache:        false,
            dataType:     'json',
            data:         '',
            contentType:  'application/json; charset=utf-8',
            type:         'GET'
        });
		
		requete.done(function(output){
			
			if(output.result == 'success'){
				
				$.each(output.data, function(i, value) { 
				
                    document.getElementById('Seances_de_r').innerHTML = '<p><FONT color= "red">Liste des séances de  </FONT> : ' + value.Seance_de + '</p>';
					document.getElementById('Justifies_r').innerHTML = '<p>Justifiés : ' + value.total_retards_justifiees + '</p>';
					document.getElementById('Non_justifies_r').innerHTML = '<p>Non justifiés : ' + value.total_retards_n_justifiees + '</p>';
					document.getElementById('Total_r').innerHTML = '<p>Total : ' + value.total_retards + '</p>';
			
				});
			}
			else {
                // hide_loading_message();
                show_message("Échec de la demande d'information", " error");
            }
                
	   });	
		
		requete.fail(function(jqXHR, textStatus){
			
            // hide_loading_message();
            show_message("Échec de la demande d'information: " + textStatus, 'error');
        });
   
	});
	
	
	
	
///////////////////////////////////////////////////  
