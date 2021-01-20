<?php

/**
 * Find an array key in a multi-dimensional array
 *
 * @param   string  $key        The array key to be searched for
 * @param   array   $search     The array to perform the search on
 * @return  bool
 */
function array_key_exists_r($key, $search) {
    $result = array_key_exists($key, $search);
    if($result) {
        return $result;
    }
    foreach($search as $v) {
        if(is_array($v)) {
            $result = array_key_exists_r($key, $v);
        }
        if($result) {
            return $result;
        }
    }
    return $result;
}

/**
 * Find a value of any array keys in a multi-dimensional array
 *
 * @param   string  $value      The value to be searched for
 * @param   array   $search     The array to perform the search on
 * @return  bool
 */
function array_value_exists_r($value, $search) {
    $result = array_search($value, $search, true);
    if($result) {
        return $result;
    }
    foreach($search as $v) {
        if(is_array($v)) {
            $result = array_value_exists_r($value, $v);
        }
        if($result) {
            return $result;
        }
    }
    return $result;
}