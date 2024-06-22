<?php
    session_start();
    
    $nomComplet = $_SESSION['nomComplet'];
    $src_image = '';
    $abs = "";
    $lignesHTML = '';
    $absJ = '';
    $absNJ = '';

    function calculerTotalHeures($nombreSeances) {
        // Durée de chaque séance en heures
        $dureeSeanceHeures = 1.5; // 1h30 en heures
    
        // Calcul du total des heures
        $totalHeures = $nombreSeances * $dureeSeanceHeures;
    
        // Conversion du total en heures, minutes et secondes
        $heures = floor($totalHeures);
        $minutes = ($totalHeures - $heures) * 60;
        $secondes = ($minutes - floor($minutes)) * 60;
        $minutes = floor($minutes);
        $secondes = round($secondes);
    
        // Formatage du résultat
        $totalFormate = sprintf("%02d:%02d:%02d", $heures, $minutes, $secondes);
    
        // Retourner le total formaté
        return $totalFormate;
    }

    if ($_SESSION['img'] != null) {
        $src_image .= $_SESSION['img'];
    } else {
        $src_image .= 'photos/0.png';
    }
    
    if ($_SESSION['md'] == null){
        $Ref .= $_SESSION['promo'];    
    }else{
        $Ref .= $_SESSION['promo'] . '-' . $_SESSION['md'];
    }

    if(isset($_POST['deconnexion'])){
        session_destroy();
        header("Location: Authentification.php");
    }
    if(isset($_SESSION['email']) && isset($_SESSION['pwd'])){
        $mail = $_SESSION['email'];
        $pwd = $_SESSION['pwd'];
        include_once "php/conn.php";
        // Requête de sélection
        $sql1 = "SELECT COUNT(ps.id_presence) as count
                FROM Users u, Personne p, Presence ps, Seance sc 
                WHERE u.mail='$mail' 
                AND u.pwd='$pwd' 
                AND u.id_personne=p.id_personne 
                AND p.id_personne=ps.id_personne 
                AND ps.est_present=0 
                AND ps.id_seance=sc.id_seance; ";
        // Exécution de la requête
        $result1 = $conn->query($sql1);
        // Vérification des erreurs d'exécution de la requête 
        if (!$result1) {
            die('Erreur d\'exécution de la requête : ' . $conn->error);
        }
        $row1 = $result1->fetch_assoc();
        if(empty($row1)){
            echo "<script>alert('donnees incorrectes!!');</script>"; 
        }else{
            $abs = calculerTotalHeures($row1['count']);
        }
        //deuxieme requete 
        $sql2 = "SELECT sc.type_seance, Mt.nom as nomMatiere, ps.date_presence, sc.heure_debut, sc.heure_fin, ps.justifiee as justifBool
                    FROM Users u, Personne p, Presence ps, Seance sc, Matiere Mt
                    WHERE u.mail='$mail'
                    AND u.pwd='$pwd'
                    AND u.id_personne=p.id_personne
                    AND p.id_personne=ps.id_personne
                    AND ps.est_present=0
                    AND ps.id_seance=sc.id_seance
                    AND sc.id_matiere=Mt.id_matiere
                    ";
        // Exécution de la requête
        $result2 = $conn->query($sql2);
        // Vérification des erreurs d'exécution de la requête 
        if (!$result2) {
            die('Erreur d\'exécution de la requête : ' . $conn->error);
        }
        $nombreSeances = 0;
        while ($row2 = $result2->fetch_assoc()){
            $type = $row2['type_seance'];
            $matieres = $row2['nomMatiere'];
            $dates = $row2['date_presence'];
            $heureD = $row2['heure_debut'];
            $heureF = $row2['heure_fin'];
            
            if($row2['justifBool'] == '1'){
                $justifiee = $row2['justifBool'];
                $nombreSeances += 1;
                $lignesHTML .= "<tr>
                                <td>$type</td>
                                <td>$matieres</td>
                                <td>$dates</td>
                                <td>Justifié(e)</td>
                                <td>$heureD</td>
                                <td>$heureF</td>
                                <td>01:30:00</td>
                            </tr>";    
            }else{
                $lignesHTML .= "<tr>
                                <td>$type</td>
                                <td>$matieres</td>
                                <td>$dates</td>
                                <td>***</td>
                                <td>$heureD</td>
                                <td>$heureF</td>
                                <td>01:30:00</td>
                            </tr>";
            }
        }
        $absJ = calculerTotalHeures($nombreSeances);
        $absNJ = calculerTotalHeures(($row1['count']-$nombreSeances));
        include_once "php/disconn.php";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- META SECTION -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>ISGA DIRECT - ISGA Campus Rabat</title>
        <script src="https://kit.fontawesome.com/45e38e596f.js"
            crossorigin="anonymous"></script>

        <link rel="icon" href="photos/favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->

        <!-- CSS INCLUDE -->

        <link rel="stylesheet" type="text/css" id="theme"
            href="css/theme-default.css" />
        <link rel="stylesheet" type="text/css" id="theme"
            href="css/bootstrap/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" id="theme"
            href="css/layout.css" />
        <link rel="stylesheet" type="text/css" id="theme" href="css/dark.css" />
        <link rel="stylesheet" type="text/css" id="theme"
            href='css/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
        <link rel="stylesheet" type="text/css" id="theme"
            href='css/fullcalendar/fullcalendar.print.min.css' rel='stylesheet'
            media='print' />
        <link rel='stylesheet' type="text/css" id="theme"
            href='css/jquery-ui.css' />

        <!-- EOF CSS INCLUDE -->

        <!-- START PRELOADS -->
        <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->

        <!-- START SCRIPTS -->
        <!-- START PLUGINS -->

        <script type="text/javascript"
            src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript"
            src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <script src='js/plugins/fullcalendar/moment.js'></script>
        <script type="text/javascript"
            src="js/jquery.twbsPagination.js"></script>
        <script src='js/plugins/fullcalendar/fullcalendar.min.js'></script>
        <script type='text/javascript'
            src='js/plugins/fullcalendar/gcal.js'></script>

        <script type="text/javascript" src="js/jquery.redirect.js"></script>
        <script type="text/javascript"
            src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script src='js/plugins/fullcalendar/locale/fr-ch.js'></script>
        <script type="text/javascript"
            src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
        <script type="text/javascript"
            src="js/jquery.upload-1.0.2.min.js"></script>
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->
        <script type='text/javascript'
            src="js/plugins/icheck/icheck.min.js"></script>
        <script type="text/javascript"
            src="js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
        <script type="text/javascript"
            src="js/plugins/dropzone/dropzone.min.js"></script>
        <script type="text/javascript"
            src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript"
            src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

        <script type="text/javascript"
            src="js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript"
            src="js/plugins/morris/morris.min.js"></script>
        <script type="text/javascript"
            src="js/plugins/rickshaw/d3.v3.js"></script>
        <script type="text/javascript"
            src="js/plugins/rickshaw/rickshaw.min.js"></script>
        <script type='text/javascript'
            src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script type='text/javascript'
            src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script type='text/javascript'
            src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>
        <script type="text/javascript"
            src="js/plugins/owl/owl.carousel.min.js"></script>

        <script type="text/javascript" src="js/plugins/moment.min.js"></script>
        <script type="text/javascript"
            src="js/plugins/daterangepicker/daterangepicker.js"></script>

        <script charset="utf-8" src="js/jquery.dataTables.js"></script>
        <script charset="utf-8" src="js/jquery.dataTables.min.js"></script>

        <link rel="stylesheet" type="text/css"
            href="css/dataTables.checkboxes.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css" />

        <!-- END THIS PAGE PLUGINS-->

        <!-- START TEMPLATE -->
        <script type="text/javascript"
            src="js/plugins/knob/jquery.knob.min.js"></script>
        <script type="text/javascript" src="js/settings.js"></script>
        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>
        <script type="text/javascript" src="js/demo_dashboard.js"></script>

    </head>
    <body>

        <!-- debut code Ahmed -->

        <div id="loading_layer" style="display:none"><img
                src="img/ajax_loader.gif" alt /></div>

        <div class="page-container">

            <script language="javascript" type="text/javascript">

   function chargerPage( page ){
		$.ajax({
		  type: "POST",
		  url: "model/gestiUtilisateurs.php?func=page",
		  data: "page=" + page,
		  
		  success: function( msg ){
			
		    if( msg == 1 ){
				$.redirect("",
                    {
                        page : page
                    },
                    "POST");
		    }
			else{
			 return false;
		    }
			
		  }
		  
		});
		
	}

</script>

            <div
                class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide sidebar">

                <ul class="x-navigation">

                    <li class="xn-logo">
                        <a href="<?php echo "Acceuil.php";?>">ISGA
                            DIRECT</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>
                    <li class="xn-profile">
                        <a href="#" class="profile-mini">
                            <div class="profile-image"><img
                                    src="<?php echo $src_image;?>" />
                            </div>
                        </a>
                        <div class="profile">
                            <div class="profile-image"><img
                                    src="<?php echo $src_image;?>" />
                            </div>
                            <div class="profile-data">
                                <div class="profile-data-name"><?php echo $nomComplet;?></div>

                            </div>

                            <div class="profile-data">
                                <div class="profile-data-name"><?php echo $Ref;?></div>

                            </div>

                            <div class="profile-controls">

                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="<?php echo "Acceuil.php";?>"><span
                                class="xn-title fa fa-desktop"></span> <span
                                class="xn-text">Dashboard</span></a> </li>

                    <!-- menu etudiant -->

                    <!-- <li><a
                            href="javascript:chargerPage( 'offreStageEmploi')"><span
                                class="xn-title fa fa-bullhorn"></span><span
                                class="xn-text">Recrutement</span></a></li> -->
                    <li><a
                            href="<?php echo "retards.php";?>"><span
                                class="xn-title fa fa-clock-o"></span><span
                                class="xn-text">Relevé des
                                retards</span></a></li>
                    <li><a
                            href="<?php echo "abs.php";?>"><span
                                class="xn-title fa fa-user"></span><span
                                class="xn-text">Relevé des
                                absences</span></a></li>
                    <!-- javascript:chargerPage( 'releveNotesEtudiant') -->
                    <li><a
                            href="<?php echo "notes.php";?>"><span
                                class="xn-titlefa fa fa-edit"></span><span
                                class="xn-text">Relevé des notes</span></a></li>

                    <!-- <li><a
                            href="javascript:chargerPage( 'relevePaiemetsEtudiant')"><span
                                class="xn-title fa fa-dollar"></span><span
                                class="xn-text">Relevé des
                                paiements</span></a></li> -->

                    <!--
<li><a href="javascript:chargerPage( 'devoirArendre')"><span class="xn-title fa fa-edit"></span><span class="xn-text"></span><div id="nombreNouveauDevoirParent"></div></a></li>
<li><a href="javascript:chargerPage( 'HistoriqueDevoirEleve')"><span class="xn-title fa fa-folder-o"></span><span class="xn-text"></span></a></li>
 -->
                    <!-- <li><a href="javascript:chargerPage( 'listeLivres')"><span
                                class="fa fa-book"></span><span
                                class="xn-text">Bibliothèque</span></a></li>
                    <li><a href="javascript:chargerPage( 'agenda')"><span
                                class="xn-title fa fa-calendar"></span><span
                                class="xn-text">Agenda scolaire</span></a></li>
                    <li><a href="javascript:chargerPage( 'emploi')"><span
                                class="xn-title fa fa-calendar-o"></span><span
                                class="xn-text">Emploi du temps</span></a></li>
                    <li><a
                            href="javascript:chargerPage( 'demandeDocuments')"><span
                                class="xn-title fa fa-files-o"></span><span
                                class="xn-text">Demande des
                                documents</span></a></li> -->

                    <!-- fin menu etudiant et debut menu admin -->

                    <li><a
                            href="<?php echo "mdps.php";?>"><span
                                class="xn-title fa fa-lock"></span><span
                                class="xn-text">Changement mot de
                                passe</span></a></li>

                </ul>

            </div>

            <script language="javascript" type="text/javascript">

   function deconnexion(){

		$.ajax({
		  type: "POST",
		  url: "model/gestiUtilisateurs.php?func=deconnexion",
		 // data: "page=" + page,
		  
		  success: function( msg ){
			
		    if( msg == 1 ){
				$.redirect("",
                    {
                    },
                    "POST");
		    }
			else{
			 return false;
		    }
			
		  }
		  
		});
		
	}

</script>

            <head>
                <!-- META SECTION -->
                <title>ISGA DIRECT</title>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport"
                    content="width=device-width, initial-scale=1" />

                <link rel="icon" href="photos/favicon.ico"
                    type="image/x-icon" />
                <!-- END META SECTION -->

                <!-- CSS INCLUDE -->
                <link rel="stylesheet" type="text/css" id="theme"
                    href="css/theme-default.css" />
                <!-- EOF CSS INCLUDE -->

                <html lang="en">
                    <body>
                        <div class="page-container">
                            <div class="page-content">
                                <ul
                                    class="x-navigation x-navigation-horizontal x-navigation-panel">
                                    <li class="xn-icon-button">
                                        <a href="#"
                                            class="x-navigation-minimize"><span
                                                class="fa fa-dedent"></span></a>
                                    </li>
                                    <li class="xn-icon-button pull-right">
                                        <a href="#" class="mb-control"
                                            data-box="#mb-signout"><span
                                                class="fa fa-sign-out"></span></a>
                                    </li>
                                </ul>
                            </head>

                            <div class="message-box animated fadeIn"
                                data-sound="alert" id="mb-signout">
                                <div class="mb-container">
                                    <div class="mb-middle">
                                        <div class="mb-title"><span
                                                class="fa fa-sign-out"></span><strong>Se
                                                Déconnecter</strong> ?</div>
                                        <div class="mb-content">
                                            <p>êtes vous sûr de vouloir quitter
                                                ?</p>
                                            <p>Cliquez sur non si vous voulez
                                                continue, oui pour se
                                                déconncter</p>
                                        </div>
                                        <div class="mb-footer">
                                            <div class="pull-right">
                                                <form method="post">
                                                    <button type="submit" name="deconnexion" class="btn btn-success btn-lg">OUI</button>
                                                </form>
                                                <button
                                                    class="btn btn-default btn-lg mb-control-close">NON</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul class="breadcrumb push-down-0">
                                <li><a href="<?php echo "Acceuil.php";?>">Acceuil</a></li>
                                <li class="active">Relevé des absences</li>
                            </ul>

                            <div class="content-frame">

                                <div class="content-frame-top">
                                    <div class="page-title">
                                        <h2><span></span>Relevé des
                                            absences</h2>
                                    </div> 
                                </div>

                                <div>

                                    <div class="col-md-12"
                                        id="absence_etudiant">
                                            <div id="Seances_de_r">
                                                <p><FONT color= "red">Liste des séances de  </FONT> : <b><?php echo $nomComplet;?></b></p>
                                            </div>
                                            <div id="Justifies_r">
                                                <p>Justifiés : <?php echo $absJ;?></p>
                                            </div>
                                            <div id="Non_justifies_r">
                                                <p>Non justifiés : <?php echo $absNJ;?></p>
                                            </div>
                                            <div id="Total_r">
                                                <p>Total : <?php echo $abs;?></p>
                                            </div>
                                    </div>

                                    <div class="panel panel-default">

                                        <div class="panel-heading">
                                            <h3 class="panel-title">Liste des
                                                séances absentées</h3>
                                            <ul class="panel-controls">
                                                <li><a href="#"
                                                        class="panel-collapse"><span
                                                            class="fa fa-angle-down"></span></a></li>
                                                <li><a href="#"
                                                        class="panel-refresh"><span
                                                            class="fa fa-refresh"></span></a></li>
                                            </ul>
                                        </div>

                                        <div
                                            class="panel-body table-responsive">
                                            <table 
                                                class="table datatable table-striped jambo_table bulk_action no-footer dataTable" 
                                                id="table_retards_etudiant" 
                                                role="grid" 
                                                aria-describedby="table_retards_etudiant_info" 
                                                style="width: 1433px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th 
                                                            class="sorting_asc" 
                                                            tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1"
                                                            colspan="1"
                                                            aria-label="Type de la séance: activate to sort column descending" 
                                                            style="width: 297px;" aria-sort="ascending">Type de la
                                                            séance 
                                                        </th>
                                                        <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Matières: activate to sort column ascending" 
                                                            style="width: 160px;">Matières
                                                        </th>
                                                        <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Date: activate to sort column ascending" 
                                                            style="width: 101px;">Date
                                                        </th>
                                                        <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Justification: activate to sort column ascending" 
                                                            style="width: 216px;">Justification
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="Heure début: activate to sort column ascending" style="width: 215px;">
                                                            Heure début</th>
                                                        <th class="sorting" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="Heure fin: activate to sort column ascending" style="width: 169px;">
                                                            Heure fin</th>
                                                        <th class="sorting_disabled" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="La durée" style="width: 163px;">
                                                            La durée</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                                                       
                                                    <?php echo $lignesHTML;?>
                                                </tbody> 
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <script charset="utf-8"
                        src="js/releveAbsencesEtudiant.js"></script>

                </body>
            </html>

            <!-- main content -->
            <!-- main content -->

            <!-- sidebar -->
            <!-- sidebar -->
            <!-- datatable -->
              <script src="../include/gespa/lib/datatables/jquery.dataTables.min.js"></script>
             <script src="../include/gespa/lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
            <!-- datatable functions -->
              <script src="../include/gespa/js/gebo_datatables.js"></script>

            <script type='text/javascript' src='../include/gespa/js/js/plugins/jquery-validation/jquery.validate.js'></script>
            <script type='text/javascript'
                src="js/plugins/validationengine/languages/jquery.validationEngine-en.js"></script>
            <script type='text/javascript'
                src="js/plugins/validationengine/jquery.validationEngine.js"></script>
            <script charset="utf-8" src="js/relevePaiemetsEtudiant.js"></script>
            <script charset="utf-8" src="js/infoUtilisateur.js"></script>

            <div id="message_container">
                <div id="message" class="success">
                    <p>This is a success message.</p>
                </div>
            </div>
            <div id="loading_container">
                <div id="loading_container2">
                    <div id="loading_container3">
                        <div id="loading_container4">
                            Loading, please wait...
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
$(document).contextmenu(function () {
return false;

});
</script>
        </body>
    </html>
