<?php
    session_start();
    $nomComplet = $_SESSION['nom'];
    $Ref = '';
    $src_image = 'photos/0.png';

    $nbre_Modules = '';
    $nbre_prof = '';
    $nbre_Salle = '';
    $nbre_Etud = '';
    $modules_1AP = '0';
    $modules_2AP = '0';
    $modules_1CI = '0';
    $modules_2CI = '0';
    $modules_3CI = '0';
    $etud_1AP = '0';
    $etud_2AP = '0';
    $etud_1CI = '0';
    $etud_2CI = '0';
    $etud_3CI = '0';
    include_once "php/conn.php";
    //cmd 1
    $sql1 = "SELECT COUNT(md.id_module) as nbre_Modules 
            FROM Module md;";
    $result1 = $conn->query($sql1);
    $row1 = $result1->fetch_assoc();
    //cmd 2
    $sql2 = "SELECT DISTINCT(COUNT(id_personne)) as nbre_prof
            FROM Personne WHERE type = 'Prof';";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    //cmd 3
    $sql3 = "SELECT DISTINCT COUNT(id_salle) as nbre_Salle FROM Salle;";
    $result3 = $conn->query($sql3);
    $row3 = $result3->fetch_assoc();
    //cmd 4
    $sql4 = "SELECT COUNT(id_personne) as nbre_Etud
            FROM Personne WHERE type = 'Etudiant';";
    $result4 = $conn->query($sql4);
    $row4 = $result4->fetch_assoc();

    //cmd 5
    $sql5 = "SELECT count(md.id_module) as nbremoduleref, p.ref as nomref
            FROM Module md, Promotion p 
            WHERE md.id_promotion = p.id_promotion 
            GROUP BY(p.id_promotion); ";
    $result5 = $conn->query($sql5);
    
    //cmd 6
    $sql6 = "SELECT COUNT(DISTINCT p.id_personne) as nbre_perso, promo.ref as promo
            from Personne p, Personne_Module pr, Module md, Promotion promo
            where p.type = 'Etudiant'
            AND p.id_personne = pr.id_personne
            AND pr.id_module = md.id_module
            AND md.id_promotion = promo.id_promotion
            group by(promo.ref);";
    $result6 = $conn->query($sql6);
    if (!$result1 || !$result2 || !$result3 || !$result4 || !$result5 || !$result6) {
        die('Erreur d\'exécution de la requête : ' . $conn->error);
    }
    
    if(!empty($row1) && !empty($row2) && !empty($row3) && !empty($row4) ){
        $nbre_Modules = $row1['nbre_Modules'];
        $nbre_prof = $row2['nbre_prof'];
        $nbre_Salle = $row3['nbre_Salle'];
        $nbre_Etud = $row4['nbre_Etud'];
        
        
        while ($row5 = $result5->fetch_assoc()){
            if($row5['nomref'] == '1AP'){
                $modules_1AP = $row5['nbremoduleref'];
            }else if($row5['nomref'] == '2AP'){
                $modules_2AP = $row5['nbremoduleref'];
            }else if($row5['nomref'] == '1CI'){
                $modules_1CI = $row5['nbremoduleref'];
            }else if($row5['nomref'] == '2CI'){
                $modules_2CI = $row5['nbremoduleref'];
            }else{
                $modules_3CI = $row5['nbremoduleref'];
            }
        }
        while ($row6 = $result6->fetch_assoc()){
            if($row6['promo'] == '1AP'){
                $etud_1AP = $row6['nbre_perso'];
            }else if($row6['promo'] == '2AP'){
                $etud_2AP = $row6['nbre_perso'];
            }else if($row6['promo'] == '1CI'){
                $etud_1CI = $row6['nbre_perso'];
            }else if($row6['promo'] == '2CI'){
                $etud_2CI = $row6['nbre_perso'];
            }else{
                $etud_3CI = $row6['nbre_perso'];
            }
        }
    }

    include_once "php/disconn.php";

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

        <!-- <style>
            /* Afficher les icônes lorsque la barre de navigation est minimisée */
            .page-sidebar-fixed .x-navigation-minimize .xn-text {
                display: inline !important;
            }
        </style> -->
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
                        <a href="<?php echo "Acceuiladmin.php"; ?>">
                            <span class="xn-title fa fa-desktop"></span>
                            <span class="xn-text">Dashboard</span>
                        </a>
                    </li>
                    <!-- Sous_menu -->
                    <li>
                        <a href="<?php echo "gestEtud.php";?>" class="toggle-submenu">
                            <span class="fa fa-graduation-cap"></span> Gestion des étudiants
                        </a>
                        <!-- <ul style="display: block;">
                            <li><a href="<?php echo "ajouterEtud.php"; ?>"><i class="fa fa-plus-circle"></i>Ajouter des étudiants</a></li>
                            <li><a href="<?php echo "modifierEtud.php";?>"><i class="fa fa-pencil"></i>Modifier des étudiants</a></li>
                            <li><a href="<?php echo "supprimerEtud.php";?>"><i class="fa fa-trash"></i>Supprimer des étudiants</a></li>
                            <li><a href="#"><i class="fa fa-search"></i>Chercher des étudiants</a></li>
                        </ul> -->
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
                            </ul>

                            <div class="content-frame">

                                <div class="content-frame-top">
                                    <div class="page-title">
                                        <h2><span></span>Acceuil</h2>
                                    </div>

                                </div>
                                 <br /><br /><br />

                                <!--  **********************************debut******************************************************************  -->

                                <!--  ****************************************************************************************************  -->

                                <div class="row" style="margin-left: 60px;">
                                    <fieldset class="classDashboard col-md-11">
                                        <legend
                                            class="legendDashboard">Récapitulatif</legend>

                                        <div class="col-md-12">
                                            <!-- Gestion des Salles -->
                                            <div class="col-md-3">
                                                
                                                <div
                                                    class="widget widget-danger widget-carousel" >
                                                    <a href="<?php echo "gestSalle.php";?>" style="color: white;">
                                                    <div class="owl-carousel"
                                                        id="owl-example">
                                                        <div>
                                                            
                                                            <div
                                                                class="widget-title">Salles</div>
                                                            <div
                                                                class="widget-subtitle"></div>
                                                            <div align="center"
                                                                id="Montant"><font size="5"><b><?php echo $nbre_Salle;?></b></font></div>
                                                            
                                                        </div>
                                                    </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- Gestion des Enseignants -->
                                            <div class="col-md-3">
                                                
                                                <div
                                                    class="widget widget-danger widget-carousel">
                                                    <a href="<?php echo "gestEnsei.php";?>" style="color: white;">
                                                        <div class="owl-carousel"
                                                            id="owl-example">

                                                            <div>
                                                                <div
                                                                    class="widget-title">Enseignants</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $nbre_prof;?></b></font></div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- Gestion des Etudiants -->
                                            <div class="col-md-3">
                                                <div
                                                    class="widget widget-warning widget-carousel">
                                                    <a href="<?php echo "gestEtud.php";?>" style="color: white;">
                                                        <div class="owl-carousel"
                                                            id="owl-example">

                                                            <div>
                                                                <div
                                                                    class="widget-title">Etudiants</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $nbre_Etud;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">1AP</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $etud_1AP;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">2AP</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $etud_2AP;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">1CI</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $etud_1CI;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">2CI</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $etud_2CI;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">3CI</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $etud_3CI;?></b></font></div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- Gestion des Modules -->
                                            <div class="col-md-3">
                                                
                                                <div
                                                    class="widget widget-success widget-carousel">
                                                    <a href="<?php echo "gestMod.php";?>" style="color: white;">
                                                        <div class="owl-carousel"
                                                            id="owl-example">

                                                            <div>
                                                                <div
                                                                    class="widget-title">Modules</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $nbre_Modules;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">1AP</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $modules_1AP;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">2AP</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $modules_2AP;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">1CI</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $modules_1CI;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">2CI</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $modules_2CI;?></b></font></div>
                                                            </div>

                                                            <div>
                                                                <div
                                                                    class="widget-title">3CI</div>
                                                                <div
                                                                    class="widget-subtitle"></div>
                                                                <div align="center"
                                                                    id="Montant"><font size="5"><b><?php echo $modules_3CI;?></b></font></div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            

                                        </div>

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
