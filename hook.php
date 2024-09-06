<?php

define('URL_SITE_COLOR', 'darkblue');
define('URL_SITE_COLOR_OPACITY', 0.6);
define('URL_SITE_FONT_SIZE', 12);

function plugin_customlogin_install()
{
   $imgPluginPath = (GLPI_PLUGIN_DOC_DIR . DIRECTORY_SEPARATOR . "customlogin");
   if (!is_dir($imgPluginPath) && !mkdir($imgPluginPath, 0777, true)) {
      return false;
   }

   $imgPath = $imgPluginPath .
      DIRECTORY_SEPARATOR .
      'dev_background.png';

   if (!copy(__DIR__ . DIRECTORY_SEPARATOR . 'pics' . DIRECTORY_SEPARATOR . 'dev_background.png', $imgPath)) {
      return false;
   }

   return true;
}

function cleanCustomLogin()
{
   $pluginTempFilesPath = (GLPI_PLUGIN_DOC_DIR . DIRECTORY_SEPARATOR . "customlogin");

   if (is_dir($pluginTempFilesPath)) {
      $files = glob($pluginTempFilesPath . DIRECTORY_SEPARATOR . '*'); // get all file names
      foreach ($files as $file) { // iterate files
         if (is_file($file)) {
            unlink($file); // delete file
         }
      }
   }

   Config::deleteConfigurationValues('customlogin', PluginCustomloginConfig::FILES_NAMES);

   $picsDirectory = GLPI_ROOT . '/pics/logos';

   // G
   $newImagePathBlack = $picsDirectory . '/logo-G-100-black.png';
   $newImagePathGrey = $picsDirectory . '/logo-G-100-grey.png';
   $newImagePathWhite = $picsDirectory . '/logo-G-100-white.png';

   if (file_exists($picsDirectory . '/old_logo-G-100-black.png')) {
      copy($picsDirectory . '/old_logo-G-100-black.png', $newImagePathBlack);
   }
   if (file_exists($picsDirectory . '/old_logo-G-100-grey.png')) {
      copy($picsDirectory . '/old_logo-G-100-grey.png', $newImagePathGrey);
   }
   if (file_exists($picsDirectory . '/old_logo-G-100-white.png')) {
      copy($picsDirectory . '/old_logo-G-100-white.png', $newImagePathWhite);
   }

   // LOGOS
   $newImagePathBlack = $picsDirectory . '/logo-GLPI-100-black.png';
   $newImagePathGrey = $picsDirectory . '/logo-GLPI-100-grey.png';
   $newImagePathWhite = $picsDirectory . '/logo-GLPI-100-white.png';

   if (file_exists($picsDirectory . '/old_logo-GLPI-100-black.png')) {
      copy($picsDirectory . '/old_logo-GLPI-100-black.png', $newImagePathBlack);
   }
   if (file_exists($picsDirectory . '/old_logo-GLPI-100-grey.png')) {
      copy($picsDirectory . '/old_logo-GLPI-100-grey.png', $newImagePathGrey);
   }
   if (file_exists($picsDirectory . '/old_logo-GLPI-100-white.png')) {
      copy($picsDirectory . '/old_logo-GLPI-100-white.png', $newImagePathWhite);
   }

   $newImagePathBlack = $picsDirectory . '/logo-GLPI-250-black.png';
   $newImagePathGrey = $picsDirectory . '/logo-GLPI-250-grey.png';
   $newImagePathWhite = $picsDirectory . '/logo-GLPI-250-white.png';

   if (file_exists($picsDirectory . '/old_logo-GLPI-250-black.png')) {
      copy($picsDirectory . '/old_logo-GLPI-250-black.png', $newImagePathBlack);
   }
   if (file_exists($picsDirectory . '/old_logo-GLPI-250-grey.png')) {
      copy($picsDirectory . '/old_logo-GLPI-250-grey.png', $newImagePathGrey);
   }
   if (file_exists($picsDirectory . '/old_logo-GLPI-250-white.png')) {
      copy($picsDirectory . '/old_logo-GLPI-250-white.png', $newImagePathWhite);
   }
}

function plugin_customlogin_uninstall()
{
   cleanCustomLogin();

   return true;
}

