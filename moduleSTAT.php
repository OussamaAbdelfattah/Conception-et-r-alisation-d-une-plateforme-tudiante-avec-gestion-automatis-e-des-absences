<?php
    session_start();
    $lignesHTMl = '';

    if(isset($_GET)){
        // var_dump($_GET);
        if($_GET['promo'] == '1AP'){
            $lignesHTMl = '1AP';
            $data = [
                'Algorithmique' => ['total_hours' => 45, 'absent_hours' => 8],
                'Communication Technique' => ['total_hours' => 60, 'absent_hours' => 12],
                'Electromagnétisme' => ['total_hours' => 50, 'absent_hours' => 7],
                'Ingénierie des Systèmes' => ['total_hours' => 35, 'absent_hours' => 5],
                'Introduction à la Mécanique des Structures' => ['total_hours' => 40, 'absent_hours' => 6],
                'Introduction à la Thermodynamique' => ['total_hours' => 30, 'absent_hours' => 4],
                'Langage C' => ['total_hours' => 55, 'absent_hours' => 10],
                'Mathématiques pour l\'Ingénieur' => ['total_hours' => 45, 'absent_hours' => 9],
                'Électronique Analogique' => ['total_hours' => 50, 'absent_hours' => 7],
            ];
        }
        if($_GET['promo'] == '2AP'){
            $lignesHTMl = '2AP';
            $data = [
                'Analyse Numérique' => ['total_hours' => 45, 'absent_hours' => 8],
                'Automatique et Systèmes Dynamiques' => ['total_hours' => 60, 'absent_hours' => 12],
                'Droit de l\'Ingénierie' => ['total_hours' => 50, 'absent_hours' => 7],
                'Economie et Gestion des Projets ' => ['total_hours' => 35, 'absent_hours' => 5],
                'Electricité et Magnétisme' => ['total_hours' => 40, 'absent_hours' => 6],
                'Electronique de Puissance' => ['total_hours' => 30, 'absent_hours' => 4],
                'Génie des Procédés' => ['total_hours' => 55, 'absent_hours' => 10],
                'Ingénierie des Matériaux Avancés' => ['total_hours' => 45, 'absent_hours' => 9],
                'Mécanique des Fluides' => ['total_hours' => 50, 'absent_hours' => 7],
            ];
        }
        if($_GET['promo'] == '1CI'){
            $lignesHTMl = '1CI';
            $data = [
                'Administration des Services 1' => ['total_hours' => 45, 'absent_hours' => 8],
                'Administration des Services 2' => ['total_hours' => 60, 'absent_hours' => 12],
                'Architecture et Conception des SI' => ['total_hours' => 50, 'absent_hours' => 7],
                'Atelier d ingénierie informatique' => ['total_hours' => 35, 'absent_hours' => 5],
                'Bases de données' => ['total_hours' => 40, 'absent_hours' => 6],
                'Communication et langues 1' => ['total_hours' => 30, 'absent_hours' => 4],
                'Communication et langues 2' => ['total_hours' => 55, 'absent_hours' => 10],
                'Management 1' => ['total_hours' => 45, 'absent_hours' => 9],
                'Mathématiques pour ingénieur' => ['total_hours' => 50, 'absent_hours' => 7],
            ];
        }
        if($_GET['promo'] == '2CI'){
            $lignesHTMl = '2CI';
            $data = [
                'Algorithmique Avancée' => ['total_hours' => 45, 'absent_hours' => 8],
                'Bases de Données Avancées' => ['total_hours' => 60, 'absent_hours' => 12],
                'Big Data Analytics' => ['total_hours' => 50, 'absent_hours' => 7],
                'Cloud Computing' => ['total_hours' => 35, 'absent_hours' => 5],
                'Cryptographie' => ['total_hours' => 40, 'absent_hours' => 6],
                'Développement Web Avancé' => ['total_hours' => 30, 'absent_hours' => 4],
                'Ingénierie des Logiciels' => ['total_hours' => 55, 'absent_hours' => 10],
                'Intelligence Artificielle' => ['total_hours' => 45, 'absent_hours' => 9],
                'Réseaux Informatiques' => ['total_hours' => 50, 'absent_hours' => 7],
            ];
        }
    }
    



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
            background-color: white; /* Fond noir */
            color: black; /* Texte blanc */
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
    <!-- <h1>STATISTIQUES DE TOUS LES MODULES</h1> -->
    <center>STATISTIQUES DES MODULES  : <?php echo $lignesHTMl;?></center>
    <canvas id="absenceChart"></canvas>
    <script>
        // Les données PHP pour les étiquettes et les pourcentages
        var labels = <?php echo json_encode(array_keys($absence_percentages)); ?>;
        var data = <?php echo json_encode(array_values($absence_percentages)); ?>;
        var backgroundColors = [
            'rgba(255, 0, 0, 0.5)', /* Rouge */
            'rgba(0, 100, 0, 0.5)', /* Noir */
            'rgba(255, 255, 255, 0.5)', /* Blanc */
            'rgba(128, 0, 0, 0.5)', /* Rouge foncé */
            'rgba(0, 128, 0, 0.5)', /* Vert */
            'rgba(0, 0, 128, 0.5)', /* Bleu */
            'rgba(128, 128, 0, 0.5)', /* Jaune */
            'rgba(128, 0, 128, 0.5)', /* Violet */
            'rgba(0, 128, 128, 0.5)', /* Cyan */
            'rgba(255, 128, 0, 0.5)', /* Orange */
            'rgba(128, 255, 0, 0.5)' /* Jaune-vert */
        ];
        var borderColors = [
            'rgba(255, 0, 0, 1)',
            'rgba(0, 100, 0, 1)',
            'rgba(255, 255, 255, 1)',
            'rgba(128, 0, 0, 1)',
            'rgba(0, 128, 0, 1)',
            'rgba(0, 0, 128, 1)',
            'rgba(128, 128, 0, 1)',
            'rgba(128, 0, 128, 1)',
            'rgba(0, 128, 128, 1)',
            'rgba(255, 128, 0, 1)',
            'rgba(128, 255, 0, 1)'
        ];

        var ctx = document.getElementById('absenceChart').getContext('2d');
        var absenceChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pourcentage d\'absences',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += Math.round(context.raw * 100) / 100 + '%';
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
