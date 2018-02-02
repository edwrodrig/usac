<?php

namespace edwrodrig\usac;

class Util {

static function future_date($seconds) {
  $current_date = new \DateTime("now", new \DateTimeZone('GMT'));
  $current_date->add(new \DateInterval('PT' . $seconds . 'S'));

  return $current_date;
}

static function get_origin() {
  return $_SERVER['REMOVE_HOST'] ?? 'local';
}

static function normalize_origin($origin = null) {
  return empty($origin) ? \edwrodrig\usac\Util::get_origin() : $origin;
}

static function uniqid($length = 32) {
  return bin2hex(random_bytes($length));
}

}