function plugin_glpi9()
{
   $url_site_color = URL_SITE_COLOR;
   $url_site_color_opacity = URL_SITE_COLOR_OPACITY;
   $url_site_font_size = URL_SITE_FONT_SIZE - 2;

   $imgLogo = PluginCustomloginConfig::getConfig('logo');
   $imgBackground = PluginCustomloginConfig::getConfig('background');
   $imgMainBackground = PluginCustomloginConfig::getConfig('main_background');

   $jsScript = "";

   if (!empty($imgLogo)) {
      $imgLogo = PluginCustomloginConfig::getImageUrl($imgLogo);
      $jsScript .= "
         var img = document.getElementById('logo_login').children[0];
         img.src = `{$imgLogo}`;
      ";
   } else $imgLogo = '';

   if (!empty($imgBackground)) {
      $imgBackground = PluginCustomloginConfig::getImageUrl($imgBackground);
      $imgBackground = "
         background-image: url(\"{$imgBackground}\");
         background-position: left;
         background-size: 70vw 102%;
         background-repeat: no-repeat;
      ";
   } else $imgBackground = '';

   if (!empty($imgMainBackground)) {
      $imgMainBackground = PluginCustomloginConfig::getImageUrl($imgMainBackground);
   } else $imgMainBackground = Html::getPrefixedUrl('/plugins/customlogin/front/config.form.php?img_dev=dev_background.png');

   $jsScript .= "
      var textLogin = document.getElementById('text-login');

      if (!textLogin.innerHTML.trim()) {
         textLogin.innerHTML = '<h2>Faça login para sua conta</h2>';
      }

      var loginInput = document.getElementsByClassName('submit')[0];
      loginInput.value = 'Entrar';

      var divEl = document.createElement('div');
      divEl.id = 'root-div';

      var bodyEl = document.getElementsByTagName('body')[0];

      divEl.innerHTML = bodyEl.innerHTML;
      bodyEl.innerHTML = '';

      bodyEl.appendChild(divEl);

      var divElDev = document.createElement('div');
      divElDev.id = 'root-div-dev';
      divElDev.innerHTML = `
         <a title=\"Desenvolvido por ACB Processamento\" class=\"dev\" target=\"_blank\">
            Desenvolvido por ACB Processamento de Dados
         </a>
      `;

      bodyEl.appendChild(divElDev);
   ";

   echo "
      <style>
      #footer-login {
         display: block !important;
      }
      @media only screen and (max-width: 1024px) {
         body {
            position: relative;
            overflow: hidden;
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            background-image: url(\"{$imgMainBackground}\");
            background-position: left;
            background-size: 100vw 100vh;
            background-repeat: no-repeat;
         }
         #root-div-dev {
            bottom: 15px !important;
            width: 100vw;
            text-align: center;
            position: absolute;
            bottom: 0px;
            font-size: {$url_site_font_size}px !important;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: {$url_site_color} !important;
            opacity: {$url_site_color_opacity} !important;
         }
         .dev {
            font-size: {$url_site_font_size}px !important;
            color: {$url_site_color} !important;
            opacity: {$url_site_color_opacity} !important;
         }
         #root-div {
            box-shadow: 0px 0px 2px 0px black;
            overflow: hidden;
            position: absolute;
            top: 75px;
            bottom: 75px;
            right: 40px;
            left: 40px;
            background-color: white !important;
            font-family: inter, -apple-system, blinkmacsystemfont, san francisco, segoe ui, roboto, helvetica neue, sans-serif;
         }
         h2 {
            font-size: 1.25rem;
            line-height: 1.4;
            font-weight: 600;
         }
         #firstboxlogin {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            position: absolute;
            background: white !important;
            min-height: unset !important;
         }
         #logo_login, #text-login {
            background: transparent !important;
         }
         #logo_login {
            padding: unset !important
         }
         #logo_login > img {
            width: 195px;
            height: 195px;
         }
         #text-login {
            border-bottom: 1px solid rgba(98, 105, 118, 0.16);
         }
         div, label, a {
            color: black !important;
         }
         #footer-login.home {
            bottom: 55px !important;
            text-align: center;
            width: 100vw;
         }
         #display-login, #text-login {
            padding: 20px 20% !important;
         }
      }
      @media only screen and (min-width: 1024px) {
         body {
            position: relative;
            overflow: hidden;
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            background-image: url(\"{$imgMainBackground}\");
            background-position: left;
            background-size: 100vw 100vh;
            background-repeat: no-repeat;
         }
         #root-div-dev {
            bottom: 15px !important;
            right: 50px !important;
            position: absolute;
            bottom: 0px;
            font-size: {$url_site_font_size}px !important;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: {$url_site_color} !important;
            opacity: {$url_site_color_opacity} !important;
         }
         .dev {
            font-size: {$url_site_font_size}px !important;
            color: {$url_site_color} !important;
            opacity: {$url_site_color_opacity} !important;
         }
         #root-div {
            box-shadow: 0px 0px 2px 0px black;
            border-radius: 8px;
            overflow: hidden;
            position: absolute;
            top: 45px;
            bottom: 45px;
            right: 40px;
            left: 40px;
            {$imgBackground}
            background-color: white !important;
            font-family: inter, -apple-system, blinkmacsystemfont, san francisco, segoe ui, roboto, helvetica neue, sans-serif;
         }
         h2 {
            font-size: 1.2vw !important;
            line-height: 1.4;
            font-weight: 600;
            text-align: center;
         }
         #firstboxlogin {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            top: 0;
            right: 0;
            bottom: 0;
            position: absolute;
            width: 30vw !important;
            background: white !important;
            min-height: unset !important;

            box-shadow: -2px 0 2px 0px grey;
            padding-bottom: 50px;
         }
         #logo_login, #text-login {
            background: transparent !important;
         }
         #logo_login {
            padding: 0 0 0 0;
         }
         #logo_login > img {
            width: 195px !important;
            height: 195px !important;
         }
         #text-login {
            border-bottom: 1px solid rgba(98, 105, 118, 0.16);
            overflow-x: unset !important;
            overflow-y: unset !important;
         }
         div, label, a {
            color: black !important;
         }
         #footer-login.home {
            bottom: 55px !important;
            right: 50px !important;
         }
         .copyright {
            font-size: 9px !important;
         }
      }
      @media only screen and (max-height: 840px) {
         #logo_login > img {
            width: 195px !important;
            height: 195px !important;
         }
      }
      @media only screen and (max-height: 720px) {
         #logo_login > img {
            display: none;
         }
      }
      </style>
   ";

   echo Html::scriptBlock($jsScript);
}

