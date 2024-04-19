<?php
session_start();

// Obtenez les noms de fichiers dans le dossier images
$fichiers = glob('images/*.png');

// Créez un tableau de codes de pays à partir des noms de fichiers
$codes_pays = array_map(function($fichier) {
    return pathinfo($fichier, PATHINFO_FILENAME);
}, $fichiers);

// Choisissez un code de pays au hasard
$code_pays_choisi = $codes_pays[array_rand($codes_pays)];

// Stockez le code de pays choisi dans la session pour vérification ultérieure
$_SESSION['code_pays_choisi'] = $code_pays_choisi;

// Retirez le pays choisi de la liste
$codes_pays = array_diff($codes_pays, [$code_pays_choisi]);

// Choisissez 15 autres pays au hasard
$autres_pays = array_rand(array_flip($codes_pays), 15);

// Ajoutez le pays choisi à la liste
$autres_pays[] = $code_pays_choisi;

// Mélangez la liste pour que l'ordre soit aléatoire
shuffle($autres_pays);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Captcha Drapeaux</title>
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            max-height: 80vh; /* ajustez cette valeur en fonction de vos besoins */
            overflow-y: auto; /* ajoute un espace de défilement si nécessaire */
        }
        img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Cliquez sur le drapeau de: <?php echo strtoupper($code_pays_choisi); ?></h1>
    <div class="grid">
        <?php foreach ($autres_pays as $pays) : ?>
            <img src="images/<?php echo $pays; ?>.png" alt="<?php echo strtoupper($pays); ?>" onclick="verifier('<?php echo $pays; ?>')">
        <?php endforeach; ?>
    </div>

    <script>
    function verifier(code_pays) {
        fetch('verifier.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'code_pays=' + code_pays,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            // Rafraîchissez la page si la réponse est correcte ou si l'utilisateur a épuisé ses essais
            if (data.includes('Correct!') || data.includes('veuillez réessayer')) {
                location.reload();
            }
        });
    }
    </script>
</body> 
</html>