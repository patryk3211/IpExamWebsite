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

function coding_fwrite($handle, $str) {
  $recoded = iconv('utf-8', $GLOBALS['config']['file_encoding'], $str);
  if($recoded === false)
    fwrite($handle, $str);
  else
    fwrite($handle, $recoded);
}

function save_report($filename, $report_array) {
  $handle = fopen($filename, 'w');
  // Write headers
  coding_fwrite($handle, '"Adres w pytaniu","Maska binarna","Maska dziesiętna","Adres Sieci","Adres Rozgłoszeniowy",'.
                  '"Punkty maska binarna","Punkty maska dziesiętna","Punkty adres sieci","Punkty adres rozgłoszeniowy"'."\n");
  // Write a line for each entry
  foreach($report_array as $entry) {
    coding_fwrite($handle, '"'.$entry['address'].'",');
    coding_fwrite($handle, '"'.$entry['binmask'].'",');
    coding_fwrite($handle, '"'.$entry['decmask'].'",');
    coding_fwrite($handle, '"'.$entry['netaddr'].'",');
    coding_fwrite($handle, '"'.$entry['brdaddr'].'",');
    coding_fwrite($handle, $entry['binmask_score'].',');
    coding_fwrite($handle, $entry['decmask_score'].',');
    coding_fwrite($handle, $entry['netaddr_score'].',');
    coding_fwrite($handle, $entry['brdaddr_score']."\n");
  }
  
  fclose($handle);
}

function save_result($solver, $startTime, $endTime, $score, $totalScore, $grade) {
  $handle = null;
  if(!file_exists(__DIR__.'/../results.csv')) {
    // Write a header
    $handle = fopen(__DIR__.'/../results.csv', 'w');
    coding_fwrite($handle, '"Numer w dzienniku","Imię","Nazwisko","Czas rozpoczęcia","Czas zakończenia","IP",');
    $questionCount = count($GLOBALS['config']['addresses']);
    for($i = 0; $i < $questionCount; ++$i) {
      coding_fwrite($handle, '"Pytanie '.($i+1).'",');
    }
    coding_fwrite($handle, '"Suma punktów","Ocena"'."\n");
  } else {
    $handle = fopen(__DIR__.'/../results.csv', 'a');
  }

  coding_fwrite($handle, $solver['number'].',"'.$solver['firstname'].'","'.$solver['lastname'].'","');
  coding_fwrite($handle, date("Y-m-d H:i:s", $startTime).'","');
  coding_fwrite($handle, date("Y-m-d H:i:s", $endTime).'","');
  coding_fwrite($handle, $_SERVER['REMOTE_ADDR'].'",');
  foreach($score as $entry) {
    coding_fwrite($handle, $entry.',');
  }
  coding_fwrite($handle, $totalScore.','.$grade."\n");
}