function plugin_glpi10()
{
   global $CFG_GLPI;

   $url_site_color = URL_SITE_COLOR;
   $url_site_color_opacity = URL_SITE_COLOR_OPACITY;
   $url_site_font_size = URL_SITE_FONT_SIZE;

   $imgLogo = PluginCustomloginConfig::getConfig('logo');
   $imgBackground = PluginCustomloginConfig::getConfig('background');
   $imgMainBackground = PluginCustomloginConfig::getConfig('main_background');

   $jsScript = "";

   if (!empty($imgLogo)) {
      $imgLogo = PluginCustomloginConfig::getImageUrl($imgLogo);
      $imgLogo = "
         content: url(\"{$imgLogo}\") !important;
      ";
   } else $imgLogo = '';

   if (!empty($imgBackground)) {
      $imgBackground = PluginCustomloginConfig::getImageUrl($imgBackground);
      $imgBackground = "
         background-image: url(\"{$imgBackground}\");
         background-size: 100% 102%;
         background-repeat: no-repeat;
      ";
   } else $imgBackground = '';

   if (!empty($imgMainBackground)) {
      $imgMainBackground = PluginCustomloginConfig::getImageUrl($imgMainBackground);
   } else $imgMainBackground = Html::getPrefixedUrl('/plugins/customlogin/front/config.form.php?img_dev=dev_background.png');

   $jsScript .= "
      var bodyEl = document.getElementsByTagName('body')[0];

      var divElDev = document.createElement('div');
      divElDev.id = 'root-div-dev';
      divElDev.innerHTML = `
         <a title=\"Desenvolvido por ACB Processamento de Dados\" class=\"dev\" target=\"_blank\">
            Desenvolvido por ACB Processamento de Dados
         </a>
      `;

      var imgEl = document.querySelector('.text-center');
      imgEl.remove();

      var cardEl = document.querySelector('form > div');
      cardEl.children[0].className = 'col-md-12';
      cardEl.children[0].insertBefore(imgEl, cardEl.children[0].firstChild);

      var divElImg = document.createElement('div');
      divElImg.id = 'card-div-img';
      var elCardBody = document.querySelector('body > div.page-anonymous > div > div > div.card.card-md > div');
      elCardBody.insertBefore(divElImg, elCardBody.firstChild);

      bodyEl.appendChild(divElDev);

      function aguardarElementoExistir(selector, callback) {
         const interval = 100; // Intervalo de verificação em milissegundos
         let tempoMaximo = 5000; // Tempo máximo para aguardar (5 segundos)
       
         const verificarExistencia = () => {
           const elSelected = document.querySelector(selector);
           if (elSelected) {
             callback(elSelected);
           } else if (tempoMaximo > 0) {
             setTimeout(verificarExistencia, interval);
             tempoMaximo -= interval;
           }
         };
       
         verificarExistencia();
       }
       
       // NEWS
       aguardarElementoExistir('.plugin_news_alert-container', function(elemento) {
         if (elemento.hasChildNodes()) {
            document.getElementsByTagName('body')[0].style.height = 'auto';
            document.getElementsByTagName('body')[0].style.padding = '10px 0 0 0';
            aguardarElementoExistir('.container-tight > .text-center', function(elemento) {
               var loginDiv = document.querySelector('form > div > .text-center');
               document.querySelector('.container-tight').appendChild(loginDiv);
             });
         }
       });
   ";

   if (!empty($CFG_GLPI['text_login']) && strlen(strval($CFG_GLPI['text_login'])) > 0) {
      $loginText = html_entity_decode(strval($CFG_GLPI['text_login']));
      $jsScript .= "
         var textLogin = document.querySelector('body > div.page-anonymous > div > div > div.card.card-md > div > form > div > div.col-md-12 > div.card-header.mb-4 > h2');
         var oldTextLogin = document.querySelector('body > div.page-anonymous > div > div > div.card.card-md > div > form > div > div.col-auto.px-2.text-center > div');

         oldTextLogin.style.display = 'none';
         textLogin.innerHTML = `{$loginText}`;
      ";
   }

   echo "
      <style>
      @media only screen and (max-width: 1024px) {
         body {
            background-image: url(\"{$imgMainBackground}\");
            background-position: left;
            background-size: 100vw 100vh;
            background-repeat: no-repeat;
            position: unset !important;
         }
         .glpi-logo {
            width: 140px !important;
            height: 140px !important;
            margin-bottom: unset !important;
            {$imgLogo}
         }
         #root-div-dev {
            bottom: 30px !important;
            width: 100vw;
            text-align: center;
            position: relative;
            font-size: {$url_site_font_size}px !important;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: {$url_site_color} !important;
            opacity: {$url_site_color_opacity} !important;
         }
         body > div.page-anonymous > div > div > div.card.card-md > div > form > div > div.col-md-12 {
            padding-left: 40px !important;
            padding-right: 40px !important;
         }
      }
      @media only screen and (min-width: 1025px) {
         body {
            position: relative;
            overflow: auto;
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            background-image: url(\"{$imgMainBackground}\");
            background-position: left;
            background-size: 100vw auto;
            background-repeat: no-repeat;
         }
         .col-auto.px-2.text-center {
            width: 50vw;
            margin: auto;
         }
         #root-div-dev {
            bottom: 30px !important;
            width: 100vw;
            text-align: center;
            position: relative;
            font-size: {$url_site_font_size}px !important;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: {$url_site_color} !important;
            opacity: {$url_site_color_opacity} !important;
         }
         .page-anonymous {
            height: inherit;
         }
         .page-anonymous > div {
            height: inherit;
            padding-top: unset !important;
            padding-bottom: unset !important;
            margin-top: unset !important;
         }

         body > div.page-anonymous > div > div > div.card.card-md {
            height: 80vh;
            border: unset !important;
            border-radius: 8px !important;
         }
         body > div.page-anonymous > div > div {
            max-width: unset !important;
            width: 90vw !important;
         }
         body > div.page-anonymous > div > div > div.card.card-md > div {
            padding: unset !important;
            display: flex;
            flex-direction: row;
            overflow-y: hidden;
         }
         #card-div-img {
            flex: 1;
            {$imgBackground}
            border-radius: 8px 0 0 8px;
         }
         form {
            overflow-y: auto;
            flex: 0.25;
            height: 100%;
            width: unset !important;
            padding: 20px 60px 40px 60px;
            box-shadow: -1px 0 8px 0px grey;
         }
         .glpi-logo {
            width: 195px !important;
            height: 195px !important;
            margin-bottom: unset !important;
            {$imgLogo}
         }
         .mx-auto {
            font-size: 1.05rem !important;
         }
      }
      @media only screen and (max-height: 840px) {
         .glpi-logo {
            width: 110px !important;
            height: 110px !important;
         }
      }
      @media only screen and (max-height: 720px) {
         .glpi-logo {
            display: none;
         }
      }
      </style>
   ";

   echo Html::scriptBlock($jsScript);
}

function plugin_customlogin_display_login()
{
   if (version_compare(GLPI_VERSION, '9.0', 'ge') && version_compare(GLPI_VERSION, '10.0', 'lt')) {
      plugin_glpi9();
   } else if (version_compare(GLPI_VERSION, '10.0', 'ge')) {
      plugin_glpi10();
   };
}
