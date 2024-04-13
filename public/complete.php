<?php
require __DIR__.'/../lib/config.php';
require __DIR__.'/../lib/csv.php';
require __DIR__.'/../lib/grading.php';

session_start();
if(!isset($_SESSION['solving'])) {
  header('Location: index.php');
  die();
}

$scores = $_SESSION['score'];
$totalScore = 0;
$maxScore = max_score();
foreach($scores as $score) {
  $totalScore += $score;
}

$grade = grade($totalScore);
$percent = $totalScore/$maxScore;

if($_SESSION['solving']) {
  $_SESSION['solving'] = false;
  $_SESSION['end_time'] = time();

  // Save result
  if(!is_dir(__DIR__.'/../reports')) {
    if(!mkdir(__DIR__.'/../reports')) {
      http_response_code(500);
      echo "500 - Internal Server Error";
      die();
    }
  }

  $reportName = __DIR__.'/../reports/'.generate_report_filename($_SESSION['solver']);
  save_report($reportName, $_SESSION['report']);
  save_result($_SESSION['solver'], $_SESSION['start_time'], $_SESSION['end_time'], $scores, $totalScore, $grade);
}

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
    <div class="w3-container w3-auto w3-card-4">
      <div class="w3-display-container">
        <h2>Test ukończony</h2>
        <div class="w3-left">
          <div>Twój wynik to <?= $totalScore.'/'.$maxScore ?> punktów</div>
          <div class="w3-padding-small"></div>
          <div class="flex">
            <div class="scorebar w3-light-grey">
              <div class="fill w3-teal" style="width: <?= $percent * 100 ?>%;"></div>
            </div>
            <span>
              <?= $percent * 100 ?>%
            </span>
          </div>
          <div class="w3-margin-bottom"></div>
        </div>
        <div class="w3-display-topright">
          <div class="w3-border-bottom w3-jumbo w3-text-dark-grey" style="width: fit-content;">
            <?= $grade ?>
          </div>
          <label class="label">Ocena</label>
        </div>
      </div>
    </div>
  </body>
</html>

