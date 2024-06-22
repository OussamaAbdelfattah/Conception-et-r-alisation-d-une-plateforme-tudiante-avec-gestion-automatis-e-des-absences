<?php
session_start();
$nomComplet = $_SESSION['nom'];

// var_dump($_SESSION);
$Ref = '';
$src_image = 'photos/0.png';
$lignesHTML = '';
$id;
// $message = "Are you sure you want to submit the form?";
//     echo "<script>confirm('" . $message . "');</script>";
include_once "php/conn.php";
$sql1 = "SELECT p.id_personne, p.nom, p.prenom, p.type, p.adresse, p.tele1 FROM Personne p
                WHERE p.type = 'Etudiant'";
$result1 = $conn->query($sql1);


if (!$result1) {
    die('Erreur d\'exécution de la requête : ' . $conn->error);
}
while ($row1 = $result1->fetch_assoc()) {
    $id = $row1['id_personne'];
    $nom = $row1['nom'];
    $prenom = $row1['prenom'];
    $nomprenom = $nom . ' ' . $prenom;
    $type = $row1['type'];
    $adresse = $row1['adresse'];
    $tele1 = $row1['tele1'];
    $sql4 = "SELECT mail FROM Users
                WHERE id_personne = $id;";
    $result4 = $conn->query($sql4);
    if (!$result4) {
        die('Erreur d\'exécution de la requête : ' . $conn->error);
    }else{
        $row4 = $result4->fetch_assoc();
        // var_dump($row4);
        $mail = $row4['mail'];
    }
    
    // Génération du code HTML pour chaque ligne de tableau avec un identifiant unique pour le formulaire de modification
    $lignesHTML .= "<tr>
                        <td><span class='editable' id='nom-$id'>$nom</span></td>
                        <td><span class='editable' id='prenom-$id'>$prenom</span></td>
                        <td><span class='editable' id='mail-$id'>$mail</span></td>
                        <td><span class='editable' id='adresse-$id'>$adresse</span></td>
                        <td><span class='editable' id='tele1-$id'>$tele1</span></td>
                        <td>
                            <form method='post'>
                                <button type='submit' class='btn-edit' style='background: none; border: none; padding: 0; color: #d9534f;' name='edit' value='$id' onclick='toggleForm(event, $id)'>
                                    <i class='fa fa-edit fa-2x'></i>
                                </button>
                                <button type='submit' class='btn-delete' style='background: none; border: none; padding: 0; color: #d9534f;' name='supp' value='$id'>
                                    <i class='fa fa-trash fa-2x'></i>
                                </button>
                                <button type='submit' class='btn-edit' style='background: none; border: none; padding: 0; color: #d9534f;' name='stat' value='$nomprenom' onclick='openStatsPage('etudiantSTAT.php')'>
                                    <i class='fa fa-eye fa-2x'></i>statistiques
                                </button>
                            </form>
                            
                        </td>
                    </tr>";
}


if (isset($_POST['Ajouter'])) {
    
    $nom = $_POST['nomEtud'];
    $prenom = $_POST['prenomEtud'];
    $mail = $_POST['mailEtud'];
    $adresse = $_POST['adresseEtud'];
    $tele1 = $_POST['telEtud1'];
    if(!empty($nom) && !empty($prenom) && !empty($mail) && !empty($adresse) && !empty($tele1)){
        $sql3 = "INSERT INTO Personne (nom, prenom, type, adresse, tele1) VALUES('$nom', '$prenom', 'Etudiant', '$adresse', '$tele1');";
        $result3 = $conn->query($sql3);
        $sql6 = "SELECT id_personne FROM Personne WHERE nom='$nom' AND prenom='$prenom';";
        $result6 = $conn->query($sql6);
        if (!$result6) {
            die('Erreur d\'exécution de la requête : ' . $conn->error);
        }
        $row6 = $result6->fetch_assoc();
        $idPersonne = $row6['id_personne'];
        $sql5 = "INSERT INTO Users (mail, pwd, id_personne)
                VALUES('$mail', '$2y$12\$aa88/Ntk5hivj4uNZ2E5muYqpgDQqDkk0/3izF.ZN/b.Ij7Yb9oTa', $idPersonne);";
        $result5 = $conn->query($sql5);
        if (!$result3 || !$result5) {
            die('Erreur d\'exécution de la requête : ' . $conn->error);
        } else {
            echo 'Données Modifiees avec succès !';
        }
        header("Location: gestEtud.php");
        exit();
    }else{
        $message = "Champs Obligatoires !!";
        echo "<script>alert('" . $message . "');</script>";
    }
}
    

