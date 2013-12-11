<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>Nos Bons plans de Montréal</title>
    <!--leaflet stylesheet-->
    <link rel="stylesheet" href="css/leaflet.css" />
    <link rel="stylesheet" href="css/leaflet.usermarker.css" />
    <link rel="stylesheet" href="css/style_v2.css" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

    <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/leaflet.ie.css" />
    <![endif]-->

</head>
<body ng-app='bonPlans' ng-controller="BonPlansCtrl">

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Montréal Bons Plans !</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Bons Plans</a></li>
            <li><a href="#about">Information</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <hr>
    <div class="container">
        <div class="page-header">
            <center><h1>Montréal-Nous tes bons plans sur Montréal et ses alentours !</h1></center>
            <center><p class="lead">Afin de réunir en un seul et même endroit tous nos bons plans. Dans un soucis de clarté et de simplicité, nous avons souhaité
                organiser tout cela sur cette page web qui va nous permettre, à nous comme à vous, d'explorer les coins sympas et les meilleures places de Montréal ! Bonne découverte !</p></center>
            <center><p><a class="btn btn-xs btn-default" href="http://montrealnous.blogspot.ca" role="button">Revenir au Blog</a></p></center>
        </div>

        <div id="badgeCount row">
            <span id="badgeCount" class="badge">{{nbLieux}}</span> Itineraires et <span class="badge">{{nbIti}}</span> Lieux
        </div>
        <leaflet id="map_canvas" center="center" defaults="defaults" event-broadcast="events" geojson="lieux" tiles='tiles'></leaflet>
    </div>

    <div class="container">
        <div class="btn-group btn-group-justified col-lg-12" id='btnLayer' data-toggle="buttons">
            <label id="osm" class="btn btn-primary active" ng-click="setBaseLayer('osm')">
                <input type="radio" name="options"></input>
                    OpenstreetMap
            </label>
            <label id="cycle" class="btn btn-primary" ng-click="setBaseLayer('cycle')">
                <input type="radio" name="options" checked></input>
                    Vélo
            </label>
            <label id="tourist" class="btn btn-primary" ng-click="setBaseLayer('tourist')">
                <input type="radio" name="options"></input>
                    Tourisme
            </label>
            <label id='toner' class="btn btn-primary" ng-click="setBaseLayer('toner')" ng-model="nb" ng-class="{active: nb}">
                <input type="radio" name="options" ></input>
                    Noir et Blanc
            </label>
        </div>
    </div>
    <hr>
    <div class="container">
        <section id="sectionLieux" ng-repeat="place in filteredLieux = (lieux.data.features | filter: isLieu)" ng-class="{selected : place.selected}">
            <div class="row">   
                <div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <div class="panel-title">
                            <div class="row">
                                <div class="col-xs-7 col-sm-7 col-lg-9">
                                    <h4>{{place.properties.Nom}}</h4>
                                </div>
                                <div id="hearts" class="col-xs-5 col-sm-5 col-lg-3">
                                    <h5 ng-repeat="i in getNumber(place.properties.Note)" class="glyphicon glyphicon-heart"></h5>
                                    <h5 ng-class="{'first':$first}" ng-repeat="i in getNumber(5-place.properties.Note)" class="glyphicon glyphicon-heart-empty"></h5>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                               <div class="row">
                                    <div class="col-xs-10">
                                        <center><strong>Catégorie</strong></center>  
                                        <h4><center>{{place.properties.Categorie}}</center></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-11 col-xs-offset-1" id="dateTest">
                                        <i class="glyphicon glyphicon-ok-circle"></i><h5>Testé le : {{place.properties.DateTest}}</h5>
                                        <p><a href="{{place.properties.Web}}">Site Internet</a></p>
                                    </div>
                                </div>
                            </div>
                            <div id="panelComm" class="col-xs-6">
                                <div class="panel panel-info">
                                  <div class="panel-heading">
                                    <h3 class="panel-title">Adresse</h3>
                                  </div>
                                  <div class="panel-body">
                                    {{place.properties.Adresse}}
                                  </div>
                                </div> 
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-xs-10 col-xs-offset-1 well well-sm">{{place.properties.Comm}}</div>
                        </div>
                      </div>
