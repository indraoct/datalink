<?php

// My common functions
function simpleArray($array, $valCol) {
    $data = array();
    foreach ($array as $row) {
        $data[] = $row[$valCol];
    }

    return $data;
}

function listArray($array, $keyCol, $valCol) {
    $data = array();
    foreach ($array as $row) {
        $data[$row->$keyCol] = $row->$valCol;
    }

    return $data;
}

function listArrayEncodedID($array, $keyCol, $valCol) {
    $data = array();
    foreach ($array as $row) {
        $data[encode($row->$keyCol)] = $row->$valCol;
    }

    return $data;
}

function customListArray($array, $keyCol, $valCol, $txt){
     $data = array();
    foreach ($array as $row) {
        $data[$row->$keyCol] = $txt.$row->$valCol;
    }

    return $data;
}

function autoNumeric() {
    if (Config::get('format.currency') == 'id')
        return "{vMin:'-999999999999.99', vMax:'999999999999.99', aSep:'.', aDec:',' }";
    else
        return "{vMin:'-999999999999.99', vMax:'999999999999.99', aSep:',', aDec:'.' }";
}

function numericJS() {
    if (Config::get('format.currency') == 'id')
        return "'.',','";
    else
        return "',','.'";
}

function displayNumeric($num) {
    if ($num != null || $num != '') {
        if (Config::get('format.currency') == 'id')
            return number_format($num, 2, ",", ".");
        else
            return number_format($num, 2, ".", ",");
    } else
        return $num;
}

function printNumeric($num) {
    if ($num != null || $num != '') {
        if (Config::get('format.currency') == 'id') {
            if (fmod($num, 1) == 0) // $number DOES NOT have a significant decimal part
                return number_format($num, 0, ",", ".");
            else
                return number_format($num, 2, ",", ".");
        }
        else {
            if (fmod($num, 1) == 0) // $number DOES NOT have a significant decimal part
                return number_format($num, 0, ".", ",");
            else
                return number_format($num, 2, ".", ",");
        }
    } else
        return $num;
}

function defaultNumeric($num) {
    if ($num != null || $num != '') {
        if (Config::get('format.currency') == 'id')
            return str_replace(array('.', ','), array('', '.'), $num);
        else
            return str_replace(',', '', $num);
    } else
        return $num;
}

function displayDate($date) {
    if ($date != null || $date != '') {
        $date = DateTime::createFromFormat('Y-m-d', $date);
        return $date->format(Config::get('format.date.php'));
    } else
        return $date;
}

function displayDateTime($datetime) {
    if ($datetime != null || $datetime != '') {
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return $datetime->format(Config::get('format.datetime.php'));
    } else
        return $datetime;
}

function defaultDate($date) {
    if ($date != null || $date != '') {
        $date = DateTime::createFromFormat(Config::get('format.date.php'), $date);
        return $date->format('Y-m-d');
    } else
        return null;
}

function defaultDateTime($date) {
    if ($date != null || $date != '') {
        $date = DateTime::createFromFormat(Config::get('format.datetime.php'), $date);
        return $date->format('Y-m-d H:i:s');
    } else
        return null;
}

function encode($string) {
    $key = Config::get('globalvar.encode_key');
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    $hash = '';
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string, $i, 1));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
    }
    return $hash;
}

function decode($string) {
    $key = Config::get('globalvar.encode_key');
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    $hash = '';
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string, $i, 2)), 36, 16));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}

function toArray($param) {
    return json_decode(json_encode($param), true);
}

function hasPrivilege($code, $action) {
    $privi = Session::get('user_privi');
    if (isset($privi[$code][$action]))
        if ($privi[$code][$action])
            return true;

    return false;
}

function spellNumeric($n) {
    $dasar = array(1 => 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan');
    $angka = array(1000000000, 1000000, 1000, 100, 10, 1);
    $satuan = array('milyar', 'juta', 'ribu', 'ratus', 'puluh', '');
    $str = "";
    $i = 0;
    if ($n == 0) {
        $str = "nol";
    } else {
        while ($n != 0) {
            $count = (int) ($n / $angka[$i]);
            if ($count >= 10) {
                $str .= spellNumeric($count) . " " . $satuan[$i] . " ";
            } else if ($count > 0 && $count < 10) {
                $str .= $dasar[$count] . " " . $satuan[$i] . " ";
            }
            $n -= $angka[$i] * $count;
            $i++;
        }
        $str = preg_replace("/satu puluh (\w+)/i", "\\1 belas", $str);
        $str = preg_replace("/satu (ribu|ratus|puluh|belas)/i", "se\\1", $str);
    }
    return $str;
}

?>