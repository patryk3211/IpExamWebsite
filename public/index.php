<?php
// Check if the test is active
require __DIR__ . '/../lib/config.php';
if ($GLOBALS['config']['active'] != 1) {
  echo "Test jest nieaktywny";
  die();
}

if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['number'])) {
  if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['number'])) {
    $error = "Podaj wszystkie dane";
  } else {
    session_start();
    $_SESSION['solving'] = true;
    $_SESSION['start_time'] = time();
    $_SESSION['current_address_index'] = 0;
    $_SESSION['report'] = [];
    $_SESSION['solver'] = [
      'firstname' => $_POST['fname'],
      'lastname' => $_POST['lname'],
      'number' => $_POST['number'],
    ];
    $_SESSION['score'] = [];

    header('Location: question.php');
  }
}

$prettyTimeLimit = "";
$timeLimitSeconds = time_limit();
if (floor($timeLimitSeconds / 3600) > 0) {
  $hours = floor($timeLimitSeconds / 3600);
  if ($hours == 1)
    $prettyTimeLimit .= "godzinę ";
  else if ($hours < 5)
    $prettyTimeLimit .= "$hours godziny ";
  else
    $prettyTimeLimit .= "$hours godzin ";
  $timeLimitSeconds -= $hours * 3600;
}

if (floor($timeLimitSeconds / 60) > 0) {
  $minutes = floor($timeLimitSeconds / 60);
  if ($minutes == 1)
    $prettyTimeLimit .= "minutę ";
  else if ($minutes < 5)
    $prettyTimeLimit .= "$minutes minuty ";
  else
    $prettyTimeLimit .= "$minutes minut ";
  $timeLimitSeconds -= $minutes * 60;
}

if ($timeLimitSeconds > 0) {
  if ($timeLimitSeconds == 1)
    $prettyTimeLimit .= "sekundę";
  else if ($timeLimitSeconds < 5)
    $prettyTimeLimit .= "$timeLimitSeconds sekundy";
  else
    $prettyTimeLimit .= "$timeLimitSeconds sekund";
}

$prettyTimeLimit = trim($prettyTimeLimit);
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <title>Obliczanie adresów IP</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="w3.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header class="w3-container w3-teal w3-margin-bottom">
    <h2>Obliczanie adresów IP</h2>
  </header>
  <div class="w3-table w3-auto">
    <div class="w3-row w3-display-container">
      <div class="w3-container w3-half w3-margin-bottom">
        <form class="w3-container w3-card-4" method="post" class="form-vertical">
          <p>
            <input class="w3-input" type="text" name="fname" />
            <label class="label">Imię</label>
          </p>
          <p>
            <input class="w3-input" type="text" name="lname" />
            <label class="label">Nazwisko</label>
          </p>
          <p>
            <input class="w3-input noarrows studentnumber" type="number" name="number" />
            <label class="label">Numer z dziennika</label>
          </p>
          <p>
            <input class="w3-button w3-teal" type="submit" value="Rozpocznij test" />
          </p>
          <?php
          if (isset($error)) {
            echo <<<HTML
        <p class="w3-text-red">
          $error
        </p>
        HTML;
          }
          ?>
        </form>
      </div>
      <div class="w3-container w3-half">
        <div class="w3-container w3-card-4">
          <h3 class="w3-margin-small">Przed rozpoczęciem testu</h3>
          <div class="w3-text-dark-grey w3-margin-bottom">
            Udzielasz odpowiedzi wpisując liczby w prawidłowe pola,
            po czym klikasz przycisk wyślij. Po wysłaniu odpowiedzi
            nie możesz powrócić do pytania.
          </div>
          <div class="w3-text-dark-grey w3-margin-bottom">
            Na rozwiązanie testu masz <?= $prettyTimeLimit ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
