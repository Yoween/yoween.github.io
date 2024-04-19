<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialisez le compteur d'essais si nécessaire
    if (!isset($_SESSION['essais'])) {
        $_SESSION['essais'] = 0;
    }

    // Incrémentez le compteur d'essais
    $_SESSION['essais']++;

    if ($_POST['code_pays'] === $_SESSION['code_pays_choisi']) {
        echo 'Correct!';
        // Réinitialisez le compteur d'essais
        $_SESSION['essais'] = 0;
    } else {
        if ($_SESSION['essais'] >= 3) {
            echo 'Incorrect, veuillez réessayer.';
            // Réinitialisez le compteur d'essais
            $_SESSION['essais'] = 0;
        } else {
            echo 'Incorrect, vous avez encore ' . (3 - $_SESSION['essais']) . ' essais.';
        }
    }
}
?>