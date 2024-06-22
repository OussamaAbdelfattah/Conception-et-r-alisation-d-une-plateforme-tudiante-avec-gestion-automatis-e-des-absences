<?php
session_start();
// var_dump($_SESSION['nomprenom']);
$data = [
    'Administration des Services 1' => ['total_hours' => 45, 'absent_hours' => 8],
    'Algorithmique' => ['total_hours' => 60, 'absent_hours' => 12],
    'Algorithmique Avancée' => ['total_hours' => 50, 'absent_hours' => 7],
    'Analyse Numérique' => ['total_hours' => 35, 'absent_hours' => 5],
    'Architecture et Conception des SI' => ['total_hours' => 40, 'absent_hours' => 6],
    'Automatique et Systèmes Dynamiques' => ['total_hours' => 30, 'absent_hours' => 4],
    'Bases de données' => ['total_hours' => 55, 'absent_hours' => 10],
    'Bases de Données Avancées' => ['total_hours' => 45, 'absent_hours' => 9],
    'Programmation Orientée Objet et Développement Web 2' => ['total_hours' => 50, 'absent_hours' => 7],
    'Électronique Analogique' => ['total_hours' => 70, 'absent_hours' => 15],
    'Réseaux de Communication' => ['total_hours' => 65, 'absent_hours' => 13]
];

function calculateAbsencePercentages($data) {
    $percentages = [];

    foreach ($data as $module => $hours) {
        $total_hours = $hours['total_hours'];
        $absent_hours = $hours['absent_hours'];
        $percentage = ($absent_hours / $total_hours) * 100;
        $percentages[$module] = round($percentage, 2); // Arrondi à 2 décimales
    }

    return $percentages;
}

$absence_percentages = calculateAbsencePercentages($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pourcentage d'Absences par Module</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            /* width: 100%; */
            height: 100%;
            /* display:; */
            justify-content: center;
            align-items: center;
            background-color: black; /* Fond noir */
            color: white; /* Texte blanc */
            font-family: Arial, sans-serif;
        }
        /* h1{
            text-align: center;
        } */
        canvas {
            display: block;
            width: 500px;
            height: 500px;
            /* padding-top: 50px; */
        }
    </style>
</head>
<body>
    <center>STATISTIQUES DE <?php echo $_SESSION['nomprenom'];?> (POURCENTAGE D'ABSENCES PAR MODULES)  : </center>
    <canvas id="absenceChart"></canvas>
    <script>
        // Données pour les étiquettes et les pourcentages
        var modules = <?php echo json_encode(array_keys($absence_percentages)); ?>;
        var percentages = <?php echo json_encode(array_values($absence_percentages)); ?>;
        
        // Couleurs prédéfinies pour les modules
        var moduleColors = [
            'rgba(255, 99, 132, 0.5)', // Rouge
            'rgba(54, 162, 235, 0.5)', // Bleu
            'rgba(255, 206, 86, 0.5)',  // Jaune
            'rgba(75, 192, 192, 0.5)',  // Vert
            'rgba(153, 102, 255, 0.5)', // Violet
            'rgba(255, 159, 64, 0.5)',  // Orange
            'rgba(201, 203, 207, 0.5)', // Gris
            'rgba(255, 0, 0, 0.5)',     // Rouge (foncé)
            'rgba(0, 255, 0, 0.5)',     // Vert (clair)
            'rgba(0, 0, 255, 0.5)'      // Bleu (clair)
        ];

        // Générer les données pour le graphique
        var data = {
            labels: modules,
            datasets: [{
                label: 'Pourcentage d\'absence',
                data: percentages,
                backgroundColor: moduleColors,
                borderWidth: 1
            }]
        };

        var ctx = document.getElementById('absenceChart').getContext('2d');
        var absenceChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed && context.parsed.y !== undefined) {
                                    label += context.parsed.y + '%';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
