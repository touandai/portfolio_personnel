
<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/mail.php';


// ============================================================
// 1. Vérifier que le formulaire a bien été envoyé en POST
// ============================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/contact.html');
    exit;
}


// ============================================================
// 2. Récupérer et nettoyer les données
// ============================================================

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$project = trim($_POST['project'] ?? '');
$message = trim($_POST['message'] ?? '');


// Supprimer les balises HTML éventuelles
$name = strip_tags($name);
$project = strip_tags($project);
$message = strip_tags($message);


// ============================================================
// 3. Vérifier les champs obligatoires
// ============================================================

if ($name === '' || $email === '' || $project === '' || $message === '') {
    header('Location: ../pages/contact.html?error=1');
    exit;
}


// ============================================================
// 4. Vérifier l'adresse email
// ============================================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/contact.html?error=2');
    exit;
}


// ============================================================
// 5. Limiter la taille des données
// ============================================================

if (mb_strlen($name) > 100) {
    header('Location: ../pages/contact.html?error=4');
    exit;
}

if (mb_strlen($email) > 255) {
    header('Location: ../pages/contact.html?error=4');
    exit;
}

if (mb_strlen($project) > 150) {
    header('Location: ../pages/contact.html?error=4');
    exit;
}

if (mb_strlen($message) > 5000) {
    header('Location: ../pages/contact.html?error=4');
    exit;
}


// ============================================================
// 6. Création de PHPMailer
// ============================================================

$mail = new PHPMailer(true);


try {

    // ========================================================
    // 7. Configuration SMTP Gmail
    // ========================================================

    $mail->isSMTP();

    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;

    $mail->Username = $config['username'];
    $mail->Password = $config['password'];

    $mail->SMTPSecure = $config['encryption'];
    $mail->Port = $config['port'];

    $mail->CharSet = 'UTF-8';
    $mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];


    // ========================================================
    // 8. Expéditeur
    // ========================================================

    $mail->setFrom(
        $config['username'],
        $config['from_name']
    );


    // ========================================================
    // 9. Destinataire
    // ========================================================

    $mail->addAddress(
        $config['to_email'],
        $config['to_name']
    );


    // ========================================================
    // 10. Permettre de répondre directement au visiteur
    // ========================================================

    $mail->addReplyTo($email, $name);


    // ========================================================
    // 11. Sujet
    // ========================================================

    $mail->Subject = 'Nouvelle demande de projet - ROMARIC';


    // ========================================================
    // 12. Contenu HTML
    // ========================================================

    $mail->isHTML(true);

    $mail->Body = '
        <h2>Nouvelle demande de contact - ROMARIC</h2>

        <p>
            <strong>Nom :</strong><br>
            ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '
        </p>

        <p>
            <strong>Email :</strong><br>
            ' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '
        </p>

        <p>
            <strong>Projet :</strong><br>
            ' . htmlspecialchars($project, ENT_QUOTES, 'UTF-8') . '
        </p>

        <p>
            <strong>Message :</strong><br>
            ' . nl2br(
                htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
            ) . '
        </p>
    ';


    // ========================================================
    // 13. Version texte
    // ========================================================

    $mail->AltBody =
        "Nouvelle demande de contact - ROMARIC\n\n" .
        "Nom : {$name}\n" .
        "Email : {$email}\n" .
        "Projet : {$project}\n\n" .
        "Message :\n" .
        "-------------------------------------\n" .
        "{$message}\n";


    // ========================================================
    // 14. Envoi
    // ========================================================

    $mail->send();


    // ========================================================
    // 15. Succès → retour sur contact.html
    // ========================================================

    header('Location: ../pages/contact.html?success=1');
    exit;


} catch (Exception $e) {
    // ========================================================
    // 16. Erreur PHPMailer
    // ========================================================
    error_log(
        'Erreur PHPMailer : ' . $mail->ErrorInfo
    );

    // On retourne sur la page contact
    header('Location: ../pages/contact.html?error=3');
    exit;
}