<!--                       <div class="panel-footer">
                        <h5>Testé le : {{place.properties.DateTest}}</h5>
                      </div> -->
                    </div>
                </div>
                <div class="col-xs-11 col-sm-4 col-md-4 col-lg-4">
                    <ul class="ulNone">
                      <li ng-repeat="img in place.properties.Photo">
                        <a href="Data/Photos/{{img.URL}}">
                            <img class="img-responsive img-thumbnail" alt="Responsive image" ng-src="Data/Photos/{{img.URL}}">
                        </a>
                        <span><center>{{img.Legende}}</center></span> 
                      </li>
                    </ul>  
                </div>
            </div> 
        </section>
    </div>
    <div class="container">
        <section id="sectionIti" ng-repeat="place in filteredIti = (lieux.data.features | filter: isIti)" ng-class="{selected : place.selected}">
            <div class="row">   
                <div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <div class="panel-title">
                            <div class="row">
                                <div class="col-xs-7 col-sm-7 col-lg-9">
                                    <h4>{{place.properties.Nom}}</h4>
                                </div>
                                <div id="hearts" class="col-xs-5 col-sm-5 col-lg-3">
                                    <h5 ng-repeat="i in getNumber(place.properties.Note)" class="glyphicon glyphicon-heart"></h5>
                                    <h5 ng-class="{'first':$first}" ng-repeat="i in getNumber(5-place.properties.Note)" class="glyphicon glyphicon-heart-empty"></h5>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <center><h4 class="well well-sm">Thème : {{place.properties.Theme}}</h4></center>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <center><h4 class="well well-sm">Catégorie : {{place.properties.Categorie}}</h4></center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <center><h4><strong>Départ</strong></h4></center> 
                                <center><p>{{place.properties.Depart}}</p></center>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <center><h4><strong>Arrivée</strong></h4></center> 
                                <center><p>{{place.properties.Arrivee}}</p></center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="listEtap col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <a href="#" class="list-group-item active">Etapes</a>
                                <div ng-repeat="etap in place.properties.Etape">
                                    <div class="media list-group-item">
                                      <a class="pull-left">
                                        <span class="badge">{{etap.Numero}}</span>
                                      </a>
                                      <div class="media-body">
                                        <span class="media-heading">{{etap.Descriptif}}</span>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="panel-footer">
                        <div class="row">
                            <strong>   
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                <center>{{place.properties.NbJour}} jours</center>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <center>Durée {{place.properties.Duree}}</center>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5">
                                <center>Distance {{place.properties.Km}} Km</center>
                            </div>
                            </strong>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-xs-11 col-sm-4 col-md-4 col-lg-4">
                    <ul class="ulNone">
                      <li ng-repeat="img in place.properties.Photo">
                        <a href="Data/Photos/{{img.URL}}">
                            <img class="img-responsive img-thumbnail" alt="Responsive image" ng-src="Data/Photos/{{img.URL}}">
                        </a>
                        <span><center>{{img.Legende}}</center></span> 
                      </li>
                    </ul>  
                </div>
            </div> 
        </section>
    </div>
    
      <hr>

      <footer class="container">
        <p><i>&copy; Camille et Marion 2013</i></p>
      </footer>
    </div> <!-- /container -->

    <!--libraries-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="js/leaflet.usermarker.js"></script>
    <script src="js/angular.js"></script>
    <script src="js/angular-leaflet-directive.min.js"></script>
    <script src="js/angular-route.min.js"></script>
    <script src="js/angular-touch.min.js"></script>
    <script src="js/app.js"></script>


</body>
</html>
