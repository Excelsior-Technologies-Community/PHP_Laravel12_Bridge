# PHP_Laravel12_Bridge

## Introduction

PHP_Laravel12_Bridge is a modern Laravel 12 application designed to demonstrate how to deploy and run Laravel in a serverless environment using the Bref ecosystem and AWS Lambda.

Unlike traditional web applications that require servers like Apache or Nginx, this project leverages serverless computing, allowing Laravel to run without managing infrastructure. This results in automatic scaling, high availability, and cost efficiency, making it ideal for modern cloud-based applications.

This project is especially useful for beginners and developers who want to understand how a traditional Laravel application can be transformed into a cloud-native, serverless application.

---

## What is Laravel Bridge?

Laravel Bridge is a package that connects Laravel applications to serverless platforms like AWS Lambda.

It works with Bref and allows Laravel to:

- Handle HTTP requests via Lambda

- Run without Apache/Nginx

- Scale automatically

- Reduce infrastructure cost

---

## Requirements

Before starting, make sure you have:

- PHP >= 8.2

- Composer

- Node.js & npm 

- AWS Account

- AWS CLI configured

- Serverless Framework installed

---

## Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Bridge "12.*"
cd PHP_Laravel12_Bridge
```

---

## Step 2: Install Bref & Laravel Bridge

```bash
composer require bref/bref bref/laravel-bridge
```

These packages allow Laravel to run on AWS Lambda.

---

## Step 3: Install Serverless Framework

```bash
npm install -g serverless
```

Check installation:

```bash
serverless --version
```

---

## Step 4: Create serverless.yml

```
service: php-laravel12-bridge

provider:
  name: aws
  region: ap-south-1
  runtime: provided.al2023

plugins:
  - ./vendor/bref/bref

package:
  patterns:
    - '!node_modules/**'
    - '!tests/**'
    - '!storage/logs/**'

functions:
  web:
    handler: public/index.php
    memorySize: 1024
    timeout: 28
    events:
      - httpApi: '*'
```

---

## Step 5: Update index.php

File: public/index.php

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle request normally (Bref handles Lambda automatically)
$app->handleRequest(Request::capture());
```

---

## Step 6: Environment Setup

Create .env:

```
APP_NAME=LaravelBridge
APP_ENV=production
APP_KEY=base64:GENERATE_KEY
APP_DEBUG=false
APP_URL=https://your-api-url

LOG_CHANNEL=stack
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## Step 7: Storage & Cache Setup

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 8: Add Test Route

File: routes/web.php

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Laravel 12 Bridge is Working 🚀";
});
```

---

## Step 9: Configure AWS

Run:

```
aws configure
```
	
Enter:

- AWS Access Key ID:        (paste)
- AWS Secret Access Key:    (paste)
- Default region name:      ap-south-1
- Default output format:    json
- AWS IAM User with programmatic access

---

## Step 10: Deploy to AWS Lambda

```bash
serverless deploy
```

After deployment, you will get:

```
https://xxxxx.execute-api.ap-south-1.amazonaws.com
```

---

## Step 11: Run Development Server

Run:

```bash
php artisan serve
```

```
http://127.0.0.1:8000/
```

## Output

<img src="screenshots/Screenshot 2026-03-19 140853.png" width="1000">

<img src="screenshots/Screenshot 2026-03-19 124622.png" width="1000">

<img src="screenshots/Screenshot 2026-03-19 124847.png" width="1000">

---

## Project Structure

```
PHP_Laravel12_Bridge/
│── app/
│── bootstrap/
│── config/
│── database/
│── public/
│── resources/
│── routes/
│── storage/
│── vendor/
│── serverless.yml
│── artisan
│── composer.json
```

Your PHP_Laravel12_Bridge Project is now ready!

