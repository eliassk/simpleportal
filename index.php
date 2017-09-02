<html>
<head>
    <!-- jQuery -->
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous">
    </script>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Własne style CSS -->
    <link rel="stylesheet" href="style.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Strona logowania</title>
</head>
<body>
<?php
//Rozpoczynamy sessje
session_start();
// Sprawdzamy czy użytkownik jest zalogowany
$islogged = !empty($_SESSION['user']);

// Sprawdzamy czy użytkownik nie wcisnął przyciska "Wyloguj", sprawdzamy czy istnieje parametr logout w url
if (isset($_GET['logout'])) {
    // Jeśli tak to usuwamy użytkownika z sesji
    unset($_SESSION['user']);
    // Ładujemy na nowo plik index.php
    header('Location: index.php');
}
// Wyświetlamy treść dostępną do zalogowanych użytkowników
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

// Jeśli jest zalogowany wyświetlamy menu z przyciskiem dodaj nowy oraz wyloguj
if ($islogged) {
        echo ' 
             <nav class="navbar navbar-default">
              <div class="container-fluid">
                <div class="navbar-header">
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="index.php">Strona główna</a></li>
                            <li><a href="newpost.php">Dodaj nowy wpis</a></li>
                            <li><a href="index.php?logout=1">Wyloguj</a></li>
                        </ul>
                    </div>
                </div>
              </div>
            </nav>';
// Inaczej wyświetlamy przycisk zaloguj
    } else {
        echo ' 
            <nav class="navbar navbar-default">
             <div class="container-fluid">
               <div class="navbar-header">
                   <div class="collapse navbar-collapse">
                       <ul class="nav navbar-nav">
                           <li><a href="index.php">Strona główna</a></li>
                           <li><a href="login.php">Zaloguj</a></li>
                       </ul>
                   </div>
               </div>
             </div>
           </nav>';
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

$can_edit = false;
// Pobieramy wszystkie wpisy
$result = $mysqli->query("SELECT posts.id, posts.title, posts.content, posts.authorid, users.first_name, users.last_name FROM posts INNER JOIN users ON posts.authorid=users.id;");
$posts = $result->fetch_all();
foreach ($posts as $post) {
    $title = $post ? $post[1] : '';
    $content = $post ? $post[2] : '';
    if (isset($post[3]) && isset($post[4]) && isset($post[5])) {
        $author = $post[4] . ' ' . $post[5];
        if (isset($user)) {
            if ($post[3] === $user['id']) {
                $can_edit = true;
            }
        };
    } else {
        $author = '';
    }

    // Jeśli jest autorem dodajemy również przycisk do edycji i usunięcia
    if ($can_edit) {
        $buttons = '  <div class="pull-right">
                                <a href="newpost.php?id='.$post[0].'">
                                     <button type="button" class="btn btn-primary">
                                        <span class="btn-small btn-google">Edytuj<i class="fa fa-chevron-right"></i></span>
                                     </button>	
                                 </a>	
                                 <a href="deletepost.php?id='.$post[0].'"			 
                                     <button type="button" class="btn btn-danger">
                                        <span class="btn-small btn-google">Usuń<i class="fa fa-chevron-right"></i></span>
                                     </button>
                                 </a>
    						 </div>';
    } else {
        $buttons = '';
    };
// Wyświetlamy wpis
    echo '
        <div class="container">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-md-12">
                                    <h2>'.$title.'</h2>
                                     <i>przez '.$author.'</i>
                                    <hr>
                                    <p>'.$content.'</p>
                                    '.$buttons.'
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    ';

    $can_edit = false;
}

?>
</div>
</body>
</html>