var app = angular.module('application', [
  'ui.router',
  'ngAnimate',
  'foundation',
  'foundation.dynamicRouting',
  'foundation.dynamicRouting.animations'
])
  .config(config)
  .run(run);

config.$inject = ['$urlRouterProvider', '$locationProvider'];

  function config($urlProvider, $locationProvider) {
    $urlProvider.otherwise('/production');

    $locationProvider.html5Mode({
      enabled:false,
      requireBase: false
    });

    $locationProvider.hashPrefix('!');
  }


function run($rootScope) {
  FastClick.attach(document.body);
}


app.controller('AppWrapper', function($scope, $rootScope) {
    $scope.tab = 1;
    $scope.setTab = function(tab){
      $scope.tab = tab;
    }
});
app.controller('ApprovalController', function($scope, $rootScope) {
    $rootScope.pageTitle = "Approval";
});
app.controller('CopyController', function($scope, $rootScope) {
    $rootScope.pageTitle = "Test Order";

    $scope.library = [
        {
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },        {
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },        {
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },        {
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },        {
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },        {
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
    ];
    console.log($scope.library);

});
app.controller('HeaderController', function($scope, $rootScope) {


    $scope.purpose = [
        {
          id: '1',
          name: 'Attention Grabbing',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: '2',
          name: 'Color Splash',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: '3',
          name: 'With Motion',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: '4',
          name: 'Special Event',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },{
          id: '5',
          name: 'Spot Specific',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: '6',
          name: 'Tag Out',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        }
    ];

    $scope.typeOfAd = [
        {
          id: 'type1',
          name: 'Audio Revision',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'type2',
          name: 'Remix',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          id: 'type3',
          name: 'Video Revision',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        }
    ];

});
app.controller('OrderController', function($scope, $rootScope) {
    $rootScope.pageTitle = "Mark's Orders";
});
app.controller('ProductionController', function($scope, $rootScope) {
    $rootScope.pageTitle = "Tape 1 Production";


    $scope.library = [
        {
          active: 'fa-unlock-alt',
          id: 'M594D0',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          active: 'fa-unlock-alt',
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          active: 'fa-lock',
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          active: 'fa-lock',
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },{
          active: 'fa-lock',
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          active: 'fa-lock',
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },{
          active: 'fa-lock',
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          active: 'fa-lock',
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },{
          active: 'fa-lock',
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          active: 'fa-lock',
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },{
          active: 'fa-lock',
          id: 'M5940',
          name: 'Tape 1',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          active: 'fa-lock',
          id: 'M5988',
          name: 'Extra Run',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
    ];

});
app.controller('StoryController', function($scope, $rootScope) {
    $rootScope.pageTitle = "Mark's Orders";


    $scope.story = [
        {
          id: '1',
          name: 'Scene 1',
          video: 'video text',
          audio: 'audio text',
          updated: '10/06/2016'
        },
        {
          id: '2',
          name: 'Scene 2',
          video: 'video text',
          audio: 'audio text',
          updated: '10/06/2016'
        },
        {
          id: '3',
          name: 'Scene 3',
          video: 'video text',
          audio: 'audio text',
          updated: '10/06/2016'
        },
        {
          id: '4',
          name: 'Scene 4',
          video: 'video text',
          audio: 'audio text',
          updated: '10/06/2016'
        }
    ];


    $scope.files = [
        {
          type: 'fa-volume-up',
          id: '1',
          name: 'Audio Sample',
          updated: '10/06/2016'
        },
        {
          type: 'fa-film',
          id: '2',
          name: 'Video Effect',
          updated: '10/06/2016'
        },
        {
          type: 'fa-volume-up',
          id: '3',
          name: 'Audio Sample Updated',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        },
        {
          type: 'fa-film',
          id: '4',
          name: 'Video Cuts',
          length: '30',
          path: 'url',
          updated: '10/06/2016'
        }
    ];



});