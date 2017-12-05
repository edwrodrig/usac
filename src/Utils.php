<?php

namespace edwrodrig\usac;

class Utils {

static function future_date($seconds) {
  $current_date = new \DateTime("now", new \DateTimeZone('GMT'));
  $current_date->add(new \DateInterval('PT' . $seconds . 'S'));

  return $current_date;
}

static function get_origin() {
  return $_SERVER['REMOVE_HOST'] ?? 'local';
}

}
