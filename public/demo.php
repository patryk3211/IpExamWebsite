<!DOCTYPE html>
<html lang="pl">

<head>
  <title>Obliczanie adresów IP</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="w3.css">
  <link rel="stylesheet" href="style.css">
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.show-file').forEach(el => {
        el.addEventListener('click', () => {
          var code = el.querySelector('.code');
          if(code.classList.contains('w3-show')) {
            code.classList.remove('w3-show');
          } else {
            code.classList.add('w3-show');
          }
        })
      });
    });
    </script>
</head>

<body>
  <header class="w3-container w3-teal w3-margin-bottom">
    <h2>Demonstracja testu na obliczanie adresów IP</h2>
  </header>
  <div class="w3-container w3-auto w3-card w3-margin-bottom">
    <h2>Opis</h2>
      <div class="w3-margin-bottom">
        Strona testu na obliczanie adresów IP. Możliwe jest ustawianie
        czasu na test oraz definiowanie skali ocen. W pliku adresy.txt
        znajdują się adresy które pokażą się w teście. W pliku results.csv
        zapisuje się ogólny wynik każdego uczestnika. W folderze reports
        znajdują się bardzej szczegółowe wyniki wraz z udzielonymi odpowiedziamy.
        Zapisywany jest czas rozpoczęcia i zakończenia testu oraz IP z jakiego go
        rozwiązano. Kod do pobrania dostępny jest <a href="https://github.com/patryk3211/IpExamWebsite">tutaj</a>.
        Aby zainstalować tę stronę należy ustawić katalog public jako katalog główny w serwerze HTTP.
      </div>
      <a class="w3-button w3-teal w3-margin-bottom" href="index.php">Przetestuj</a>
  </div>
  <div class="w3-container w3-auto w3-card w3-margin-bottom">
    <h2>Plik configuracji - config.txt</h2>
    <div class="w3-code">
      <?= nl2br(file_get_contents(__DIR__ . '/../config.txt')) ?>
    </div>
  </div>
  <div class="w3-container w3-auto w3-card w3-margin-bottom">
    <h2>Plik adresów - adresy.txt</h2>
    <div class="w3-code">
      <?= nl2br(file_get_contents(__DIR__ . '/../adresy.txt')) ?>
    </div>
  </div>
  <div class="w3-container w3-auto w3-card w3-margin-bottom">
    <h2>Plik wyników - results.csv</h2>
    <div class="w3-code">
      <?= nl2br(file_get_contents(__DIR__ . '/../results.csv')) ?>
    </div>
  </div>
  <div class="w3-container w3-auto w3-card w3-margin-bottom">
    <h2>Folder z wynikami szegółowymi - reports</h2>
      <ul class="w3-ul w3-border w3-margin-bottom w3-hoverable">
        <?php
          $dirHandle = opendir(__DIR__.'/../reports');
          while($file = readdir($dirHandle)) {
            if(!is_file(__DIR__.'/../reports/'.$file))
              continue;
            echo "<li class=\"show-file\">$file";
            echo '<div class="w3-hide w3-code code">';
            echo nl2br(file_get_contents(__DIR__.'/../reports/'.$file));
            echo '</div></li>';
          }
        ?>
      </ul>
  </div>
</body>

</html>
