<?php 
        session_start();

        if(isset($_POST['connecter'])){
            if(!empty($_POST['username']) && !empty($_POST['password'])){
                $email = $_POST['username'];
                $pwd = $_POST['password'];
                
                include_once "php/conn.php";
                $sql2 = "SELECT u.pwd FROM Users u
                            WHERE u.mail='$email';";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();

                if (!$result2) {
                    die('Erreur d\'exécution de la requête : ' . $conn->error);
                }
                
                if(!empty($row2) && password_verify($pwd, $row2['pwd'])){
                    $newpwd = $row2['pwd'];
                    $sql1 = "SELECT p.nom, p.prenom, u.photo as img FROM Users u, Personne p 
                        WHERE u.mail='$email'
                        AND u.pwd='$newpwd'
                        AND u.id_personne=p.id_personne;";
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();

                    if (!$result1) {
                        die('Erreur d\'exécution de la requête : ' . $conn->error);
                    }
                    if(!empty($row1)){
                        $_SESSION['email'] = $email;
                        $_SESSION['pwd'] = $newpwd;
                        $_SESSION['nom'] = strtoupper($row1['nom']);
                        $_SESSION['prenom'] = $row1['prenom'];
                        $_SESSION['img'] = $row1['img'];
                        include_once "disconn.php";
                        if($row1['nom'] == 'Admin'){
                            header("Location: Acceuiladmin.php");
                        }else{
                            header("Location: Acceuil.php");
                        }
                    }
                }else{
                    $message = "Login/password : incorrect !";
                    echo "<script>alert('" . $message . "');</script>";
                }
            }else{
                $message = "Le champ identifiant et mot de passe sont obligatoire !";
                echo "<script>alert('" . $message . "');</script>";
            }
        }
?>
<!DOCTYPE html>
<html lang="en" class="body-full-height"><head>
        <!-- META SECTION -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ISGA DIRECT - ISGA Campus Rabat</title>

        <link rel="icon" href="photos/favicon.ico" type="image/x-icon">
        <!-- END META SECTION -->

        <!-- CSS INCLUDE -->

        <style>.file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }.file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }.file-input-name { margin-left: 8px; }</style><link
            rel="stylesheet" type="text/css" id="theme"
            href="css/theme-default.css">
        <link rel="stylesheet" type="text/css" id="theme"
            href="css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" id="theme" href="css/layout.css">
        <link rel="stylesheet" type="text/css" id="theme" href="css/dark.css">
        <link rel="stylesheet" type="text/css" id="theme"
            href="css/fullcalendar/fullcalendar.min.css">
        <link rel="stylesheet" type="text/css" id="theme"
            href="css/fullcalendar/fullcalendar.print.min.css" media="print">
        <link rel="stylesheet" type="text/css" id="theme"
            href="css/jquery-ui.css">


        <!-- EOF CSS INCLUDE -->

        <!-- START PRELOADS -->
    </head><body><audio id="audio-alert" src="audio/alert.mp3"
            preload="auto"></audio>
        <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->

        <!-- START SCRIPTS -->

        <link rel="stylesheet" type="text/css"
            href="css/dataTables.checkboxes.css">
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css">

        <!-- END THIS PAGE PLUGINS-->

        <!-- START TEMPLATE -->

        <!-- debut code -->

        <div id="loading_layer" style="display:none"><img
                src="img/ajax_loader.gif" alt></div>

        <div class="page-container">

            <!-- START PAGE CONTAINER -->

            <!-- EOF CSS INCLUDE -->
            <div class="login-container">
                <link rel="stylesheet" type="text/css" id="theme"
                    href="css/dark.css">
                <link rel="stylesheet" type="text/css" id="theme"
                    href="css/layout.css">
                <div class="login-box animated fadeInDown">
                    <div class="login-logo"></div>
                    <div class="login-body">
                        <div class="login-title"><strong>Bienvenue</strong>,
                            <font size="2.5">Veuillez saisir votre Login et mot
                                de passe pour se connecter.</font></div>
                        <form class="form-horizontal" id="login_form"
                             method="POST">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input id="username" name="username"
                                        type="text" class="form-control"
                                        placeholder="Nom de l'utilisateur"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input id="password" name="password"
                                        type="password" class="form-control"
                                        placeholder="Mot de passe">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6">
                                    <input id="connecter" name="connecter"
                                        type="submit" class="btn btn-info btn-block" value="Connexion">
                                    <!-- <button class="btn btn-info btn-block"
                                        id="connecter"
                                        type="submit"> Connexion</button> -->
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="login-footer">

                        <div class="pull-left">
                            Copyright © 2009 - 2024 - Tous droits réservés -
                            ISGA Maroc
                        </div>

                    </div>

                </div>

            </div>
            <link rel="stylesheet" type="text/css" id="theme"
                href="css/layout.css">
            <link rel="stylesheet" type="text/css" id="theme"
                href="css/dark.css">
            <script type="text/javascript"
                src="js/plugins/jquery/jquery.min.js"></script>
            <script type="text/javascript" src="js/jquery.redirect.js"></script>
            <script charset="utf-8" src="js/Authentification.js"></script>
            <script charset="utf-8" src="js/jquery.validate.min.js"></script>

            <script type="text/javascript"
                src="js/plugins/validationengine/languages/jquery.validationEngine-en.js"></script>
            <script type="text/javascript"
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


        </div><div id="topcontrol" title="Scroll Back to Top"
            style="position: fixed; bottom: 10px; right: 10px; opacity: 0; cursor: pointer;"><!-- TO TOP --><div
                class="to-top"><span
                    class="fa fa-angle-up"></span></div><!-- END TO TOP --></div></body></html>
