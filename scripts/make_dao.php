<?php

require_once(__DIR__ . '/../vendor/autoload.php');

edwrodrig\f2s\Commands::generate_dao_from_folder(__DIR__ . '/../config/db', '\edwrodrig\usac\db\sqlite3', 'sqlite3', __DIR__ . '/../src');
