<html>
<head>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- Własne style CSS -->
    <link rel="stylesheet" href="style.css" />

    <title>Strona logowania</title>
</head>
<body>
<?php
//Rozpoczynamy sessje
session_start();
// Sprawdzamy czy użytkownik jest zalogowany
if (!empty($_SESSION['user'])) {
    // Sprawdzamy czy użytkownik nie wcisnął przyciska "Wyloguj", sprawdzamy czy istnieje parametr logout w url
    if (isset($_GET['logout'])) {
        // Jeśli tak to usuwamy użytkownika z sesji
        unset($_SESSION['user']);
        // Ładujemy na nowo plik index.php
        header('Location: index.php');
    }
    // Wyświetlamy treść dostępną do zalogowanych użytkowników
    $user = $_SESSION['user'];
    echo ' 
        <div id="loggedin">
            Jesteś zalogowany jako <b>'.$user['first_name'].' '.$user['last_name'].' </b>
            <br /><br />
            <a class="btn btn-default" href="index.php?logout=1">Wyloguj się</a>
        </div>
        ';
} else {
    // Jeżeli użytkownik nie jest zalogowany wysyłamy go do login.php
    require_once __DIR__. '\login.php';
}
?>
</body>
</html>