// Traitement de la modification
if (isset($_POST['modifier'])) {
    $nom = $_POST['nomEtud'];
    $prenom = $_POST['prenomEtud'];
    $mail = $_POST['mailEtud'];
    $adresse = $_POST['adresseEtud'];
    $tele1 = $_POST['telEtud1'];
    $idd = $_POST['ID'];


    // var_dump($_POST);
    $sql2 = "UPDATE PERSONNE SET nom='$nom', prenom='$prenom', adresse = '$adresse', tele1 = '$tele1' WHERE id_personne = $idd;";
    $sql8 = "UPDATE Users SET mail = '$mail' WHERE id_personne = $idd;";
    $result2 = $conn->query($sql2);
    $result8 = $conn->query($sql8);

    if (!$result2 || !$result8) {
        die('Erreur d\'exécution de la requête : ' . $conn->error);
    } else {
        echo 'Données Modifiees avec succès !';
    }
    header("Location: gestEtud.php");
    exit();
}

// Traitement de la suppression
if (isset($_POST['supp'])) {
    $idd = $_POST['supp'];
    $sql = "DELETE FROM Personne WHERE id_personne=$idd";
    $sql7 = "DELETE FROM Users WHERE id_personne=$idd";
    $result7 = $conn->query($sql7);
    // Exécution de la requête
    $result = $conn->query($sql);
    
    if (!$result || !$result7) {
        die('Erreur d\'exécution de la requête : ' . $conn->error);
    }
    // Redirection après suppression
    header("Location: gestEtud.php");
    exit();
}

// Traitement de la déconnexion
if (isset($_POST['deconnexion'])) {
    session_destroy();
    header("Location: Authentification.php");
    exit();
}
if (isset($_POST['stat'])) {
    // var_dump($_POST);
    $_SESSION['nomprenom'] = $_POST['stat'];
    echo '<script>window.open("etudiantSTAT.php?user=' . $_POST['stat'] . '", "_blank", "width=800,height=600")</script>';
    // exit();
}

