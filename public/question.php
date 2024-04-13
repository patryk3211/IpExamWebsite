<?php
require __DIR__ . '/../lib/config.php';

session_start();
if (!isset($_SESSION['solving']) || !$_SESSION['solving']) {
  header('Location: index.php');
  die();
}

$duration = time() - $_SESSION['start_time'];
$timeLeft = time_limit() - $duration;
?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <title>Obliczanie adresów IP</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="w3.css">
  <link rel="stylesheet" href="style.css">
  <script>
    var timeLeft = <?= $timeLeft ?>;
    document.addEventListener('DOMContentLoaded', () => {
      setInterval(() => {
        --timeLeft;
        var minutes = Math.floor(timeLeft / 60);
        var seconds = timeLeft % 60;
        var timeText = "";
        if (minutes < 10)
          timeText += "0" + minutes.toString();
        else
          timeText += minutes.toString();
        timeText += ":";
        if (seconds < 10)
          timeText += "0" + seconds.toString();
        else
          timeText += seconds.toString();
        document.getElementById('timer').textContent = timeText;
        if (timeLeft <= 0)
          document.getElementById('ipfullform').submit();
      }, 1000);
    });
  </script>
</head>

<body>
  <header class="w3-container w3-teal w3-margin-bottom">
    <h2>Obliczanie adresów IP</h2>
  </header>
  <div class="w3-container">
    <div class="w3-row w3-auto">
      <div class="w3-threequarter">
        <div class="w3-container w3-margin-right w3-card-4 w3-margin-bottom">
          <h3>Pytanie</h3>
          <p>
            Oblicz maskę binarną, dziesiętną, adres sieci oraz adres rozgłoszeniowy dla podanego adresu
          </p>
          <p class="w3-xxlarge w3-border-bottom question-ip w3-margin-bottom w3-margin-top w3-text-grey">
            <?= $GLOBALS['config']['addresses'][$_SESSION['current_address_index']] ?>
          </p>
        </div>
      </div>
      <div class="w3-quarter">
        <div class="w3-container w3-card-4 w3-margin-bottom w3-center">
          <h3>Pozostały czas</h3>
          <h2 id="timer"><?= date("i:s", $timeLeft) ?></h2>
        </div>
      </div>
    </div>
    <form class="w3-container w3-auto w3-card-4" action="send.php" method="post" id="ipfullform">
      <header>
        <h3>Odpowiedź</h3>
      </header>

      <div class="input-block binmask">
        <input type="text" name="maskbin0" />
        <span class="input-text-deco">.</span>
        <input type="text" name="maskbin1" />
        <span class="input-text-deco">.</span>
        <input type="text" name="maskbin2" />
        <span class="input-text-deco">.</span>
        <input type="text" name="maskbin3" />
      </div>
      <label class="label">Maska binarnie</label>

      <div class="input-block decaddr">
        <input type="text" name="maskdec0" />
        <span class="input-text-deco">.</span>
        <input type="text" name="maskdec1" />
        <span class="input-text-deco">.</span>
        <input type="text" name="maskdec2" />
        <span class="input-text-deco">.</span>
        <input type="text" name="maskdec3" />
      </div>
      <label class="label">Maska dziesiętnie</label>

      <div class="input-block decaddr">
        <input type="text" name="net0" />
        <span class="input-text-deco">.</span>
        <input type="text" name="net1" />
        <span class="input-text-deco">.</span>
        <input type="text" name="net2" />
        <span class="input-text-deco">.</span>
        <input type="text" name="net3" />
      </div>
      <label class="label">Adres sieci</label>

      <div class="input-block decaddr">
        <input type="text" name="broad0" />
        <span class="input-text-deco">.</span>
        <input type="text" name="broad1" />
        <span class="input-text-deco">.</span>
        <input type="text" name="broad2" />
        <span class="input-text-deco">.</span>
        <input type="text" name="broad3" />
      </div>
      <label class="label">Adres rozgłoszeniowy</label>

      <p>
        <input class="w3-button w3-teal" type="submit" value="Wyślij" />
      </p>
    </form>
  </div>
</body>

</html>
