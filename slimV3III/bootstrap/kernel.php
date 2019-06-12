<?php
use Dotenv\Dotenv;

// Load Dotenv //
if (file_exists(base_path() . '/app.env')) {
    $_dotenv = new Dotenv(base_path(), "app.env");

    $_dotenv->load();

    unset($_dotenv);
}