include_once "disconn.php";
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
                        <a href="<?php echo "gestEtud.php"; ?>" class="toggle-submenu">
                            <span class="fa fa-graduation-cap"></span> Gestion des étudiants
                            
                        </a>
                        <!-- <ul style="display: block;">
                            <li><a href="<?php echo "ajouterEtud.php"; ?>"><i class="fa fa-plus-circle"></i>Ajouter des étudiants</a></li>
                            <li><a href="<?php echo "modifierEtud.php"; ?>"><i class="fa fa-pencil"></i>Modifier des étudiants</a></li>
                            <li><a href="<?php echo "supprimerEtud.php"; ?>"><i class="fa fa-trash"></i>Supprimer des étudiants</a></li>
                            <li><a href="#"><i class="fa fa-search"></i>Chercher des étudiants</a></li>
                        </ul> -->
                    </li>
                    <!-- Rubrique 2 : Gestion des enseignants -->
                    <li>
                        <a href="<?php echo "gestEnsei.php"; ?>" class="toggle-submenu">
                            <span class="fa fa-user"></span> Gestion des enseignants
                            <span class="xn-icon-button">
                                <!-- <i class="fa fa-plus"></i> -->
                            </span>
                        </a>
                        <!-- <ul style="display: block;">
                            <li><a href="#"><i class="fa fa-plus-circle"></i> Ajouter des enseignants</a></li>
                            <li><a href="#"><i class="fa fa-pencil"></i> Modifier des enseignants</a></li>
                            <li><a href="#"><i class="fa fa-trash"></i> Supprimer des enseignants</a></li>
                            <li><a href="#"><i class="fa fa-search"></i>Chercher des enseignants</a></li>
                            <li><a href="#"><i class="fa fa-tasks"></i>Affecter des enseignants</a></li>
                        </ul> -->
                    </li>
                    <!-- Rubrique 3 : Gestion des modules -->
                    <li>
                        <a href="<?php echo "gestMod.php"; ?>" class="toggle-submenu">
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
                        <a href="<?php echo "gestExam.php"; ?>" class="toggle-submenu">
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
                        <a href="<?php echo "gestEmploi.php"; ?>" class="toggle-submenu">
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
                                <li class="active">Gestion des étudiants</li>
                            </ul>

                            <div class="content-frame">
                                <!-- template -->
                                <div class="content-frame-top">
                                    <div class="page-title">
                                        <h2><span></span>Gestion des étudiants</h2>
                                    </div>

                                </div>
                                <div class="row" id="monFormulaire" style="margin-left: 60px; display: none;">
                                    <form method="post"  name="form">
                                        <div class="scheduler-border col-md-2">
                                        </div>
                                        <fieldset class="scheduler-border col-md-8">
                                        <legend
                                        class="scheduler-border" id="legend1">Modifier étudiant</legend>
                                            <div>
                                                <table align="center">
                                                    <tr>
                                                        <td style="width:200px;height:50px;"
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
                                                        <td style="width:200px;height:50px;"
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
                                                        <td style="width:200px;height:50px;"
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
                                                        <td style="width:200px;height:50px;"
                                                            align="left">
                                                            Adresse :
                                                        </td>
                                                        <td style="width:250px;"
                                                            align="center">
                                                            <input type="text"
                                                                id="adresseEtud" name="adresseEtud"
                                                                style="width:200px;"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:200px;height:50px;"
                                                            align="left">
                                                            telephone 1 :
                                                        </td>
                                                        <td style="width:250px;"
                                                            align="center">
                                                            <input type="text"
                                                                id="telEtud1" name="telEtud1"
                                                                style="width:200px;"/>
                                                        </td>
                                                    </tr>
                                                    <input type="hidden" id="ID" name="ID">
                                                    <!-- <tr>
                                                        <td style="width:200px;height:50px;"
                                                            align="left">
                                                            telephone 2 :
                                                        </td>
                                                        <td style="width:250px;"
                                                            align="center">
                                                            <input type="text"
                                                                id="telEtud2" name="telEtud2"
                                                                style="width:200px;"/>
                                                        </td>
                                                    </tr> -->
                                                    <tr>
                                                        <td align="center"
                                                            style="height:50px;"
                                                            colspan="2">
                                                            <input type="button"
                                                                style="cursor:pointer;"
                                                                id="Retablir" name="Retablir"
                                                                value="Rétablir" onclick="getElementById('telEtud1').value = '';
                                                                                            getElementById('adresseEtud').value = '';getElementById('mailEtud').value = '';
                                                                                            getElementById('prenomEtud').value = ''; getElementById('nomEtud').value = ''"/>

                                                            &nbsp;&nbsp;&nbsp;
                                                            <input type="submit"
                                                                style="cursor:pointer;"
                                                                id="add" name="add"
                                                                value="Valider" onclick="valider()"/>

                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <script>
                                    function toggleForm(event, id) {
                                        event.preventDefault(); // Empêcher le comportement par défaut du bouton
                                        if (document.getElementById('monFormulaire').style.display == 'none' && id != -1) {
                                            document.getElementById('monFormulaire').style.display = 'block';
                                            // console.log(id);
                                            event.preventDefault(); // Empêcher le formulaire d'être soumis

                                            // Sélectionner la ligne correspondante
                                            nom = document.getElementById("nom-" + id).innerHTML;
                                            prenom = document.getElementById("prenom-" + id).innerHTML;
                                            mail = document.getElementById("mail-" + id).innerHTML;
                                            adresse = document.getElementById("adresse-" + id).innerHTML;
                                            tele = document.getElementById("tele1-" + id).innerHTML;

                                            // console.log(nom, prenom, mail, adresse, tele);

                                            document.getElementById("nomEtud").setAttribute("value", nom);
                                            document.getElementById("prenomEtud").setAttribute("value", prenom);
                                            document.getElementById("mailEtud").setAttribute("value", mail);
                                            document.getElementById("adresseEtud").setAttribute("value", adresse);
                                            document.getElementById("telEtud1").setAttribute("value", tele);

                                            document.getElementById("nomEtud").setAttribute("placeholder", nom);
                                            document.getElementById("prenomEtud").setAttribute("placeholder", prenom);
                                            document.getElementById("mailEtud").setAttribute("placeholder", mail);
                                            document.getElementById("adresseEtud").setAttribute("placeholder", adresse);
                                            document.getElementById("telEtud1").setAttribute("placeholder", tele);

                                            document.getElementById("ID").setAttribute("value", id);
                                            document.getElementById("add").setAttribute("name", "modifier");
                                            document.getElementById("legend1").innerText = 'modifier étudiant';
                                        }else if(document.getElementById('monFormulaire').style.display == 'none' && id == -1){
                                            document.getElementById('monFormulaire').style.display = 'block';
                                            event.preventDefault(); // Empêcher le formulaire d'être soumis
                                            document.getElementById("add").setAttribute("name", "Ajouter");
                                            document.getElementById("legend1").innerText = 'Ajouter étudiant';
                                        }else {
                                            document.getElementById('monFormulaire').style.display = 'none';

                                            document.getElementById("nomEtud").setAttribute("value", "");
                                            document.getElementById("prenomEtud").setAttribute("value", "");
                                            document.getElementById("mailEtud").setAttribute("value", "");
                                            document.getElementById("adresseEtud").setAttribute("value", "");
                                            document.getElementById("telEtud1").setAttribute("value", "");

                                            document.getElementById("nomEtud").setAttribute("placeholder", "");
                                            document.getElementById("prenomEtud").setAttribute("placeholder", "");
                                            document.getElementById("mailEtud").setAttribute("placeholder", "");
                                            document.getElementById("adresseEtud").setAttribute("placeholder", "");
                                            document.getElementById("telEtud1").setAttribute("placeholder", "");
                                        }
                                        
                                    }
                                    function valider(){
                                        document.getElementById('monFormulaire').style.display = 'none';
                                    }
                                </script>


                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <ul class="panel-controls">
                                                <li>
                                                    <form method="post">
                                                        <button type="submit" class="btn-plus" style="background: none; border: none; padding: 0; color: green;" name="add_person" id="add_person" value="1" onclick="toggleForm(event, -1)">
                                                            <i class="fa fa-plus fa-2x"></i> Ajouter
                                                        </button>
                                                    </form>
                                                </li>
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

                                        <!-- iciii db  -->
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
                                                        <th class="sorting" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="Heure début: activate to sort column ascending" style="width: 215px;">
                                                            email</th>
                                                        <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Date: activate to sort column ascending" 
                                                            style="width: 101px;">Adresse
                                                        </th>
                                                        <th 
                                                            class="sorting" tabindex="0" 
                                                            aria-controls="table_retards_etudiant" 
                                                            rowspan="1" colspan="1" 
                                                            aria-label="Justification: activate to sort column ascending" 
                                                            style="width: 216px;">Telephone 1
                                                        </th>
                                                        <th class="sorting_disabled" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="Heure fin: activate to sort column ascending" style="width: 169px;">
                                                            Actions</th>
                                                        <!-- <th class="sorting_disabled" tabindex="0" aria-controls="table_retards_etudiant" rowspan="1" colspan="1" aria-label="La durée" style="width: 163px;">
                                                            La durée</th> -->
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>                                                                       
                                                    <!-- <tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No data available in table</td></tr> -->
                                                    <?php echo $lignesHTML;?>
                                                    <!-- <tr>
                                                        <td>c</td>
                                                        <td>a</td>
                                                        <td>a</td>
                                                        <td>a</td>
                                                        <td>a</td>
                                                        <td>
                                                            <i class="fa fa-edit fa-2x"></i>
                                                            <i class="fa fa-trash fa-2x"></i>
                                                        </td>
                                                    </tr> -->
                                                </tbody> 
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br /><br /><br />
                                <!--  **********************************debut******************************************************************  -->

                                <!--  ****************************************************************************************************  -->
                                            </fieldset>
                                        </div>

                                        <!--  Fin Dashboard prof  -->

                                    </div>
                                </div>

                            </div>
                            <script>
                                    function openStatsPage(phpFile) {
                                        window.open(phpFile, '_blank', 'width=800,height=600');
                                    }
                                </script>
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
