<?php

require_once('al-includes/core.php');
require 'al-includes/emails/clientEmail.php';
require 'al-includes/emails/paymentEmail.php';
require 'vendor/autoload.php';

function generateCost($email, $rut, $name, $lastName, $id, $ticket){
   $client = new \GuzzleHttp\Client();         
     $body = $client->request('POST', 'https://app.payku.cl/api/transaction/', [
       'json' => [                
         'email' => 'finanzas@naturalmentesobrenatural.org', 
         'order' => $id, 
         'subject' => $ticket,
         'amount' => 5000,  
         'payment' => 1, 
         'urlreturn' => 'http://eventos.ivch.cl/api.php',
         'urlnotify' => 'http://eventos.ivch.cl/api.php',
         'marketplace' => '2af2b5f966c011b14179b1a1cfb0f37068aca6481fe1a240a8fd6af3f2e44a39'
         ],  
       'headers' => [                                  
         'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'              
       ]           
     ])->getBody();   
   $response = json_decode($body);
   header('Location: '.$response->url);
}

function getCapacity($conn, $id){
   $stmt = $conn->query("SELECT * FROM event WHERE eventId=".$id);
    while ($row = $stmt->fetch()) {
        $eventName=$row['eventName'];
        $eventCapacity=$row['eventCapacity'];
        if($eventCapacity>=1)
       {
         echo "<option value='".$id."'>".$eventName."</option>"; 
       }
    }
}

if(!empty($_POST['name']) && !empty($_POST['ln']) && !empty($_POST['rut']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['ticket']))
{
   try {
      $rut = strtoupper(str_replace(array('-', '.', "'", " "), '', $_POST['rut']))
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `person`(`personName`, `personLastName`, `personRut`, `personEmail`, `personPhone`, `personExtra`) VALUES ('".$_POST['name']."','".$_POST['ln']."','".$rut."','".$_POST['email']."','".$_POST['phone']."','".$_POST['extra']."')";
        $conn->exec($sql);
           try {
               $stmt = $conn->query("SELECT * FROM `person` WHERE `personEmail` ='".$_POST['email']."'");
               while ($row = $stmt->fetch()) {
                   generateCost($_POST['email'], $_POST['rut'], $_POST['name'], $_POST['lastName'], $row['personId'], $_POST['ticket']);
               }
           } catch(PDOException $e) {
           }
       } catch (PDOException $e) {
           echo $sql . "<br>" . $e->getMessage();
       }
}

