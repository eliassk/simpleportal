<?php
//Rozpoczynamy sessje
session_start();
?>
<html>
<head>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Własne style CSS -->
    <link rel="stylesheet" href="style.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Formularz</title>
</head>
<body>
<?php
// Sprawdzamy czy użytkownik jest zalogowany
if (!empty($_SESSION['user'])) {
    // Sprawdzamy czy użytkownik nie wcisnął przyciska "Wyloguj", sprawdzamy czy istnieje parametr logout w url
    if (isset($_GET['logout'])) {
        // Jeśli tak to usuwamy użytkownika z sesji
        unset($_SESSION['user']);
        // Ładujemy na nowo plik index.php
        header('Location: index.php');
    }

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
    if (isset($_POST['submit'])) {
        // Pobieramy pobieramy dane z formularza do zmienych
        $postid	 = $_POST['postid'];
        $title	 = $_POST['title'];
        $content = $_POST['content'];
        $authorid = $user['id'];

        if (!empty($postid)) {
            // Jeżeli mamy podane id posta (edytujemy wpis) to napisujemy w bazie
            $sql = "UPDATE posts SET `title`='{$title}', `content`='{$content}' WHERE `id`='{$postid}';";
        } else {
            // Dadajemy wpis do bazy
            $sql = "INSERT  INTO `posts` (`id`, `title`, `content`, `authorid`)
            VALUES (NULL, '{$title}', '{$content}', '{$authorid}')";
        }

        if ($mysqli->query($sql)) {
            // Jeżeli nie było żadnych błedów przekierowywujemy użytkownika do strony głównej
            header('Location: index.php');
        } else {
            // Jeśli coś poszło nie tak i wystąpiły jakieś błędy wyświetlamy je na ekranie
            echo "<p class='red'>Wystąpił błąd podczas dodawania wpisu: {$mysqli->error}</p>";
            exit();
        }
    } else {
        // Jeśli mamy podane id (edycja) pobieramy dane wpisu
        if (isset($_GET['id'])) {
            $postid = $_GET['id'];
            $result = $mysqli->query("SELECT * FROM posts WHERE id='{$postid}' LIMIT 1");
            $post = $result->fetch_array();

            $title = $post['title'];
            $content = $post['content'];
        } else {
            $postid = $title = $content = '';
        }
        // Wyświetlamy menu i formularz, wypełniamy danymi jeśli edytujemy wpis
        echo '
 <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="form.php">Dodaj nowy wpis</a></li>
                <li><a href="index.php?logout=1">Wyloguj</a></li>
            </ul>
        </div>
    </div>
  </div>
</nav>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="submit-form container" action="form.php" method="post">
                <input class="form-control" name="postid" type="hidden" value="'.$postid.'"/>
                <div class="form-group col-md-11">
                    <label for="title">Nagłówek:</label>
                    <input class="form-control" id="title" type="text" name="title" value="'.$title.'"/>
                </div>
                <div class="form-group col-md-11">
                    <label for="content">Tekst:</label>
                    <textarea class="form-control" name="content">'.$content.'</textarea>
                </div>
                <div class="col-md-10">
                    <input type="submit" name="submit" class="btn btn-default" value="Zapisz" />
                </div>
            </form>
        </div>
    </div>
</div>
        ';
    }
} else {
    // Jeżeli użytkownik nie jest zalogowany wysyłamy go do login.php
    require_once __DIR__. '\login.php';
}
?>
</body>
</html>
