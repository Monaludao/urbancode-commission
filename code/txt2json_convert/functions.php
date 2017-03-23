<?php
function printarray($array) {
  foreach($array as $k => $v) {
    if(!is_array($v)) {
      echo '[' . $k . '] => ' . $v . '<br>';
    } else {
      echo '[' . $k . '] => ';
      printarray($v);
    }
  }
}

function find_index($fulltxt, $title_string) {
  $index = array();
  foreach($fulltxt as $k => $v) {
    if(preg_match("/$title_string/", $v)) {
      array_push($index, $k);
    }
  }
  return($index);
}

function slice_my_array($fulltxt, $index_array) {
  array_push($index_array, count($fulltxt));
  $sliced_txt = array();
  for($i = 0; $i < count($index_array); $i++) {
    if($i == 0) {
      $slice_start = 0;
    } else {
      $slice_start = $index_array[$i-1];
    }

    if(count($index_array) == 1) {
      $slice_length = count($fulltxt) - $slice_start;
    } else {
      $slice_length = $index_array[$i] - $slice_start;
    }
    array_push($sliced_txt, array_slice($fulltxt, $slice_start, $slice_length));
  }
  return($sliced_txt);
}

function combine_array_sentence($txt_array) {
  //compose sentences in section_array
  //txt_array need to be an array of txt array
  //e.g. array(array(of txt),array(of txt)...)
  for($n = 0; $n < count($txt_array); $n++) {
    for($n_line = 0; $n_line < count($txt_array[$n]); $n_line++) {
      if(preg_match('/：$|。$/', $txt_array[$n][$n_line])) {
        $txt_array[$n][$n_line] .= '\\r\\n';
      }
    }
    $txt_array[$n] = implode("", $txt_array[$n]);
    $txt_array[$n] = rtrim($txt_array[$n], '\\r\\n');
  }
  return($txt_array);
}

function clean_empty($txt_array) {
  for($i = 0; $i < count($txt_array); $i++) {
    if(count($txt_array[$i]) == 0) {
      unset($txt_array[$i]);
    }
  }
  return(array_values($txt_array));
}

function findDate($txt_line) {
  $txt_line = preg_replace("/ +/", "", $txt_line);
  preg_match('/中華民國([0-9]+)年/', $txt_line, $m_year);
  preg_match('/年([0-9]+)月/', $txt_line, $m_month);
  preg_match('/月([0-9]+)日/', $txt_line, $m_day);
  $m_date = trim($m_year[1]) . '/' . trim($m_month[1]) . '/' . trim($m_day[1]);
  return($m_date);
}

function findTime($txt_line) {
  if(preg_match("/時|：|:/", $txt_line)) {
    $txt_line = preg_replace("/ +/", "", $txt_line);
    $txt_line = preg_replace("/\(|\)/", "", $txt_line);
    $txt_line = preg_split("/時|：|:/", $txt_line);
  } else {
    preg_match_all("!\d+!", $txt_line, $txt_line);
    $txt_line = $txt_line[0];
  }
  if(count($txt_line) > 0) {
    preg_match('/[0-9]+/', $txt_line[0], $t_hour);
    $t_hour = $t_hour[0];
    preg_match('/[0-9]+/', $txt_line[1], $t_minute);
    if(count($t_minute) > 0) {
      $t_minute = $t_minute[0];
    } else {
      $t_minute = '00';
    }
    $s_time = trim($t_hour) . ':' . trim($t_minute);
    return($s_time);
  } else {
    return('00:00');
  }
}
