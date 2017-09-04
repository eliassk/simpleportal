<html>
<head>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- Własne style CSS -->
    <link rel="stylesheet" href="style.css" />

    <title>Formularz rejestracji</title>
</head>
<body>
<div id="register-form">
    <h1>Rejstracja</h1>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <div class="form-group">
            <label for="usernameInput">Login:</label>
            <input id="usernameInput"  class="form-control" type="text" name="username" />
        </div>
        <div class="form-group">
            <label for="passwordInput">Hasło:</label>
            <input id="passwordInput" class="form-control" type="password" name="password" />
        </div>
        <div class="form-group">
            <label for="firstnameInput">Imię:</label>
            <input id="firstnameInput" class="form-control" type="text" name="first_name" />
        </div>
        <div class="form-group">
            <label for="lastnameInput">Nazwisko:</label>
            <input id="lastnameInput" class="form-control" type="text" name="last_name" />
        </div>
        <div class="form-group">
            <label for="emailInput">Email:</label>
            <input id="emailInput" class="form-control" type="text" name="email" />
        </div>
        <input type="submit" class="btn btn-default" name="submit" value="Register" />
    </form>

    <?php
    // Sprawdzamy czy użytkownik kliknął "Register"
    if (isset($_POST['submit'])) {
        // Jeśli tak to
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

        // Pobieramy pobieramy dane z formularza do zmienych
        $username	= $_POST['username'];
        $password	= sha1($_POST['password']);
        $first_name	= $_POST['first_name'];
        $last_name	= $_POST['last_name'];
        $email	= $_POST['email'];

        // Sprawdzamy czy hasło, login i email nie są puste
        if (empty($username) || empty($password) || empty($email)) {
            echo '<p class="red">Login, hasło i email nie mogą być puste</p>';

        } else {
            $isLoginTaken = false;
            $isEmailTaken = false;
            // Sprawdzamy czy użytkownik z taki loginem już istnieje
            $result = $mysqli->query("SELECT username from users WHERE username = '{$username}' LIMIT 1");
            if ($result->num_rows === 1) {
                $isLoginTaken = true;
            }
            // Sprawdzamy czy użytkownik z takim emailem już nie istnieje
            $result = $mysqli->query("SELECT email from users WHERE email = '{$email}' LIMIT 1");
            if ($result->num_rows === 1) {
                $isEmailTaken = true;
            }


            if ($isLoginTaken && $isEmailTaken) {
                // Wyświetlamy błąd że email i login już istnieją w bazie danych
                echo '<p class="red">Konto z podaną nazwą użytkownika i adresem email już istnieje!</p>';
            } elseif($isLoginTaken) {
                // Wyświetlamy błąd że login już istnieje w bazie danych
                echo '<p class="red">Nazwa użytkownika jest zajęta!</p>';
            } elseif ($isEmailTaken) {
                // Wyświetlamy błąd że email już istnieje w bazie danych
                echo '<p class="red">Konto z podanym adresem email już istnieje!</p>';
            } else {
                // Jeżeli dane przeszły przez nasza walidacje dodajemy użytkownika do bazy danych
                $sql = "INSERT  INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `email`) 
            VALUES (NULL, '{$username}', '{$password}', '{$first_name}', '{$last_name}', '{$email}')";

                if ($mysqli->query($sql)) {
                    // Jeżeli nie było żadnych błedów przekierowywujemy użytkownika do formularza logowania
                    header('Location: index.php');
                } else {
                    // Jeśli coś poszło nie tak i wystąpiły jakieś błędy wyświetlamy je na ekranie
                    echo "<p class='red' '>Wystąpił błąd podczas dodawania użytkownika: {$mysqli->connect_error}</p>";
                    exit();
                }
            }

        }
    }
    ?>
</div>
</body>
</html>