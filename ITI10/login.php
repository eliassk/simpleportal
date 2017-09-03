<html>
<head>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Strona logowania</title>
</head>
<body>
<div id="login-form">
<h1>Zaloguj się</h1>
<!-- Formularz logowania -->
<form class="login-form" action="login.php" method="post">
    <div class="form-group">
        <label for="usernameInput">Login:</label>
        <input class="form-control" id="usernameInput" type="text" name="username" /><br />
    </div>
    <div class="form-group">
        <label for="passwordInput">Hasło:</label>
        <input class="form-control" type="password" name="password" /><br />
    </div>
    <input type="submit" name="submit" class="btn btn-default" value="Login" />
</form>
<p>Nie masz konta? <a href="register.php">Zarejstruj się</a> </p>
<?php
session_start();
// Sprawdzamy czy formularz został wysłany
if (isset($_POST['submit'])){

    // Ładujemy plik z danymi dostępowymi do bazy danych
    require_once __DIR__ . "\config.php";

    // Tworzymy nowe połączenie z bazą danych
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Sprawdzamy czy nie ma jakiś problemów w połączeniu
    if ($mysqli->connect_error) {
        // Jeśli wystąpił jakiś problem to go wyświetlamy na ekrenie
        echo "<p>Wystąpił błąd podczas lączenia z bazą danych: {$mysqli->connect_error}</p>";
        exit();
    }

    // Pobieramy nazwe użytkownika i hasło do zmiennej
    $username = $_POST['username'];
    // hashujemy hasło, dla bespieczeństwa przechowywuje się go w bazie danych zakryptowane przez algorytm sha1
    $password = sha1($_POST['password']);

    // Sprawdzamy czy w bazie istnieje użytkownik z loginem i hasłem takim jaki został podany w formularzu
    $sql = "SELECT id, username, first_name, last_name, email from users WHERE username LIKE '{$username}' AND password LIKE '{$password}' LIMIT 1";
    $result = $mysqli->query($sql);

    // Jeżeli zapytanie zwróciło nam 0 rekordów, to znaczy że w bazie danych nie ma użytkownika o podanym loginie i haśle
    if ($result->num_rows === 0) {
        // Wyświetlamy wiadomośc na ekranie
        echo '<p class="red">Zły login/hasło!</p>';
    } else {
        // A jeżeli zapytanie zwróciło nam użytkownika, to znaczy że podany login i hasło pasują do użytkownika w bazie danych
        // Zapisujemy id użytkownika do sesji co pozwoli nam potem sprawdzić czy użytkownik jest zalogowany
        $user = $result->fetch_array();
        $_SESSION['user'] = $user;
        // i na koniec przekierowywujemy użytkownika z powrotem do pliku index.php
        header('Location: index.php');
    }
}
?>
</div>
</body>
</html>