<head>
    <!-- META SECTION -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ISGA DIRECT - ISGA Campus Rabat</title>

    <link rel="icon" href="photos/favicon.ico" type="image/x-icon">
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->

    <style>.file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }.file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }.file-input-name { margin-left: 8px; }</style><link
        rel="stylesheet" type="text/css" id="theme"
        href="css/theme-default.css">
    <link rel="stylesheet" type="text/css" id="theme"
        href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" id="theme" href="css/layout.css">
    <link rel="stylesheet" type="text/css" id="theme" href="css/dark.css">
    <link rel="stylesheet" type="text/css" id="theme"
        href="css/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" id="theme"
        href="css/fullcalendar/fullcalendar.print.min.css" media="print">
    <link rel="stylesheet" type="text/css" id="theme" href="css/jquery-ui.css">

    <!-- EOF CSS INCLUDE -->

    <!-- START PRELOADS -->
</head>
<!-- META SECTION -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ISGA DIRECT - ISGA Campus Rabat</title>
<link rel="icon" href="photos/favicon.ico" type="image/x-icon">
<!-- END META SECTION -->
<!-- CSS INCLUDE -->
<style>.file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }.file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }.file-input-name { margin-left: 8px; }</style>
<link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css">
<link rel="stylesheet" type="text/css" id="theme"
    href="css/bootstrap/bootstrap.min.css">
<link rel="stylesheet" type="text/css" id="theme" href="css/layout.css">
<link rel="stylesheet" type="text/css" id="theme" href="css/dark.css">
<link rel="stylesheet" type="text/css" id="theme"
    href="css/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" type="text/css" id="theme"
    href="css/fullcalendar/fullcalendar.print.min.css" media="print">
<link rel="stylesheet" type="text/css" id="theme" href="css/jquery-ui.css">
<!-- EOF CSS INCLUDE -->
<!-- START PRELOADS -->
<head>
    <!-- META SECTION -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ISGA DIRECT - ISGA Campus Rabat</title>

    <link rel="icon" href="photos/favicon.ico" type="image/x-icon">
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->

    <style>.file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }.file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }.file-input-name { margin-left: 8px; }</style><link
        rel="stylesheet" type="text/css" id="theme"
        href="css/theme-default.css">
    <link rel="stylesheet" type="text/css" id="theme"
        href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" id="theme" href="css/layout.css">
    <link rel="stylesheet" type="text/css" id="theme" href="css/dark.css">
    <link rel="stylesheet" type="text/css" id="theme"
        href="css/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" id="theme"
        href="css/fullcalendar/fullcalendar.print.min.css" media="print">
    <link rel="stylesheet" type="text/css" id="theme" href="css/jquery-ui.css">

    <!-- EOF CSS INCLUDE -->

    <!-- START PRELOADS -->
</head>
<body><audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS -->

    <!-- START SCRIPTS -->
    
    <link rel="stylesheet" type="text/css" href="css/dataTables.checkboxes.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css">

    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->

    
        </div><div id="topcontrol" title="Scroll Back to Top"
            style="position: fixed; bottom: 10px; right: 10px; opacity: 0; cursor: pointer;"><!-- TO TOP --><div
                class="to-top"><span
                    class="fa fa-angle-up"></span></div><!-- END TO TOP --></div></body></html>
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