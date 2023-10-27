<p align="center">
<a href="https://shazeedul.dev target="_blank"><img src="./resources/assets/admin-assets/img/logo.png" width="400" alt="Laravel Logo"></a>
</p>

## About Laravel

-   Empowering your knowledge with <b>nobelui.</b>

# How To Install

## Step 1: Install Dependencies

Before installing the this project, we need to make sure that our system has all the required dependencies installed. We will need to install the following dependencies:

-   PHP 8.1 or higher
-   Composer
-   Node.js
-   NPM
-   git

## Step 2: Clone This app

To clone the app, following command:

```bash
git clone
```

## Step 3: Go To project directory and composer install

-   first go to the project directory

```bash
cd project_path
```

-   Then copy the .env.example file to .env

```bash
cp .env.example .env
```

-   Then install composer

```bash
composer install
```

-   Then install npm

```bash
npm install
```

## Step 4: Compiling asset and publish assets

-   To compiling npm asset run this command

```bash
npm run build
```

-   then publish assets

```bash
php artisan storage:link
```

## Step 5: Config your database and assine to .env file

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nobelui
DB_USERNAME=root
DB_PASSWORD=
```

## Step 6: Config your google recaptcha

The first step is to create reCAPTCHA API keys. To do this, follow these steps:

-   Go to the Google reCAPTCHA admin <a href="https://www.google.com/recaptcha/">console</a>.
-   Sign in to your Google account if you haven't already done so.
-   Click on the "Create" button to create a new reCAPTCHA API key pair.
-   Select "reCAPTCHA v2" as the type of reCAPTCHA you want to use.
-   Enter a label for your reCAPTCHA key pair (e.g. "MyWebsite reCAPTCHA").
-   Add the domain name or IP address of your website in the "Domains" field.
-   Accept the reCAPTCHA Terms of Service and click on the "Submit" button.
-   After you submit the form, you will see the Site key and Secret key values that you will need to configure your reCAPTCHA.

### Then add those key to your .env file

```php
INVISIBLE_RECAPTCHA_SITEKEY='6LeOGLdadaUkAAAAAInNQSNiLNsvPWXFtfOvNzdN5rCi'
INVISIBLE_RECAPTCHA_SECRETKEY='6LeOdadaGLUkAAAAAK3pEgkOJrpuSuGIhrNYTKm8XGKR'
```

## Step 7: Now serve your application using php artisan serve command

```bash
 php artisan serve
```

## Security Vulnerabilities

If you discover a security vulnerability within This Project, please send an e-mail to <a href="https://shazeedul.dev" target="_blank">SYED SHAZEEDUL ISLAM</a> via [support@shazeedul.dev](mailto:support@shazeedul.dev). All security vulnerabilities will be promptly addressed.
