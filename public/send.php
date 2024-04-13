<?php
require __DIR__.'/../lib/config.php';

session_start();
if(!isset($_SESSION['solving']) || !$_SESSION['solving']) {
  header('Location: index.php');
  die();
}

function form_bin_ip_to_long($form_prefix) {
  $num = 0;
  for($i = 0; $i < 4; ++$i) {
    if(!isset($_POST[$form_prefix.$i]) || !is_numeric($_POST[$form_prefix.$i]))
      return false;
    $bintext = $_POST[$form_prefix.$i];
    if(!preg_match('/^[01]+$/', $bintext))
      return false;
    $num |= base_convert($bintext, 2, 10) << (24 - $i * 8);
  }
  return $num;
}

function form_ip_to_long($form_prefix) {
  $num = 0;
  for($i = 0; $i < 4; ++$i) {
    if(!isset($_POST[$form_prefix.$i]) || !is_numeric($_POST[$form_prefix.$i]))
      return false;
    $num |= $_POST[$form_prefix.$i] << (24 - $i * 8);
  }
  return $num;
}

function form_input_to_string($form_prefix) {
  $str = "";
  for($i = 0; $i < 4; ++$i) {
    if(!isset($_POST[$form_prefix.$i]))
      return false;
    $str .= $_POST[$form_prefix.$i];
    if($i != 3) $str .= '.';
  }
  return $str;
}

$duration = time() - $_SESSION['start_time'];
$timeLeft = time_limit() - $duration;

// Check if time has not run out
if($timeLeft > -5) {
  // Convert base address into longs
  $base_addr = $GLOBALS['config']['addresses'][$_SESSION['current_address_index']];
  $ip_mask = explode('/', $base_addr);

  $mask_bits = $ip_mask[1];
  $mask = 0;
  for($i = 0; $i < $mask_bits; ++$i) {
    $mask = ($mask << 1) | 1;
  }
  for($i = 0; $i < 32 - $mask_bits; ++$i) {
    $mask <<= 1;
  }
  $ip_addr = ip2long($ip_mask[0]);

  // Save input to report
  $report_entry = [
    'address' => $base_addr,
    'binmask' => form_input_to_string('maskbin'),
    'decmask' => form_input_to_string('maskdec'),
    'netaddr' => form_input_to_string('net'),
    'brdaddr' => form_input_to_string('broad'),
    'binmask_score' => 0,
    'decmask_score' => 0,
    'netaddr_score' => 0,
    'brdaddr_score' => 0,
  ];

  $totalScore = 0;

  // Check answers
  $correctmask = long2ip($mask);
  $binmaskcheck = long2ip(form_bin_ip_to_long('maskbin'));
  if($binmaskcheck == $correctmask) {
    ++$totalScore;
    $report_entry['binmask_score'] = 1;
  }

  $decmaskcheck = long2ip(form_ip_to_long('maskdec'));
  if($decmaskcheck == $correctmask) {
    ++$totalScore;
    $report_entry['decmask_score'] = 1;
  }

  $netaddrcheck = long2ip(form_ip_to_long('net'));
  $correctnet = long2ip($ip_addr & $mask);
  if($netaddrcheck == $correctnet) {
    ++$totalScore;
    $report_entry['netaddr_score'] = 1;
  }

  $brdaddrcheck = long2ip(form_ip_to_long('broad'));
  $correctbrd = long2ip(($ip_addr & $mask) | ~$mask);
  if($brdaddrcheck == $correctbrd) {
    ++$totalScore;
    $report_entry['brdaddr_score'] = 1;
  }

  array_push($_SESSION['report'], $report_entry);
  array_push($_SESSION['score'], $totalScore);
}

if($timeLeft <= 0) {
  $questionsLeft = count($_SESSION['score']) - count($GLOBALS['config']['addresses']);
  for($i = 0; $i < $questionsLeft; ++$i) {
    array_push($_SESSION['score'], 0);
  }
  header('Location: complete.php');
  die();
}

if(++$_SESSION['current_address_index'] < count($GLOBALS['config']['addresses'])) {
  header('Location: question.php');
} else {
  header('Location: complete.php');
}

