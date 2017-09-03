<?php
//Rozpoczynamy sessje
session_start();
// Sprawdzamy czy użytkownik jest zalogowany
if (!empty($_SESSION['user'])) {

    // Ładujemy plik z danymi dostępowymi do bazy danych
    require_once __DIR__ . '\config.php';

    // Tworzymy nowe połączenie z bazą danych
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Sprawdzamy czy nie ma jakiś problemów w połączeniu
    if ($mysqli->connect_error) {
        // Jeśli wystąpił jakiś problem to go wyświetlamy na ekrenie
        echo "<p class='red'>Wystąpił błąd podczas lączenia z bazą danych: {$mysqli->connect_error}</p>";
        exit();
    }

    $user = $_SESSION['user'];
    if (empty($user)) {
        // Jeżeli użytkownik nie jest zalogowany wysyłamy go do login.php
        require_once __DIR__ . '\login.php';
    } elseif (isset($_GET['id'])) {

        $sql = "DELETE FROM posts WHERE `id`='{$_GET['id']}';";

        // Usuwamy post z id podanym w url'u
        if ($mysqli->query($sql)) {
            // Jeżeli nie było żadnych błedów przekierowywujemy użytkownika do formularza logowania
            header('Location: index.php');
        } else {
            // Jeśli coś poszło nie tak i wystąpiły jakieś błędy wyświetlamy je na ekranie
            echo "<p class='red' '>Wystąpił błąd podczas dodawania wpisu: {$mysqli->connect_error}</p>";
            exit();
        }

    }

}
?>