var app = angular.module('bonPlans',[
    'ngResource',
    "leaflet-directive", 
    'ngTouch',
    'ui.bootstrap'
    ]);

app.factory('Places', ['$resource',
  function($resource){
    return $resource('Data/places/:placeID.json', {}, {
      query: {method:'GET', params:{placeID:'place'}, isArray:true}
    });
  }]);

app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/about', {
        templateUrl: 'partials/about.html'
        // controller: 'aboutCtrl'
      }).
      when('/contact', {
        templateUrl: 'partials/contact.html'
        // controller: 'contactCtrl'
      }).
      when('/bonplans', {
        templateUrl: 'partials/bon_plans.html',
        controller: 'BonPlansCtrl'
      }).
      when('/search', {
        templateUrl: 'partials/search.html',
        controller: 'BonPlansCtrl'
      }).
      when('/places/:placeID', {
        templateUrl: 'partials/place-details.html',
        controller: 'PlaceDetailCtrl'
      }).
      otherwise({
        redirectTo: '/bonplans'
      });
  }]);

app.controller('BonPlansCtrl', ["$scope", "$http" ,"$log", "leafletData", function ($scope, $http, $log, leafletData){

	var c = {
		osm:{url:"http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"},
		toner:{url:"http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png"},
		cycle:{url:"http://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png"},
		night:{url:"http://{s}.tile.cloudmade.com/{key}/{styleId}/256/{z}/{x}/{y}.png",
			options:{key:"007b9471b4c74da4a6ec7ff43552b16f",styleId:999}},
		tourist:{url:"http://{s}.tile.cloudmade.com/{key}/{styleId}/256/{z}/{x}/{y}.png",
			options:{key:"007b9471b4c74da4a6ec7ff43552b16f",styleId:7}
		}};

	var d;
	d=angular.copy(c.osm, d);

	$scope.setBaseLayer = function(a){ 
		var d=c[a],
		e=d.url;
		if(d.hasOwnProperty("options"))
			for(var f in d.options)
				d.options.hasOwnProperty(f)&&(e=e.replace("{"+f+"}",
					d.options[f]));
		$scope.tiles.url=e;
		$scope.tiles.name = a;

		$("#btnLayer label.active").toggleClass("active");
		$("#btnLayer #"+a).toggleClass("active");
	};

	$scope.fitBounds = function() {
        leafletData.getMap().then(function(map) {
            leafletData.getGeoJSON().then(function(obj){
            	map.fitBounds(obj.getBounds());
            });
        });
    };


    angular.extend($scope, {
        center: {
            lat: 45.506828,
            lng: -73.58505,
            zoom: 11
        },
        tiles:d,
        defaults: {
/*        	tileLayer: 'http://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png',
            tileLayerOptions: {
                opacity: 0.9,
                detectRetina: true,
                reuseTiles: true,
            },*/
            icon: {
		        url: 'http://cdn.leafletjs.com/leaflet-0.6.4/images/marker-icon.png',
		        retinaUrl: 'http://cdn.leafletjs.com/leaflet-0.6.4/images/marker-icon@2x.png',
		        size: [25, 41],
		        anchor: [12, 40],
		        popup: [0, -40],
		        shadow: {
		            url: 'http://cdn.leafletjs.com/leaflet-0.6.4/images/marker-shadow.png',
		            retinaUrl: 'http://cdn.leafletjs.com/leaflet-0.6.4/images/marker-shadow.png',
		            size: [41, 41],
		            anchor: [12, 40]
		        }
		    },
		    path: {
		        weight: 10,
		        opacity: 1,
		        color: '#0000ff'
		    },
            scrollWheelZoom: false
        }
    });

	$scope.lieux = [];
    $scope.nbLieux =  0;
	$scope.nbIti = 0;
	$scope.oldFeatureSelected = null;
    $scope.src = {};
    $scope.src.type = 'Tout';
    $scope.src.Cat = 'Tout';
    $scope.src.Theme = 'Tout';
    $scope.placeClass = 'srcSimple';

	$scope.isLieu = function(place) {
        return place.properties.Type === "Lieu";
    };
	$scope.isIti = function(iti) {
        return iti.properties.Type === "Itineraire";
    };

	$scope.getNumber = function(num) {
	    return new Array(num);   
	}
	
    $scope.geojsonClick = function(feature) {
        feature.selected = true;
        if($scope.oldFeatureSelected != null & $scope.oldFeatureSelected != feature)
        	$scope.oldFeatureSelected.selected = false;
        $scope.oldFeatureSelected = feature;

    }
  
    $scope.srcType = function(obj){

        return $scope.src.type == obj.properties.Type ? obj :
                $scope.src.type == "Tout" ? obj : null;
    }

    $scope.srcCat = function(obj){
        
        return $scope.src.Cat == obj.properties.Categorie ? obj :
                $scope.src.Cat == "Tout" ? obj : null;
    }

    $scope.srcTheme = function(obj){
        
        return $scope.src.Theme == obj.properties.Theme ? obj :
                $scope.src.Theme == "Tout" ? obj : null;
    }

	$http.get('Data/lieu.json').success(function(data){
        angular.extend($scope, {
            lieux: {
                data: data,
                resetStyleOnMouseout: true
            },
        });
		$scope.lieux.data.features.forEach(function(lieu){
            lieu.selected = true;
		});
		$scope.fitBounds();
		$scope.lieux.data.features.forEach(function(f){
			if (f.properties.Type == 'Lieu') {$scope.nbLieux += 1};
			if (f.properties.Type == 'Itineraire') {$scope.nbIti += 1};
		});	
	});

    $scope.$on("leafletDirectiveMap.geojsonClick", function(ev, featureSelected, leafletEvent) {
        $scope.geojsonClick(featureSelected);
    });
}]);

app.controller('navBarCtrl', ["$scope", function ($scope){

    $scope.menu = 'home';

}]);

app.controller('PlaceDetailCtrl', ['$scope', '$routeParams', 'Places',
  function($scope, $routeParams, Places) {
    $scope.place = Places.get({placeID: $routeParams.placeID}, function(place) {
      // $scope.mainImageUrl = place.images[0];
    });

    $scope.center = {
            lat: 45.506828,
            lng: -73.58505,
            zoom: 11
        };

    $scope.defaults = {
          tileLayer: 'http://www.toolserver.org/tiles/bw-mapnik/{z}/{x}/{y}.png',
            tileLayerOptions: {
                opacity: 0.9,
                detectRetina: true,
                reuseTiles: true
            }
        };

  }]);

