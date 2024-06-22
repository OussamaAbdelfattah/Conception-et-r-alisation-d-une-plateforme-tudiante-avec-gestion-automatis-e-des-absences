<?php
    session_start();
    $nomComplet = $_SESSION['nom'];
    $Ref = '';
    $src_image = 'photos/0.png';
    $lignesHTML = '';

    include_once "php/conn.php";
        $sql1 = "SELECT carte.id_RFID, p.nom, p.prenom FROM carteRFID carte, Personne p WHERE carte.id_personne = p.id_personne;";
        $result1 = $conn->query($sql1);

        if (!$result1) {
            die('Erreur d\'exécution de la requête : ' . $conn->error);
        }
        while($row1 = $result1->fetch_assoc()){
            $id = $row1['id_RFID'];
            $nom = $row1['nom'];
            $prenom = $row1['prenom'];
            $lignesHTML .= "<tr>
                                <td>$nom</td>
                                <td>$prenom</td>
                                <td>$id</td>
                            </tr>";
        }
        if (isset($_POST['supp'])){
            $idd = $_POST['supp'];
            $sql = "DELETE FROM Module WHERE id_module=$idd";
    
            // Exécution de la requête
            $result = $conn->query($sql);
            
            if (!$result) {
                die('Erreur d\'exécution de la requête : ' . $conn->error);
            }
            header("Location: gestMod.php");
        }
    include_once "disconn.php";


    if(isset($_POST['deconnexion'])){
        session_destroy();
        header("Location: Authentification.php");
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

        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                        <a href="<?php echo "Acceuiladmin.php"; ?>">ISGA
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
                        <a href="<?php echo "Acceuiladmin.php"; ?>"><span
                                class="xn-title fa fa-desktop"></span> <span
                                class="xn-text">Dashboard</span></a> </li>
                    <!-- Sous_menu -->
                    <li>
                        <a href="<?php echo "gestEtud.php";?>" class="toggle-submenu">
                            <span class="fa fa-graduation-cap"></span> Gestion des Etudiants
                            
                        </a>
                    </li>
                    <!-- Rubrique 2 : Gestion des enseignants -->
                    <li>
                        <a href="<?php echo "gestEnsei.php";?>" class="toggle-submenu">
                            <span class="fa fa-user"></span> Gestion des enseignants
                            <span class="xn-icon-button">
                                <!-- <i class="fa fa-plus"></i> -->
                            </span>
                        </a>
                    </li>
                    <!-- Rubrique 3 : Gestion des modules -->
                    <li>
                        <a href="<?php echo "gestMod.php";?>" class="toggle-submenu">
                            <span class="fa fa-cubes"></span> Gestion des modules
                            <span class="xn-icon-button">
                                <!-- <i class="fa fa-plus"></i> -->
                            </span>
                        </a>
                        <!-- <ul style="display: block;">
                            <li><a href="#"><i class="fa fa-plus-circle"></i> Ajouter un module</a></li>
                            <li><a href="#"><i class="fa fa-pencil"></i> Modifier un module</a></li>
                            <li><a href="#"><i class="fa fa-trash"></i> Supprimer un module</a></li>
                            <li><a href="#"><i class="fa fa-calendar"></i> Planifier les modules</a></li>
                            <li><a href="#"><i class="fa fa-users"></i> Suivre les inscriptions</a></li>
                            Ajoutez d'autres sous-rubriques au besoin
                        </ul> -->
                    </li>
                    <!-- Rubrique 5 : Gestion des examens -->
                    <li>
                        <a href="<?php echo "gestExam.php";?>" class="toggle-submenu">
                            <span class="fa fa-file-text"></span> Gestion des examens
                            <span class="xn-icon-button">
                                <!-- <i class="fa fa-plus"></i> -->
                            </span>
                        </a>
                        <!-- <ul style="display: block;">
                            <li><a href="#"><i class="fa fa-calendar"></i> Planifier les examens</a></li>
                            <li><a href="#"><i class="fa fa-building"></i> Assigner des salles d'examen</a></li>
                            <li><a href="#"><i class="fa fa-pencil"></i> Enregistrer les résultats des examens</a></li>
                            Ajoutez d'autres sous-rubriques au besoin
                        </ul> -->
                    </li>
                    <!-- Rubrique 6 : Gestion des emplois du temps -->
                    <li>
                        <a href="<?php echo "gestEmploi.php";?>" class="toggle-submenu">
                            <span class="fa fa-calendar"></span> Gestion des emplois du temps
                            <span class="xn-icon-button">
                                <!-- <i class="fa fa-plus"></i> -->
                            </span>
                        </a>
                        <!-- <ul style="display: block;">
                            <li><a href="#"><i class="fa fa-calendar-plus-o"></i> Gérer les emplois du temps des matières</a></li>
                            Ajoutez d'autres sous-rubriques au besoin
                        </ul> -->
                    </li>
                    <!-- Rubrique 7 : Gestion des ressources -->
                    <li>
                        <a href="#" class="toggle-submenu">
                            <span class="fa fa-cogs"></span> Gestion des ressources
                            <span class="xn-icon-button">
                                <!-- <i class="fa fa-plus"></i> -->
                            </span>
                        </a>
                        <ul style="display: block;">
                            <li><a href="<?php echo "gestSalle.php"; ?>"><i class="fa fa-university"></i> Gérer les salles de classe et les laboratoires</a></li>
                            <!-- Sous-rubrique RFID -->
                            <li>
                                <a href="#" class="toggle-submenu">
                                    <span class="fa fa-id-badge"></span> RFID
                                    <span class="xn-icon-button">
                                        <!-- <i class="fa fa-plus"></i> -->
                                    </span>
                            </a>
                                <ul style="display: block;">
                                    <li><a href="<?php echo "gestRFID.php"?>"><i class="fa fa-list"></i> Listes</a></li>
                                    <li><a href="#"><i class="fa fa-eye"></i> Lire</a></li>
                                    <li><a href="#"><i class="fa fa-refresh"></i> Réinitialiser</a></li>
                                    <li><a href="#"><i class="fa fa-hand-pointer-o"></i> Affecter</a></li>
                                </ul>
                            </li>
                            <!-- Ajoutez d'autres sous-rubriques au besoin -->
                        </ul>
                    </li>
                    <!-- Rubrique 8 : Gestion des absences -->
                    <li>
                        <a href="<?php echo "gestAbs.php";?>" class="toggle-submenu">
                        <span class="fa fa-check"></span> Gestion des Absences
                            <span class="xn-icon-button">
                                <!-- <i class="fa fa-plus"></i> -->
                            </span>
                        </a>
                        <!-- <ul style="display: block;">
                            <li><a href="#"><i class="fa fa-calendar-plus-o"></i> Gérer les emplois du temps des matières</a></li>
                            Ajoutez d'autres sous-rubriques au besoin
                        </ul> -->
                    </li>
                    <!-- menu etudiant -->

                    <li><a
                            href="<?php echo "mdpadmin.php"; ?>"><span
                                class="xn-title fa fa-lock"></span><span
                                class="xn-text">Changement de mot de passe</span></a></li>

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
                                    <!-- <li class="xn-icon-button">
                                        <a href="#"
                                            class="x-navigation-minimize"><span
                                                class="fa fa-dedent"></span></a>
                                    </li> -->
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
                                                <!-- <a
                                                    href="Authentification.html"
                                                    class="btn btn-success btn-lg">OUI</a> -->
                                                <!-- <a
                                                    href="javascript:deconnexion()"
                                                    class="btn btn-success btn-lg">OUI</a> -->
                                                <button
                                                    class="btn btn-default btn-lg mb-control-close">NON</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script language="javascript"
                                type="text/javascript">

   function chargerPage( page ){
			
		$.ajax({
		  type: "POST",
		  url:  "model/gestiUtilisateurs.php?func=page",
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

                            <ul class="breadcrumb push-down-0">
                                <li><a href="<?php echo "Acceuiladmin.php"; ?>">Acceuil</a></li>
                                <li class="active">Gestion des Ressources</li>
                                <li class="active">Gestion RFID</li>
                            </ul>

                            <div class="content-frame">
                                <!-- template -->
                                <div class="content-frame-top">
                                    <div class="page-title">
                                        <h2><span></span>Gestion RFID</h2>
                                    </div>

                                </div>

                                <!-- <div class="row" style="margin-left: 60px;"> -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <ul class="panel-controls">
                                                <li>
                                                    <a href="#" class="panel-collapse">
                                                        <span class="fa fa-angle-down" aria-hidden="true"></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="panel-refresh">
                                                        <span class="fa fa-refresh" aria-hidden="true"></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="panel-body table-responsive">
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
                                                            style="width: 297px;" aria-sort="ascending">Nom
                                                        </th>
                                                        <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Matières: activate to sort column ascending" 
                                                            style="width: 160px;">Prénom
                                                        </th>
                                                        <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Date: activate to sort column ascending" 
                                                            style="width: 101px;">ID Carte
                                                        </th>
                                                        <!-- <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Justification: activate to sort column ascending" 
                                                            style="width: 216px;">Telephone 1
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="Heure début: activate to sort column ascending" style="width: 215px;">
                                                        Telephone 2</th>
                                                        <th class="sorting" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="Heure fin: activate to sort column ascending" style="width: 169px;">
                                                            Action</th> -->
                                                        <!-- <th class="sorting_disabled" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="La durée" style="width: 163px;">
                                                            La durée</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>                                                                       
                                                    <?php echo $lignesHTML;?>
                                                </tbody> 
                                            </table>
                                        </div>
                                    </div>
                                <!-- <form method="post" id="formMdp" name="formMdp">
                                    <div class="scheduler-border col-md-2">
                                    </div>
                                    <fieldset class="scheduler-border col-md-8">
                                        <legend
                                            class="scheduler-border">Supprimer étudiant</legend>
                                        <div>
                                            <table align="center">
                                                <tr>
                                                    <td style="width:200px;"
                                                        align="left">
                                                        Nom :
                                                    </td>
                                                    <td style="width:250px;"
                                                        align="center">
                                                        <input type="text"
                                                            id="nomEtud" name="nomEtud"
                                                            style="width:200px;"/>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width:200px;"
                                                        align="left">
                                                        Prénom :
                                                    </td>
                                                    <td style="width:250px;"
                                                        align="center">
                                                        <input type="text"
                                                            id="prenomEtud" name="prenomEtud"
                                                            style="width:200px;"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;"
                                                        align="left">
                                                        Email :
                                                    </td>
                                                    <td style="width:250px;"
                                                        align="center">
                                                        <input type="mail"
                                                            id="mailEtud" name="mailEtud"
                                                            style="width:200px;"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center"
                                                        colspan="2">
                                                        <input type="button"
                                                            style="cursor:pointer;"
                                                            id="Retablir" name="Retablir"
                                                            value="Rétablir" onclick="getElementById('PasswordA').value = '';
                                                                                        getElementById('PasswordC').value = ''"/>

                                                        &nbsp;&nbsp;&nbsp;
                                                        <input type="submit"
                                                            style="cursor:pointer;"
                                                            id="add" name="add"
                                                            value="Valider" />

                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </fieldset>
                                </form> -->
                                    <!-- <fieldset class="classDashboard col-md-11">
                                        <legend class="legendDashboard">shi haja</legend>
                                        <div>
                                            <div class="col-md-1"></div>
                                            
                                        </div>
                                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                    </fieldset> -->
                                </div>
                                <br /><br /><br />

                                <!--  **********************************debut******************************************************************  -->

                                <!--  ****************************************************************************************************  -->

                                <!-- <div class="row" style="margin-left: 60px;">
                                    <fieldset class="classDashboard col-md-11">
                                        <legend
                                            class="legendDashboard">Récapitulatif</legend>

                                        <div class="col-md-12">
                                            <div class="col-md-3">

                                                <div
                                                    class="widget widget-warning widget-carousel">
                                                    <div class="owl-carousel"
                                                        id="owl-example">

                                                        <div>
                                                            <div
                                                                class="widget-title">Nombres Etudiants</div>
                                                            <div
                                                                class="widget-subtitle"></div>
                                                            <div align="center"
                                                                id="Montant"><font size="5"><b>10000 </b></font></div>
                                                        </div>

                                                        <div>
                                                            <div
                                                                class="widget-title">Nombres Enseignants</div>
                                                            <div
                                                                class="widget-subtitle"></div>
                                                            <div align="center"
                                                                id="Montant_paye">
                                                                <font size="5"><b>100 </b></font>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <div
                                                                class="widget-title">Nombres Salles</div>
                                                            <div
                                                                class="widget-subtitle"></div>
                                                            <div align="center"
                                                                id="Reste_a_paye"><font size="5"><b>20 </b></font></div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div> -->

                                            <!-- <div class="col-md-3">

                                                <div
                                                    class="widget widget-success widget-item-icon">
                                                    <div
                                                        class="widget-item-left">
                                                        <span> <a href="<?php echo "abs.php";?>"><img
                                                                src="photos/gsi_1.png" /></a>
                                                        </div>
                                                        <div
                                                            class="widget-data">
                                                            <div
                                                                class="widget-title">Total
                                                                des Heures
                                                                absentées</div>
                                                            <div
                                                                id="Total_absence1"><font color="RED" size="4"><?php echo $abs;?></font></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">

                                                    <div
                                                        class="widget widget-primary widget-item-icon">
                                                        <div
                                                            class="widget-item-left">
                                                            <span> <a href="<?php echo "retards.php"?>"><img
                                                                    src="photos/retard_1.png" /></a>
                                                            </div>
                                                            <div
                                                                class="widget-data">
                                                                <div
                                                                    class="widget-title">Total
                                                                    heures de
                                                                    retard</div>
                                                                <div
                                                                    id="Total_retard1"><font color="#FF8000" size="4"><?php echo $retard;?></font></div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-3">
                                                        <div
                                                            class="widget widget-danger widget-padding-sm">
                                                            <div
                                                                class="widget-big-int plugin-clock">12<span>:</span>00</div>
                                                            <div
                                                                class="widget-subtitle plugin-date">Thursday,
                                                                July 26,
                                                                2018</div>
                                                        </div>
                                                    </div>

                                                </div> -->
                                            </fieldset>
                                        </div>

                                        <!--  Fin Dashboard prof  -->

                                    </div>
                                </div>

                            </div>

                            <script charset="utf-8"
                                src="js/Dashboard.js"></script>

                        </body>
                    </html>

                    <!-- main content -->
                    <!-- main content -->

                    <!-- sidebar -->
                    <!-- sidebar -->
                    <!-- datatable -->
                    <!--   <script src="../include/gespa/lib/datatables/jquery.dataTables.min.js"></script>-->
                    <!--  <script src="../include/gespa/lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>-->
                    <!-- datatable functions -->
                    <!--   <script src="../include/gespa/js/gebo_datatables.js"></script>-->

                    <!--<script type='text/javascript' src='../include/gespa/js/js/plugins/jquery-validation/jquery.validate.js'></script>-->
                    <script type='text/javascript'
                        src="js/plugins/validationengine/languages/jquery.validationEngine-en.js"></script>
                    <script type='text/javascript'
                        src="js/plugins/validationengine/jquery.validationEngine.js"></script>
                    <script charset="utf-8"
                        src="js/relevePaiemetsEtudiant.js"></script>
                    <script charset="utf-8"
                        src="js/infoUtilisateur.js"></script>

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
