<?php

const POINTS_PER_ADDRESS = 4;

function max_score() {
  $addresses = $GLOBALS['config']['addresses'];
  $maxScore = count($addresses) * POINTS_PER_ADDRESS;
  return $maxScore;
}

function get_grade_scale() {
  $scaleText = $GLOBALS['config']['grade_scale'];
  $points = explode(',', $scaleText);

  $scale = [];
  foreach($points as $point) {
    $value = trim($point);
    if(!is_numeric($value)) {
      http_response_code(500);
      echo "500 - Internal Server Error";
      die();
    }
    settype($value, 'float');
    array_push($scale, $value / 100);
  }

  return $scale;
}

function grade($score) {
  $percent = $score / max_score();
  $scale = get_grade_scale();

  $grade = 1;
  foreach($scale as $minScore) {
    if($percent < $minScore)
      break;
    ++$grade;
  }

  return $grade;
}