?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <!-- Metas -->
      <meta charset="utf-8">
      <title>Eventos - Jóvenes La Viña Chile</title>
      <meta name="description" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Css -->
      <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all"/>
      <link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
      <link href="css/main.css" rel="stylesheet" type="text/css" media="all"/>
      <link href="css/flexslider.css" rel="stylesheet" type="text/css"  media="all" />
      <link href="css/venobox.css" rel="stylesheet" type="text/css"  media="all" />
      <link href="css/fonts.css" rel="stylesheet" type="text/css"  media="all" />
      <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,700" rel="stylesheet">
   </head>
   <body>
      <!-- Preloader 
      <div class="loader">
         <div class="loader-inner">
            <svg width="120" height="220" viewbox="0 0 100 100" class="loading-spinner" version="1.1" xmlns="http://www.w3.org/2000/svg">
               <circle class="spinner" cx="50" cy="50" r="21" fill="#111111" stroke-width="1.5"/>
            </svg>
         </div>
      </div>
       End preloader-->
      <!--Wrapper-->
      <div class="wrapper">
         <!--Hero section-->
         <section class="hero overlay">
            <!--Main slider-->
            <div class="main-slider slider">
               <ul class="slides">
                  <li>
                     <div class="background-img">
                        <img src="img/background.png">
                     </div>
                  </li>
               </ul>
            </div>
            <!--End main slider-->
            <!--Header-->
            <header class="header">
               <!--Container-->
               <div class="container ">
                  <!--Row-->
                  <div class="row">
                     <div class="col-md-2">
                        <a class="scroll logo" href="#wrapper">
                           <h2>eventos</h2>
                        </a>
                     </div>
                     <div class="col-md-10 text-right">
                        <nav class="main-nav">
                           <div class="toggle-mobile-but">
                              <a href="#" class="mobile-but" >
                                 <div class="lines"></div>
                              </a>
                           </div>
                           <ul>
                              <li><a class="scroll" href="#wrapper">Inicio</a></li>
                              <li><a class="scroll" href="#informacion">Información</a></li>
                              <li><a class="scroll" href="#topics">Invitado</a></li>
                              <li><a class="scroll" href="#schedule">Programa</a></li>
                              <li><a class="scroll" href="#entrada">Tickets</a></li>
                           </ul>
                        </nav>
                     </div>
                  </div>
                  <!--End row-->
               </div>
               <!--End container-->
            </header>
            <!--End header-->
            <!--Inner hero-->
            <div class="inner-hero fade-out">
               <!--Container-->
               <div class="container hero-content">
                  <!--Row-->
                  <div class="row">
                     <div class="col-sm-12 text-center">
                        <h1 class="large mb-10"></h1>
                        <p class="uppercase "></p>
                     </div>
                  </div>
                  <!--End row-->
               </div>
               <!--End container-->
            </div>
            <!--End inner hero-->
         </section>
         <!--End hero section-->
         <!--About section-->
         <section id="informacion" class="about pt-120 pb-120 brd-bottom">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-sm-8 col-sm-offset-2 mb-100 text-center">
                     <h1 class="title">Incontenible</h1>
                     <p class="title-lead mt-20">Anhelamos que Dios venga e inunde nuestras vidas de manera <b>INCONTENIBLE</b> por lo que los esperamos a todos en dos fechas: 8 de abril para todas las viñas de Santiago y 9 de abril exclusivo para regiones.</p>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-md-3 col-sm-3">
                     <div class="block-info-1">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                           width="35px" height="25px" viewBox="0 0 42 32" enable-background="new 0 0 42 32" xml:space="preserve">
                           <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="5.1983" y1="28.1187" x2="43.4067" y2="11.702">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_1_)" d="M38,30.5v-19c0-0.276-0.224-0.5-0.5-0.5S37,11.224,37,11.5v19c0,0.276-0.224,0.5-0.5,0.5h-31
                              C5.224,31,5,30.776,5,30.5v-19C5,11.224,4.776,11,4.5,11S4,11.224,4,11.5v19C4,31.327,4.673,32,5.5,32h31
                              C37.327,32,38,31.327,38,30.5z"/>
                           <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="4.7162" y1="26.9965" x2="42.9245" y2="10.5799">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_2_)" d="M8.5,23C8.224,23,8,23.224,8,23.5S8.224,24,8.5,24H10v3.5c0,0.276,0.224,0.5,0.5,0.5
                              s0.5-0.224,0.5-0.5V24h6v3.5c0,0.276,0.224,0.5,0.5,0.5s0.5-0.224,0.5-0.5V24h6v3.5c0,0.276,0.224,0.5,0.5,0.5s0.5-0.224,0.5-0.5V24
                              h6v3.5c0,0.276,0.224,0.5,0.5,0.5s0.5-0.224,0.5-0.5V24h1.5c0.276,0,0.5-0.224,0.5-0.5S33.776,23,33.5,23H32v-5h1.5
                              c0.276,0,0.5-0.224,0.5-0.5S33.776,17,33.5,17H32v-4.5c0-0.276-0.224-0.5-0.5-0.5S31,12.224,31,12.5V17h-6v-4.5
                              c0-0.276-0.224-0.5-0.5-0.5S24,12.224,24,12.5V17h-6v-4.5c0-0.276-0.224-0.5-0.5-0.5S17,12.224,17,12.5V17h-6v-4.5
                              c0-0.276-0.224-0.5-0.5-0.5S10,12.224,10,12.5V17H8.5C8.224,17,8,17.224,8,17.5S8.224,18,8.5,18H10v5H8.5z M31,18v5h-6v-5H31z
                              M24,18v5h-6v-5H24z M11,18h6v5h-6V18z"/>
                           <linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="-0.4811" y1="14.9003" x2="37.7272" y2="-1.5164">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_3_)" d="M32.5,3h4.25C36.837,3,37,3,37,3.5V8H5V3.5C5,3.224,5.224,3,5.5,3h4C9.776,3,10,2.776,10,2.5
                              S9.776,2,9.5,2h-4C4.673,2,4,2.673,4,3.5v5C4,8.776,4.224,9,4.5,9h33C37.776,9,38,8.776,38,8.5v-5C38,2.394,37.354,2,36.75,2H32.5
                              C32.224,2,32,2.224,32,2.5S32.224,3,32.5,3z"/>
                           <linearGradient id="SVGID_4_" gradientUnits="userSpaceOnUse" x1="-1.6311" y1="12.2237" x2="36.5772" y2="-4.1929">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_4_)" d="M26.5,3C26.776,3,27,2.776,27,2.5S26.776,2,26.5,2h-11C15.224,2,15,2.224,15,2.5S15.224,3,15.5,3
                              H26.5z"/>
                           <linearGradient id="SVGID_5_" gradientUnits="userSpaceOnUse" x1="-2.9557" y1="9.1407" x2="35.2526" y2="-7.2759">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_5_)" d="M13,4.5v-4C13,0.224,12.776,0,12.5,0S12,0.224,12,0.5v4C12,4.776,12.224,5,12.5,5S13,4.776,13,4.5z"
                              />
                           <linearGradient id="SVGID_6_" gradientUnits="userSpaceOnUse" x1="-0.3065" y1="15.3067" x2="37.9019" y2="-1.11">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_6_)" d="M29.5,5C29.776,5,30,4.776,30,4.5v-4C30,0.224,29.776,0,29.5,0S29,0.224,29,0.5v4
                              C29,4.776,29.224,5,29.5,5z"/>
                        </svg>
                        <p>
                           <strong>FECHA</strong>
                           <span>8 y 9 de Abril</span>
                           <span>$5.000</span>
                        </p>
                     </div>
                  </div>
                  <div class="col-md-3 col-sm-3">
                     <div class="block-info-1">
                        <svg version="1.1" id="Layer_7" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                           width="35px" height="25px" viewBox="0 0 42 32" enable-background="new 0 0 42 32" xml:space="preserve">
                           <linearGradient id="SVGID_7_" gradientUnits="userSpaceOnUse" x1="4.511" y1="11.8158" x2="41.3229" y2="27.6695">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_7_)" d="M10.239,31.926c0.009,0.006,0.021,0.003,0.03,0.009C10.341,31.973,10.418,32,10.499,32
                              c0.044,0,0.088-0.006,0.132-0.018l10.868-2.966l10.868,2.966C32.411,31.994,32.455,32,32.499,32c0.082,0,0.158-0.027,0.23-0.065
                              c0.01-0.005,0.021-0.003,0.03-0.009l9-5.5c0.191-0.117,0.281-0.348,0.22-0.563l-4.984-17.5c-0.041-0.147-0.148-0.267-0.29-0.326
                              c-0.142-0.057-0.301-0.048-0.436,0.026l-4.962,2.784c-0.24,0.135-0.326,0.44-0.191,0.681c0.135,0.242,0.439,0.327,0.682,0.191
                              l4.409-2.475l4.707,16.526l-8.015,4.898l-1.904-15.231c-0.034-0.275-0.293-0.466-0.559-0.434c-0.273,0.034-0.468,0.284-0.434,0.558
                              l1.907,15.259L22,28.115v-2.73c0-0.276-0.224-0.5-0.5-0.5s-0.5,0.224-0.5,0.5v2.73l-9.911,2.705l1.907-15.259
                              c0.034-0.274-0.16-0.524-0.434-0.558c-0.272-0.032-0.524,0.159-0.559,0.434l-1.904,15.231L2.084,25.77L6.791,9.244l4.409,2.475
                              c0.242,0.134,0.546,0.049,0.682-0.191c0.135-0.241,0.049-0.545-0.191-0.681L6.729,8.063C6.595,7.988,6.436,7.979,6.293,8.037
                              c-0.142,0.059-0.249,0.178-0.29,0.326l-4.984,17.5c-0.062,0.216,0.028,0.446,0.22,0.563L10.239,31.926z"/>
                           <linearGradient id="SVGID_8_" gradientUnits="userSpaceOnUse" x1="12.6241" y1="7.5582" x2="28.5468" y2="14.4156">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_8_)" d="M21.161,23.367c0.096,0.088,0.217,0.132,0.339,0.132c0.12,0,0.24-0.043,0.336-0.129
                              C22.169,23.067,30,15.882,30,8.499c0-4.767-3.733-8.5-8.5-8.5S13,3.732,13,8.499C13,15.753,20.828,23.059,21.161,23.367z
                              M21.5,0.999c4.275,0,7.5,3.224,7.5,7.5c0,6.097-5.993,12.337-7.497,13.807C20.002,20.819,14,14.497,14,8.499
                              C14,4.223,17.225,0.999,21.5,0.999z"/>
                           <linearGradient id="SVGID_9_" gradientUnits="userSpaceOnUse" x1="17.3671" y1="6.7191" x2="25.6329" y2="10.2789">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_9_)" d="M26,8.499c0-2.481-2.019-4.5-4.5-4.5S17,6.018,17,8.499s2.019,4.5,4.5,4.5S26,10.98,26,8.499z
                              M21.5,11.999c-1.93,0-3.5-1.57-3.5-3.5s1.57-3.5,3.5-3.5s3.5,1.57,3.5,3.5S23.43,11.999,21.5,11.999z"/>
                        </svg>
                        <p>
                           <strong>LUGAR</strong>
                           <span>Viernes - Exequiel Fernández 1029, Ñuñoa</span>
                           <span>Sábado - Bosques Nativos 5758, Peñalolén</span>
                        </p>
                     </div>
                  </div>
                  <div class="col-md-3 col-sm-3">
                     <div class="block-info-1">
                        <svg version="1.1" id="Layer_10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                           width="35px" height="25px" viewBox="0 0 42 32" enable-background="new 0 0 42 32" xml:space="preserve">
                           <linearGradient id="SVGID_10_" gradientUnits="userSpaceOnUse" x1="1.9027" y1="28.6566" x2="55.9027" y2="10.9483">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_10_)" d="M40.498,0c-0.276,0-0.5,0.224-0.5,0.5v31c0,0.276,0.224,0.5,0.5,0.5s0.5-0.224,0.5-0.5v-31
                              C40.998,0.224,40.774,0,40.498,0z"/>
                           <linearGradient id="SVGID_14_" gradientUnits="userSpaceOnUse" x1="1.1102" y1="26.2399" x2="55.1102" y2="8.5316">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_10_)" d="M38.717,1.204c-0.17-0.083-0.376-0.062-0.526,0.055l-0.565,0.445C34.648,4.059,29.667,8,19.5,8H5.631
                              C4.18,8,3,9.2,3,10.674v10.714C3,22.828,4.18,24,5.631,24h3.394c0.088,1.125,0.502,3.794,2.454,5.761
                              C12.953,31.247,14.979,32,17.5,32c0.276,0,0.5-0.224,0.5-0.5S17.776,31,17.5,31c-2.242,0-4.026-0.652-5.306-1.938
                              c-1.668-1.677-2.067-4.025-2.163-5.062h2.995c0.085,0.682,0.36,1.881,1.274,2.802C15.089,27.597,16.166,28,17.5,28
                              c0.276,0,0.5-0.224,0.5-0.5S17.776,27,17.5,27c-1.055,0-1.891-0.302-2.484-0.896c-0.657-0.659-0.894-1.546-0.981-2.104h5.396
                              c10.216,0,15.237,3.963,18.237,6.331l0.522,0.411c0.089,0.07,0.198,0.105,0.307,0.105c0.075,0,0.15-0.017,0.219-0.051
                              c0.172-0.084,0.281-0.258,0.281-0.449V1.653C38.998,1.462,38.889,1.288,38.717,1.204z M4,21.388V10.674C4,9.751,4.731,9,5.631,9H9
                              v14H5.631C4.716,23,4,22.292,4,21.388z M37.998,29.317C34.864,26.851,29.672,23,19.432,23h-5.797
                              c-0.047-0.015-0.095-0.03-0.148-0.031c-0.001,0-0.001,0-0.002,0c-0.054,0-0.105,0.015-0.154,0.031H10V9h9.5
                              c10.237,0,15.392-3.864,18.498-6.316V29.317z"/>
                           <linearGradient id="SVGID_11_" gradientUnits="userSpaceOnUse" x1="-1.8839" y1="17.1097" x2="52.1161" y2="-0.5987">
                              <stop  offset="0" style="stop-color:#fd5819"/>
                              <stop  offset="1" style="stop-color:#4f8cf2"/>
                           </linearGradient>
                           <path fill="url(#SVGID_11_)" d="M1.5,21.857c0.276,0,0.5-0.224,0.5-0.5V10.643c0-0.276-0.224-0.5-0.5-0.5S1,10.367,1,10.643v10.714
                              C1,21.633,1.224,21.857,1.5,21.857z"/>
                        </svg>
                        <p>
                           <strong>INVITADO ESPECIAL</strong>
                           <span>Paul Rapley</span>
                        </p>
                     </div>
                  </div>
                  <div class="col-md-3 col-sm-3 text-right">
                     <div class="block-info-1">
                        <a href="#entrada" class="scroll but">Obtén tu Entrada</a>
                     </div>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End about section-->
         <!--Topics section-->
         <section id="topics" class="topics pt-120 pb-60  brd-bottom">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-sm-8 col-sm-offset-2 text-center">
                     <h1 class="title">Conoce a nuestro invitado</h1>
                     <p class="title-lead mt-10">Paul estudió y se graduó de la Escuela de Ministerio de Bill Johnson en Redding, California. También tiene una Maestría en Divinidad de King’s University; así como un título en Biblia y Teología de Crown College. Paul apareció en el documental “Christ in You/Cristo en ti”, ha enseñado en la Escuela Ministerial de Teen Challenge en Minnesota, BSSM, entre muchas más. Ha estado en más de 50 países, con más de 10.000 testimonios de condiciones físicas sanadas por Jesús. Él tiene una tremenda pasión por ver a la iglesia activada para ministrar los dones del Espíritu.</p>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
            <div class="container">
               <div class="row vertical-align">
                  <div class="col-md-6 col-sm-6">
                     <div class="col-md-12">
                        <img src="img/paul.png">
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                     <div class="block-video">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/Ob9bOcYkDKc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                     </div>
                  </div>
               </div>
               <!--end of row-->
            </div>
         </section>
         <!--End topics section-->
         <!--Schedule section-->
         <section id="schedule" class="schedule pb-120">
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-sm-8 col-sm-offset-2 mb-100 text-center">
                     <h1 class="title">Programa</h1>
                     <p class="title-lead mt-10">¡Conoce el programa de nuestra conferencia y crea expectativas del tiempo que tendremos!</p>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-sm-12">
                     <h3 class="sub-title-0  mb-25"><span class="gradient-text">Días de Conferencia</span></h3>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-sm-4 ">
                     <!--Tabs-->
                     <ul class="block-tabs">
                        <li class="active"><i class="et-line-calendar"></i><strong>Viernes</strong>  <span>- 8 Abril 2022</span></li>
                        <li class=""><i class="et-line-calendar"></i><strong>Sábado</strong>  <span>- 9 Abril 2022</span></li>
                     </ul>
                  </div>
                  <div class="col-sm-8 ">
                     <ul class="block-tab">
                        <!--Tab-->
                        <li class="active">
                           <div class="block-date"><i class="et-line-calendar"></i><strong>Viernes</strong>  <span>- Exequiel Fernández 1029, Ñuñoa</span></div>
                           <div class="block-detail">
                              <span class="time">08:00 - 10:00</span>
                              <span class="topic">Conference Opening</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <p class="gradient-text ">Adam Blanco</p> </span>
                              </div>
                           </div>
                           <div class="block-detail">
                              <span class="time">10:30 - 12:30</span>
                              <span class="topic">Photography Foundations</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <a href="#" class="gradient-text ">Luca Palermo</a> </span>
                              </div>
                           </div>
                           <div class="block-detail">
                              <span class="time">13:00</span>
                              <span class="topic">Lunch Time</span>
                           </div>
                           <div class="block-detail">
                              <span class="time">15:00 - 16:30</span>
                              <span class="topic">Retouching</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <a href="#" class="gradient-text "> Lina Blamberg </a> </span>
                              </div>
                           </div>
                           <div class="block-detail">
                              <span class="time">17:00</span>
                              <span class="topic">Coffee Break</span>
                           </div>
                           <div class="block-detail">
                              <span class="time">17:30 - 18:00</span>
                              <span class="topic">Raw Processing</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <a href="#" class="gradient-text "> Emilie Lippelt </a> </span>
                              </div>
                           </div>
                        </li>
                        <!--Tab-->
                        <li>
                           <div class="block-date"><i class="et-line-calendar"></i><strong>Sábado</strong>  <span>- Bosques Nativos 5758, Peñalolén</span></div>
                           <div class="block-detail">
                              <span class="time">08:00 - 10:00</span>
                              <span class="topic">Cameras + Gear</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <a href="#" class="gradient-text ">Adam Blanco</a> </span>
                              </div>
                           </div>
                           <div class="block-detail">
                              <span class="time">10:30 - 12:30</span>
                              <span class="topic">Night + Low Light</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <a href="#" class="gradient-text ">Luca Palermo</a> </span>
                              </div>
                           </div>
                           <div class="block-detail">
                              <span class="time">13:00</span>
                              <span class="topic">Lunch Time</span>
                           </div>
                           <div class="block-detail">
                              <span class="time">15:00 - 16:30</span>
                              <span class="topic">Lighting</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <a href="#" class="gradient-text "> Lina Blamberg </a> </span>
                              </div>
                           </div>
                           <div class="block-detail">
                              <span class="time">17:00</span>
                              <span class="topic">Coffee Break</span>
                           </div>
                           <div class="block-detail">
                              <span class="time">17:30 - 18:00</span>
                              <span class="topic">Color Correction</span>
                              <div class="block-text">
                                 <p>Nihilne te nocturnum praesidium Palati, nihil urbis vigilae, nihil timor populi, nihil concursus velit omnium, nihil hic muntissimus habendi senatus locus, nihil horum ora vultusque moverunt, patere tua consilia non sentis constrictam nihil hic muntissimus.</p>
                                 <span class="speaker"> <strong >Speaker</strong> : <a href="#" class="gradient-text "> Emilie Lippelt </a> </span>
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End schedule section-->
         <!--Counter section-->
         <section class="counter pt-120 pb-120 overlay parallax">
            <div class="background-img" >
               <img src="img/bgclean.png" alt="">
            </div>
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-sm-12  text-center  front-p">
                     <h1 class="title">Tiempo restante hasta el inicio de la conferencia.</h1>
                     <p class="title-lead mt-10 mb-20">8 y 9 de Abril 2022 - Santiago, Chile </p>
                     <span class="countdown gradient-text"></span>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End counter section-->
         <!--Register section-->
         <section id="entrada" class="register pt-120 pb-120 overlay">
            <div class="background-img " >
               <img src="img/bgclean.png" alt="">
            </div>
            <!--Container-->
            <div class="container">
               <!--Row-->
               <div class="row">
                  <div class="col-md-6 front-p">
                     <img src="img/infodates.png">
                  </div>
                  <div class="col-md-6 front-p">
                     <form class="registry-form form" name="buy" method="POST" action="">
                        <h2 class="sub-title-1 mt-150 mb-30">Obtén tu entrada</h2>
                        <div class="col-sm-6">
                           <input placeholder="Nombre" value="" id="name" name="name" type="text" required>
                        </div>
                        <div class="col-sm-6">
                           <input placeholder="Apellido" value="" id="ln" name="ln" type="text" required>
                        </div>
                        <div class="col-sm-6">
                           <input placeholder="Tu Rut" value="" id="rut" name="rut" type="text" required>
                        </div>
                        <div class="col-sm-6">
                           <input placeholder="Tu WhatsApp" value="" id="phone" name="phone" type="text" required>
                        </div>
                        <div class="col-sm-6">
                           <input placeholder="Tu Email" value="" id="email" name="email" type="email" required>
                        </div>
                        <div class="col-sm-6">
                           <input placeholder="Tu Iglesia" value="" id="extra" name="extra" type="text">
                        </div>
                        <div class="col-sm-6">
                           <div class="block-select">
                              <select required name="ticket">
                                 <option value="" disabled selected hidden>Selecciona tu Entrada</option>
                                 <?php getCapacity($conn, 1); ?>
                                 <?php getCapacity($conn, 2); ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <input value="Obtén tu entrada" class="but submit" type="submit">
                        </div>
                        <div class="col-sm-12">
                           <p>* No compartiremos tu información.</p>
                        </div>
                     </form>
                  </div>
               </div>
               <!--End row-->
            </div>
            <!--End container-->
         </section>
         <!--End register section-->
         <!--End faq section-->
         <!--End gallery section-->
         <footer class="bg-dark">
            <div class="bottom-footer bg-black pb-50">
               <!--Container-->
               <div class="container ">
                  <div class="row">
                     <div class="col-md-6">
                        <p>	&copy; 2022 - Asociación de Iglesias La Viña Chile - Todos los derechos reservados.</p>
                     </div>
                     <!--
                     <div class="col-md-6 ">
                        <ul class="block-legal">
                           <li><a href="#">Privacy Policy</a>
                           <li><a  href="#">Terms of Use</a></li>
                           <li><a  href="#">About</a></li>
                           <li><a  href="#">Legal</a></li>
                           <li><span><a class="gradient-text scroll" href="#wrapper">Volver al Inicio</a></span></li>
                        </ul>
                     </div>
                     -->
                  </div>
               </div>
               <!--End container-->		
            </div>
         </footer>
      </div>
      <!-- End wrapper-->
      <!--Javascript-->	
      <script src="js/jquery-1.12.4.min.js" type="text/javascript"></script>
      <script src="js/jquery.flexslider-min.js" type="text/javascript"></script>
      <script src="js/jquery.countdown.min.js" type="text/javascript"></script>
      <script src="js/smooth-scroll.js" type="text/javascript"></script>
      <script src="js/jquery.validate.min.js" type="text/javascript"></script>
      <script src="js/placeholders.min.js" type="text/javascript"></script>
      <script src="js/venobox.min.js" type="text/javascript"></script>
      <script src="js/instafeed.min.js" type="text/javascript"></script>
      <script src="js/script.js" type="text/javascript"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbGQXiGt-6UAmOFFdSzYI-byeE7ewBuVM&callback=initializeMap"></script>
      <!-- Google analytics -->
      <!-- End google analytics -->
   </body>
</html>
