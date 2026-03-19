<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle request normally (Bref handles Lambda automatically)
$app->handleRequest(Request::capture());