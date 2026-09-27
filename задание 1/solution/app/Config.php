<?php

namespace App;

class Config {
    public string $base_dir;
    public string $db_host;
    public string $db_name;
    public string $db_user;
    public string $db_password;

    public function __construct() {
        $this->base_dir = __DIR__ . '/../';

        $this->db_host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'test';
        $this->db_user = getenv('DB_USER') ?: 'root';
        $this->db_password = getenv('DB_PASS') ?: '';
    }
}