<?php

function generate_report_filename($solver) {
  $random = random_int(0, 999);
  if($random < 10) {
    $random = '00'.$random;
  } else if($random < 100) {
    $random = '0'.$random;
  }
  
  return $solver['number']." - ".$solver['firstname']." ".$solver['lastname']." - ".date("dmY_His")." - ".$_SERVER['REMOTE_ADDR']." - ".$random.".csv";
}

function save_report($filename, $report_array) {
  $handle = fopen($filename, 'w');
  // Write headers
  fwrite($handle, '"Adres w pytaniu","Maska binarna","Maska dziesiętna","Adres Sieci","Adres Rozgłoszeniowy",'.
                  '"Punkty maska binarna","Punkty maska dziesiętna","Punkty adres sieci","Punkty adres rozgłoszeniowy"'."\n");
  // Write a line for each entry
  foreach($report_array as $entry) {
    fwrite($handle, '"'.$entry['address'].'",');
    fwrite($handle, '"'.$entry['binmask'].'",');
    fwrite($handle, '"'.$entry['decmask'].'",');
    fwrite($handle, '"'.$entry['netaddr'].'",');
    fwrite($handle, '"'.$entry['brdaddr'].'",');
    fwrite($handle, $entry['binmask_score'].',');
    fwrite($handle, $entry['decmask_score'].',');
    fwrite($handle, $entry['netaddr_score'].',');
    fwrite($handle, $entry['brdaddr_score']."\n");
  }
  
  fclose($handle);
}

function save_result($solver, $startTime, $endTime, $score, $totalScore, $grade) {
  $handle = null;
  if(!file_exists(__DIR__.'/../results.csv')) {
    // Write a header
    $handle = fopen(__DIR__.'/../results.csv', 'w');
    fwrite($handle, '"Numer w dzienniku","Imię","Nazwisko","Czas rozpoczęcia","Czas zakończenia","IP",');
    $questionCount = count($GLOBALS['config']['addresses']);
    for($i = 0; $i < $questionCount; ++$i) {
      fwrite($handle, '"Pytanie '.($i+1).'",');
    }
    fwrite($handle, '"Suma punktów","Ocena"'."\n");
  } else {
    $handle = fopen(__DIR__.'/../results.csv', 'a');
  }

  fwrite($handle, $solver['number'].',"'.$solver['firstname'].'","'.$solver['lastname'].'","');
  fwrite($handle, date("Y-m-d H:i:s", $startTime).'","');
  fwrite($handle, date("Y-m-d H:i:s", $endTime).'","');
  fwrite($handle, $_SERVER['REMOTE_ADDR'].'",');
  foreach($score as $entry) {
    fwrite($handle, $entry.',');
  }
  fwrite($handle, $totalScore.','.$grade."\n");
}

