<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    var_dump($_POST);
    $id_personne = -1;
    $date = date('Y-m-d');
    // $heure = date('H:i:s');
    $heure = strtotime('09:30:01');
    $heure_debut_seance = strtotime('09:00:00');
    $heure_retard_seance = strtotime('09:15:00');
    $heure_absence_seance = strtotime('09:30:00');
    echo "date : " . $date . ", heure : " . $heure;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uid = $_POST['uid'];
        $nom = $_POST['nom'];
        // Connect to your database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "App_isga_direct";

        // Create connection
        $conn = new mysqli($servername, $username, '', $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Insert UID into database
        $sql = "SELECT id_personne FROM carteRFID WHERE id_RFID='$uid';";
        $result = $conn->query($sql);
        if (!$result) {
            die('Erreur d\'exécution de la requête : ' . $conn->error);
        }else{
            $row = $result->fetch_assoc();
            $id_personne = $row['id_personne'];
            
            
            //present : 
            if($heure < $heure_retard_seance && $heure > $heure_debut_seance){
                echo "present";
                $sql2 = "INSERT INTO Presence (date_presence, est_present, id_seance, id_personne)
                        VALUES('$date', 1, 12, $id_personne);";
                $result2 = $conn->query($sql2);
                if (!$result2) {
                    die('Erreur d\'exécution de la requête : ' . $conn->error);
                }else{
                    var_dump($result2);
                }
            }
            //en retard
            if($heure < $heure_absence_seance && $heure > $heure_retard_seance){
                echo "en retard";
                $sql3 = "INSERT INTO Presence (date_presence, est_present, id_seance, id_personne, en_retard)
                        VALUES('$date', 1, 12, $id_personne, 1);";
                $result3 = $conn->query($sql3);
                if (!$result3) {
                    die('Erreur d\'exécution de la requête : ' . $conn->error);
                }else{
                    var_dump($result3);
                }
            }
            //absent
            if($heure > $heure_absence_seance){
                echo "absent";
                $sql4 = "INSERT INTO Presence (date_presence, est_present, id_seance, id_personne)
                        VALUES('$date', 0, 15, $id_personne);";
                $result4 = $conn->query($sql4);
                if (!$result4) {
                    die('Erreur d\'exécution de la requête : ' . $conn->error);
                }else{
                    var_dump($result4);
                }
            }
        }
        $conn->close();
    }
?>

