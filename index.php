<?php
//inclusion fichiers
include("class/Constantes.php");
include("class/Connexion.php");
include("class/Search.php");
include("class/pagination.php");
//instance de la connexion
$connMongo = new Connexion();
$dataBase = $connMongo->getConnexion();

//instance sentiment analysis
require_once 'phpInsight-master/autoload.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>Euro 2016, search engine</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

</head>
<body>
<!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="js/materialize.min.js"></script>
<script>
 $(document).ready(function() {
    $('select').material_select();
  }); 
  </script>
  <nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="#" class="brand-logo"><img src="images/logo.jpg"/></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="#">Navbar Link</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="#">Navbar Link</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>

  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>

        <h1 class="header center teal-text text-lighten-2"></h1>

        <div class="row center">
          <h5 class="header col s12 light"></h5>
        </div>
        

      </div>
    </div>
    <div class="parallax"><img src="images/background2.jpg" alt="Unsplashed background img 1"></div>
  </div>


  <div class="container">
    <div class="section">

      <!--   Icon Section   -->



        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text"><i class="material-icons">group</i></h2>
            <h5 class="center">Search Engine</h5>

            <p class="center light">Please enter the text in the search text area :</p>
          </div>
        </div>


<form method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> >

<div class="row">
    <div class="input-field col s12">  

       <div class="input-field col s6 offset-s3">
          <select name="creneau">
            <option value="" disabled selected>Choose your time slot...</option>
            <option value="1">2 -> 4 july 2016</option>
            <option value="2">5 -> 8 july 2016</option>
            <option value="3">9 -> 11 july 2016</option>
           </select>
       <label>Select Your time slot :</label>
      </div>

  <div class="row">
    <div class="col s12">
      <div class="row">
      
        <div class="input-field col s6 offset-s2">
          <i class="material-icons prefix">textsms</i>
          <input type="text" id="autocomplete-input" class="autocomplete" name="searchTweets">
          <label for="autocomplete-input">Search...</label>
        </div>

          <div class="input-field col s4 ">          
          <button class="btn waves-effect waves-light" type="submit" name="action">Search...
          <i class="material-icons right valign">search</i>
         </button>
        </div>
        
      </div>
    </div>
  </div>

  
    

    </div>
  </div>

</form>


    </div>
  </div>

  <?php
    //on affiche le bloc que lors d'une recherche
    if(isset($_POST['searchTweets']) or isset($_GET['searchTweets']))
    {


      //test si click sur page
      if(isset($_GET['page']))
        {
          $searchKeyword = $_GET['searchTweets'];
        }
        else
        {
          $searchKeyword = $_POST['searchTweets'];
        }

      //instance de recherche
       $searchResult = new Search($searchKeyword, $dataBase);
       //comptage des enregistrements
       $countReccord = $searchResult->countAnalytics();
       //traitement de la requete
       if($searchKeyword=="")
       {
        $countReccord=0;
       }
      // echo $countReccord;
       $pagination = new pagination($countReccord);
       $limit = $pagination->getLimit();
       $skip = $pagination->getSkip();

       //echo "<br>limite index :".$limit;
       //echo "<br> skip index : ".$skip;
       //envoie des resultats avec gestion de la pagination
       $displayResult = $searchResult->getAnalytics($limit, $skip);



      
      ?>
      <div class="row">
          <div class="col s12">
            
               <h5  class="center">Result of the request :</h5>
                <?php //si retour vide
                  //calcul du nombre de résultat pour pagination et / ou retour vide
                  if(empty($displayResult) or $countReccord ==0)
                    {
                      echo "<p class=\"center\">Sorry, no result for this request... </p>";
                    }
                  else
                      {
                        //echo "nombre d'enregistrements : ".$countReccord;
                 ?>
                  <ul class="collection col s8 offset-s2">
                   <?php //boucle d'affichage
                      foreach ($displayResult as $doc)
                      {?>
                      <li class="collection-item avatar">
                       <img src="<?php echo ($doc["user"]["profile_image_url"]); ?>" alt="" class="circle"> 
                       <span class="title"> <?php echo ($doc["user"]["name"]); ?></span>
                        <p><?php echo ($doc["created_at"]); ?> </p>
                        <p class="left"><?php echo ($doc["text"]); ?> </p>

                        <div class="row">
                          <div class="col s12 m6">
                            <div class="card indigo lighten-4">
                              <span class="card-title">Sentiment analytics : </span>
                                 <p>Dominant sentiment : <?php echo $searchResult->getClass() ; ?></p>
                                 <p><?php print_r($searchResult->getScore()) ; ?></p>
                                 <div class="card-action"></div>
                            </div>
                          </div>
                        </div>

                        
                      </li>
                   <?php 
                     //End foreach
                        }
                       } ?>
                 </ul>
               
          </div>
      </div>
      <div class="row">
        <div class="col s6 offset-s3">
            <?php
               //affichage pagination
                $affichagePagination = $pagination->affichagePagination();
            ?>
        </div>
      </div>
    <?php
     
    }
    //destruction de la variable
    unset($_POST['searchTweets']);
  ?>


  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
         
        </div>
      </div>
    </div>
    <div class="parallax"><img src="images/background3.jpg" alt="Unsplashed background img 2"></div>
  </div>




 

  <footer class="page-footer teal">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Made by</h5>
          <p class="grey-text text-lighten-4">Module développé dans le cadre du cours de Marc SPANIOL, Université de Caen / Basse Normandie par Alexandre DUCREUX et Frédéric LORENCE.</p>


        </div>
        
        
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="brown-text text-lighten-3" href="http://materializecss.com">Materialize</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>

  </body>
</html>
