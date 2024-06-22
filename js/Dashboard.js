   
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
		e.preventDefault();
		
		var Montant_paye = $("#Montant_paye");
		var Montant = $("#Montant");
		var Reste_a_paye = $("#Reste_a_paye");
		
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
				$.each(output.data, function(i, value) { 
 
					Montant_paye.html('<FONT size="5"><b>' + value.Versement_total + ' DH </b></FONT>');
					Montant.html('<FONT size="5"><b>' + value.Montant + ' DH </b></FONT>');
					Reste_a_paye.html('<FONT size="5"><b>' + value.Reste + ' DH </b></FONT>');
				
				});
				show_message("Les informations ont été chargé avec succès", " success");
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
	
	
	$(window).on('load', function(e){
		e.preventDefault();
		
		var Total_absence1 = $("#Total_absence1");
		
		var requete = $.ajax({
            url:          'model/gestiEtdAbsencesRetards.php?func=listeEtudiantsAbsences',
            cache:        false,
            dataType:     'json',
            data:         '',
            contentType:  'application/json; charset=utf-8',
            type:         'GET'
        });
		
		requete.done(function(output){
			
			if(output.result == 'success'){
				$.each(output.data, function(i, value) { 
					Total_absence1.html('<FONT color="RED" size="4">' + value.total_absences + '</FONT>');
				
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
	
	$(window).on('load', function(e){
		e.preventDefault();
		
		var Total_retard1 = $("#Total_retard1");
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
					Total_retard1.html('<FONT color="#FF8000" size="4">' + value.total_retards + '</FONT>');
				
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
