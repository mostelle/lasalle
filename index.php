<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>La Salle</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">LA SALLE</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="#resForm">Réserver</a></li>
      </ul>

      <ul id="nav-mobile" class="sidenav">
        <li><a href="#resForm">Réserver</a></li>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
  </nav>

  <div class="parallax-container" id="index-banner">
    
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>
        <h1 class="header center white-text z-depth-5">LA SALLE</h1>
        <div class="row center">
        <a href="#resForm" class="btn-large waves-effect waves-light orange"><i class="material-icons right">query_builder</i>Réservez</a>
      </div>
      </div>
    </div>
    

    <div class="parallax">
      <img src="img/background1.jpg" alt="Unsplashed background img 1" style="transform: translate3d(-50%, 381.8px, 0px); opacity: 1;">
    </div>
  
  </div>

  <div class="container">
    <?php
    
error_reporting(E_ALL);
ini_set("display_errors", 1);
    include_once 'includes.php';
    include_once 'process.php';

    if( isset( $msg_remp ) && empty($msg_remp) ){ ?>
      <div class="row center">
        <h3 class="header col s12 light">Merci pour votre réservation !</h3>
        <p>Nous vous contacterons dans les plus bref délais !</p>
      </div>
    <?php
    }else{
    ?>
      <?php if( empty( $connectPDO->getError() ) ){ ?>

      <?php if( isset( $msg_remp ) && !empty($msg_remp) ){ ?>
         <div class="row center">
          <p>
            <strong><?php echo $msg_remp; ?></strong>
          </p>
        </div>
      <?php } ?>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="resForm">
      
        <div class="row center">
          <h5 class="header col s12 light">Réservez votre salle en ligne</h5>
        </div>

        <div class="input-field col s12">
          <select name="salle_id" class="icons">
            <option value="" disabled selected>Choisissez votre salle :</option>
            <?php echo $rent->listOptionsRoom(); ?>
          </select>
          <label>Choix de la salle</label>
        </div>

        <div class="input-field col s12">
          <input name="date_res" type="text" class="datepicker">
          <label>Choix de la date</label>
        </div>

        <div class="row">
          <div class="input-field col s6">
            <i class="material-icons prefix">account_circle</i>
            <input name="name" placeholder="Votre Nom" id="first_name" type="text" class="validate">
            <label for="first_name">Nom</label>
          </div>
          <div class="input-field col s3">
            <i class="material-icons prefix">contact_mail</i>
            <input name="mail" placeholder="Votre Mail" id="email" type="email" class="validate">
            <label for="email">Email</label>
          </div>
          <div class="input-field col s3">
            <i class="material-icons prefix">contact_phone</i>
            <input name="tel" placeholder="Votre Téléphone" id="icon_telephone" type="tel" class="validate">
            <label for="icon_telephone">Téléphone</label>
          </div>
        </div>

        <div class="row center">
          <p>
            <button id="resButton" name="resButton" type="submit" class="btn-large waves-effect waves-light orange">Réserver</button>
          </p>
        </div>
      </form>

      <?php
      }else{ // c'est la crise !! ?>
        <p>Désolé, vous ne pouvez pas réserver en ce moment, nous rencontrons une erreur avec nos services. Veuillez nous excusez de la gêne occasionnée.</p>
        <p>Pour la création des tables lors d'une première ouverture : <strong>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <button type="submit" class="btn-large waves-effect waves-light orange">Recharger la page SVP.</button>
          </form>
        </p>
        <p style="display:none">
          <strong>Impossible de se connecter à la base de données => </strong>
          <code><?php echo $connectPDO->getError(); ?></code>
        </p>
      <?php
      }
    }//$thanks
    ?>
  </div>

  <?php
    $listRentRoom = $rent->listRentRoom();

    if( !empty($listRentRoom) ){ 
  ?>
    <div class="container">
      <div class="section">
        <!--   Icon Section   -->
        <div class="row">
          <div class="col s12">
          
            <h5 class="center">Liste des réservations</h5>
            <table class="striped responsive-table ">
              <thead>
                <tr>
                    <th class="col s4">Salle</th>
                    <th class="col s4">Nom</th>
                    <th class="col s2">Mail</th>
                    <th class="col s2">Date</th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                  foreach ($listRentRoom as $room => $details) {
                    
                    $date     = $details['date_res'];
                    $dateAr   = explode('-',$date);
                    $dateArFr = array_reverse($dateAr);
                    $dateFr   = implode('/', $dateArFr );
                    ?>
                    <tr>
                      <td class="col s4">
                          <div class="col s4">
                            <img src="img/salle-<?php echo  $details['salle_id']; ?>.jpg" alt="" class="circle responsive-img">
                          </div>
                          <div class="col s8">
                            <span class="black-text">
                              <?php echo $details['town'] . ' |<br>' . $details['salle_name'] ; ?>
                            </span>
                          </div>
                      </td>
                      <td class="col s4"><?php echo $details['client_name']; ?></td>
                      <td class="col s2"><?php echo $details['mail']; ?></td>
                      <td class="col s2"><?php echo $dateFr; ?></td>
                    </tr>
                    <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php 
    }
  ?>
  <div class="container">
    <div class="section">
        <!--   Icon Section   -->
      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">business_center</i></h2>
            <h5 class="center">Mission</h5>

            <p class="light">Location de salle pour l’organisation de réunion par les entreprises ou les particuliers.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">room</i></h2>
            <h5 class="center">Périmètre géographique</h5>

            <p class="light">Salles dans toute la France, nottament Paris, Lyon et Marseille.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">map</i></h2>
            <h5 class="center">Adresse</h5>

            <p class="light">300 Boulevard de Vaugirard, 75017 Paris, France</p>
          </div>
        </div>
      </div>

    </div>

    <br><br>
  
  </div>

  <footer class="page-footer orange">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Qui sommes-nous ? </h5>
          <p>Nous sommes une société spécialisée dans la location de salle pour l’organisation de réunion par les entreprises ou les particuliers. Notre périmètre géographique de l’activité s'étends sur toute la France et plus précisément à Paris, Lyon et Marseille.</p>
        </div>
        <div class="col l3 s12">
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Où nous trouver ?</h5>
          <ul>
            <li class="white-text" >Adresse : 300 Boulevard de Vaugirard, 75017 Paris, France</li>
            <li class="white-text" >Raison sociale : LASALLE</li>
            <li class="white-text" >Type de structure : SARL</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="orange-text text-lighten-3" href="#">W.Loiseau</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>


  </body>
</html>