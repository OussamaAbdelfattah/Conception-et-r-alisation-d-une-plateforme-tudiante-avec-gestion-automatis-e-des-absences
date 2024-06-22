<?php
    session_start();
    $nomComplet = '';
    $Ref = '';
    $src_image = '';
    $retard = '';

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

    if(isset($_POST['deconnexion'])){
        session_destroy();
        header("Location: Authentification.php");
    }

    if(isset($_SESSION['email']) && isset($_SESSION['pwd']) && isset($_SESSION['nom']) && isset($_SESSION['prenom'])){
        $nom = $_SESSION['nom'];
        $prenom = $_SESSION['prenom'];
        $mail = $_SESSION['email'];
        $pwd = $_SESSION['pwd'];
        $nomComplet .= $nom . ' ' .  $prenom;
        $_SESSION['nomComplet'] = $nomComplet;
                include_once "php/conn.php";
                
                $sql1 = "SELECT promo.ref as promo, md.ref as md
                        FROM Users u, Personne p, Personne_Module pm, Module md, Promotion promo
                        WHERE u.id_personne=p.id_personne
                        AND p.nom='$nom' AND p.prenom='$prenom'
                        AND p.id_personne=pm.id_personne
                        AND pm.id_module=md.id_module
                        AND md.id_promotion=promo.id_promotion
                        ;";
                $result1 = $conn->query($sql1);
                $row1 = $result1->fetch_assoc();
                
                
                if (!$result1) {
                    die('Erreur d\'exécution de la requête : ' . $conn->error);
                }
                
                if(!empty($row1)){
                    $_SESSION['promo'] = $row1['promo'];
                    $_SESSION['md'] = $row1['md'];
                    if ($row1['md'] == null){
                        $Ref .= $row1['promo'];
                    }else{
                        $Ref .= $row1['promo'] . '-' . $row1['md'];
                    }
                }
                $sql2 = "SELECT COUNT(ps.id_presence) as count
                        FROM Users u, Personne p, Presence ps, Seance sc 
                        WHERE u.mail='$mail'
                        AND u.pwd='$pwd'
                        AND u.id_personne=p.id_personne 
                        AND p.id_personne=ps.id_personne 
                        AND ps.est_present=0 
                        AND ps.id_seance=sc.id_seance; ";
                // Exécution de la requête
                $result2 = $conn->query($sql2);
                // Vérification des erreurs d'exécution de la requête 
                if (!$result2) {
                    die('Erreur d\'exécution de la requête : ' . $conn->error);
                }
                $row2 = $result2->fetch_assoc();
                if(!empty($row2)){
                    $abs = calculerTotalHeures($row2['count']);
                }
                // Requête de sélection
                $sql3 = "SELECT COUNT(ps.id_presence) as count
                FROM Users u, Personne p, Presence ps, Seance sc 
                WHERE u.mail='$mail' 
                AND u.pwd='$pwd' 
                AND u.id_personne=p.id_personne 
                AND p.id_personne=ps.id_personne 
                AND ps.est_present=1
                AND ps.en_retard=1
                AND ps.id_seance=sc.id_seance; ";
        // Exécution de la requête
        $result3 = $conn->query($sql3);
        // Vérification des erreurs d'exécution de la requête 
        if (!$result3) {
            die('Erreur d\'exécution de la requête : ' . $conn->error);
        }
        $row3 = $result3->fetch_assoc();
        if(empty($row3)){
            echo "<script>alert('donnees incorrectes!!');</script>"; 
        }else{
            $retard = calculerTotalHeures($row3['count']);
        }
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
        <script type="<!-- debut code Ahmed -->text/javascript"
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
                        <a href="<?php echo "Acceuil.php"; ?>">ISGA
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
                        <a href="<?php echo "Acceuil.php"; ?>"><span
                                class="xn-title fa fa-desktop"></span> <span
                                class="xn-text">Dashboard</span></a> </li>

                    <!-- menu etudiant -->

                    <!-- <li><a
                            href="javascript:chargerPage( 'offreStageEmploi')"><span
                                class="xn-title fa fa-bullhorn"></span><span
                                class="xn-text">Recrutement</span></a></li> -->
                    <li>
                            <a  
                                href="<?php echo "retards.php"; ?>"><span
                                class="xn-title fa fa-clock-o"></span><span
                                class="xn-text">Relevé des
                                retards</span></a>
                    </li>
                    <li><a
                            href="<?php echo "abs.php"; ?>"><span
                                class="xn-title fa fa-user"></span><span
                                class="xn-text">Relevé des
                                absences</span></a></li>

                    <li><a
                            href="<?php echo "notes.php"; ?>"><span
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
                            href="<?php echo "mdps.php"; ?>"><span
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
                                <li><a href="<?php echo "Acceuil.php"; ?>">Acceuil</a></li>
                            </ul>

                            <div class="content-frame">

                                <div class="content-frame-top">
                                    <div class="page-title">
                                        <h2><span></span>Acceuil</h2>
                                    </div>

                                </div>

                                <div class="row" style="margin-left: 60px;">

                                    <fieldset class="classDashboard col-md-11">
                                        <legend class="legendDashboard">Menu
                                            principal</legend>
                                        <div>
                                            <div class="col-md-1"></div>
                                            <!-- <div class="col-md-2">
                                                <div>
                                                    <a
                                                        href="javascript:chargerPage( 'offreStageEmploi')">
                                                        <span><img
                                                                src="photos/recrutement.png"
                                                                align="middle"
                                                                border="3" /><br />
                                                            <font color="BLACK"
                                                                size="3">Recrutement</font></span></a>
                                                </div>
                                            </div> -->

                                            <div class="col-md-2">
                                                <div>
                                                    <a
                                                        href="<?php echo "retards.php"; ?>">
                                                        <span><img
                                                                src="photos/retard.png"
                                                                align="middle"
                                                                border="3" /><br />
                                                            <font color="BLACK"
                                                                size="3">Relevé
                                                                des
                                                                retards</font></span></a>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div>
                                                    <a
                                                        href="<?php echo "abs.php"; ?>">
                                                        <span><img
                                                                src="photos/gsi.png"
                                                                align="middle"
                                                                border="3" /><br />
                                                            <font color="BLACK"
                                                                size="3">Relevé
                                                                des
                                                                absences</font></span></a>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div>
                                                    <a
                                                        href="<?php echo "notes.php"; ?>">
                                                        <span><img
                                                                src="photos/releve.png"
                                                                align="middle"
                                                                border="3" /><br />
                                                            <font color="BLACK"
                                                                size="3">Relevé
                                                                des
                                                                notes</font></span></a>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div>
                                                    <a
                                                        href="<?php echo "mdps.php"; ?>">
                                                        <span><img
                                                                src="photos/mdp.png"
                                                                align="middle"
                                                                border="3" /><br />
                                                            <font color="BLACK"
                                                                size="3">Changement
                                                                mot de
                                                                passe</font></span></a>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2">
                                                <div>
                                                    <a
                                                        href="javascript:chargerPage( 'relevePaiemetsEtudiant','1')">
                                                        <span><img
                                                                src="photos/dollard.png"
                                                                align="middle"
                                                                border="3" /><br />
                                                            <font color="BLACK"
                                                                size="3">Relevé
                                                                des
                                                                paiements</font></span></a>
                                                </div>
                                            </div> -->

                                        </div>
                                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                    </fieldset>
                                </div>
                                <br /><br /><br />

                                <!--  **********************************debut******************************************************************  -->

                                <!--  ****************************************************************************************************  -->

                                <div class="row" style="margin-left: 60px;">
                                    <fieldset class="classDashboard col-md-11">
                                        <legend
                                            class="legendDashboard">Récapitulatif</legend>

                                        <div class="col-md-12">
                                            <!-- <div class="col-md-3">

                                                <div
                                                    class="widget widget-warning widget-carousel">
                                                    <div class="owl-carousel"
                                                        id="owl-example">

                                                        <div>
                                                            <div
                                                                class="widget-title">Montant
                                                                à payer</div>
                                                            <div
                                                                class="widget-subtitle">Pour
                                                                l'année
                                                                2023/2024</div>
                                                            <div align="center"
                                                                id="Montant"></div>
                                                        </div>

                                                        <div>
                                                            <div
                                                                class="widget-title">Montant
                                                                payé</div>
                                                            <div
                                                                class="widget-subtitle">à
                                                                la date du
                                                                23-03-2024</div>
                                                            <div align="center"
                                                                id="Montant_paye">
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <div
                                                                class="widget-title">Montant
                                                                restant</div>
                                                            <div
                                                                class="widget-subtitle">Pour
                                                                l'année
                                                                2023/2024</div>
                                                            <div align="center"
                                                                id="Reste_a_paye"></div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div> -->

                                            <div class="col-md-3">

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
                                                                    des heures de
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
