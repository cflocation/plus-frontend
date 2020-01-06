"use strict";angular.module("viewerApp",["ngAnimate","ngAria","ngCookies","ngMessages","ngResource","ngRoute","ngDialog","ngSanitize","angular-simple-sidebar","angular.filter"]).config(["$routeProvider",function(a){a.when("/:tab/:key",{templateUrl:"views/main.html",controller:"MainCtrl",controllerAs:"main"}).otherwise({redirectTo:"/"})}]),angular.module("viewerApp").controller("MainCtrl",["$scope","$sce","$timeout","$routeParams","$location","ngDialog","data",function(a,b,c,d,e,f,g){a.proposalTitle="",a.scheduleLines=[],a.proposalLineFixed=[],a.proposalLineRotator=[],a.uri="",a.state=!1,a.menuTitle="menu",a.tab=d.tab,a.proposalKey=d.key,a.sid="",a.setShow=function(d){var e=d.startDate.split(" "),f="http://ext.showseeker.com/spr2/"+d.callSign+"/"+e[0].trim()+"/"+e[1].trim()+"/"+d.tz+"/9084922e57724e74b37175d54b36fbe6";a.uri=b.trustAsResourceUrl(f),c(a.openWindow,500)},a.closeSchedule=function(){a.sid=""},a.openWindow=function(){a.state=!0},a.getPath=function(b){var c=b+"/"+a.proposalKey;e.path(c)},a.doSum=function(a){var b=0;return angular.forEach(a,function(a){b+=a.rate}),b},a.openSchedule=function(b){a.sid=b[0].showId,a.schedule=b},g.getProposal("proposalData",11619).then(function(b){angular.forEach(b.lines,function(b){angular.forEach(b.lines,function(b){1===b.lineType&&a.proposalLineFixed.push(b),2===b.lineType&&a.proposalLineRotator.push(b)})}),a.proposalTitle=b.name})}]),angular.module("viewerApp").controller("AboutCtrl",function(){this.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}),angular.module("viewerApp").service("data",["$http","$cookies","$q",function(a,b,c){var d=this;this.getProposal=function(b,e){var f=c.defer(),g="http://104.239.194.76/proposal/lines/"+e;return d[b]?f.resolve(d[b]):a({method:"GET",url:g,headers:{"Api-Key":"39534ec5f7fdd997cac0f073b244b4b2",User:"147"}}).then(function(a){f.resolve(a.data)}),f.promise}}]),angular.module("viewerApp").run(["$templateCache",function(a){a.put("views/about.html","<p>This is the about view.</p>"),a.put("views/main.html",'<angular-simple-sidebar state="state" uri="uri"></angular-simple-sidebar> <br> <center> <h1>{{proposalTitle}}</h1> </center> <br> <div class="row"> <div class="col-md-12"> <div class="panel panel-default"> <div class="panel-body panel-dark"> <div class="row"> <div class="col-xs-6 text-left"> <h3>Dave Hardy</h3>Account Executive <br>Spectrum Reach <br> <a href="mailto:daveh@vastadsales.com">daveh@vastadsales.com</a> <br> <a href="tel:530.271.1292">530.271.1292</a> </div> <div class="col-xs-6 text-right"><img src="images/user.2096b0ff.jpg" class="img-rounded" style="height:150px"></div> </div> </div> </div> </div> </div> <div class="btn-group btn-group-justified" role="group"> <div class="btn-group" role="group"> <button type="button" class="btn btn-default" ng-click="getPath(\'fixed\')" ng-class="{\'active\':tab==\'fixed\'}"><i class="fa fa-clock-o fa-2x" aria-hidden="true"></i> <br>Fixed</button> </div> <div class="btn-group" role="group"> <button type="button" class="btn btn-default" ng-click="getPath(\'rotators\')" ng-class="{\'active\':tab==\'rotators\'}"><i class="fa fa-retweet fa-2x" aria-hidden="true"></i> <br>Rotators</button> </div> <div class="btn-group" role="group"> <button type="button" class="btn btn-default"><i class="fa fa-globe fa-2x" aria-hidden="true"></i> <br>Digital</button> </div> </div> <br> <div ng-show="tab==\'fixed\'"> <div class="row block-grid-lg-4 block-grid-md-3 block-grid-sm-3 block-grid-xs-2"> <div class="block-grid-item" ng-repeat="(key, value) in proposalLineFixed | groupBy: \'showId\'"> <div ng-class="sid==value[0].showId ? \'wells-off\' : \'wells\'"> <center> <img ng-src="{{value[0].thumb}}" class="img-responsive" ng-click="setShow(value[0])"> <div class="rates"> <div class="row"> <div class="col-xs-6 text-left">Spots: {{value.length}}</div> <div class="col-xs-6 text-right">Total: ${{doSum(value)}}</div> </div> </div> <div class="row"> <div class="col-xs-6 text-left"><img ng-src="https://showseeker.s3.amazonaws.com/images/netwroklogo/75/{{value[0].stationId}}.png" class="wh2"></div> <div class="col-xs-6 text-right"> <div class="cal"> <i class="fa fa-calendar fa-2x" aria-hidden="true" ng-click="openSchedule(value)"></i> </div> </div> </div> </center> </div> <div ng-show="sid==value[0].showId" class="scheduler"> <div class="container"> <div class="sschedule"> <div class="row padder"> <div class="col-xs-6 text-left titem-l">Schedule</div> <div class="col-xs-6 text-right titem" ng-click="closeSchedule()"><i class="fa fa-times-circle fa-2x" aria-hidden="true"></i></div> </div> <div ng-repeat="row in schedule"> <div class="schedule-row"> <span ng-if="row.epiTitle"><h4><i>{{row.epiTitle}}</i></h4></span> <h4>{{row.dayFormat}} {{row.startDateFmt}} at {{row.startTimeFmt}}</h4> <div class="nrate"> <h4>${{row.rate}}</h4> </div> </div> </div> <div> </div> </div> </div> </div> </div> </div> </div> <div ng-show="tab==\'fixed\'"> <br><br> <div ng-repeat="row in proposalLineFixed"> <div class="row" style="padding-left:15px;padding-right:15px"> <div class="panel panel-default"> <div class="panel-heading"> <div class="row"> <div class="col-xs-6"> <h3 class="panel-title">{{row.title}}</h3> </div> <div class="col-xs-6 text-right"> <h3 class="panel-title"><button class="btn btn btn-default" ng-click="setShow(row)"><i class="fa fa-info-circle" aria-hidden="true"></i> More Info</button></h3> </div> </div> </div> <div class="panel-body panel-dark"> <div class="col-xs-2"><img ng-src="{{row.thumb}}" class="img-responsive"></div> <div class="col-xs-8"> <h4>{{row.zoneName}}</h4> <h5>{{row.dayFormat}} {{row.startDateFmt}} at {{row.startTimeFmt}}</h5> <span ng-if="row.epiTitle"><i>{{row.epiTitle}}</i><br></span> <h4><span class="label label-primary">{{row.genre1}}</span> <span class="label label-primary">{{row.genre2}}</span></h4> <h4><span class="label label-success">{{row.new}}</span></h4> <h4><span class="label label-primary">${{row.rate}}</span></h4> </div> <div class="col-xs-2 text-right"> <img ng-src="https://showseeker.s3.amazonaws.com/images/netwroklogo/75/{{row.stationId}}.png" class="wh"> </div> </div> </div> </div> </div> </div> <div ng-show="tab==\'rotators\'"> <div ng-repeat="row in proposalLineRotator"> <div class="row" style="padding-left:15px;padding-right:15px"> <div class="panel panel-default"> <div class="panel-heading"> <div class="row"> <div class="col-xs-12"> <h3 class="panel-title">{{row.title}}</h3> </div> </div> </div> <div class="panel-body panel-dark"> <div class="col-xs-10"> <h4>{{row.zoneName}}</h4> <h5>{{row.dayFormat}} {{row.startDateFmt}} at {{row.startTimeFmt}}</h5> <span ng-if="row.epiTitle"><i>{{row.epiTitle}}</i><br></span> <h4><span class="label label-primary">{{row.genre1}}</span> <span class="label label-primary">{{row.genre2}}</span></h4> <h4><span class="label label-success">{{row.new}}</span></h4> <h4><span class="label label-primary">${{row.rate}}</span></h4> </div> <div class="col-xs-2 text-right"> <img ng-src="https://showseeker.s3.amazonaws.com/images/netwroklogo/75/{{row.stationId}}.png" class="wh"> </div> </div> </div> </div> </div> </div> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>'),a.put("partials/schedule.html",'<div ng-controller="MainCtrl"> {{scheduleLines}} <div ng-repeat="row in schedule"> </div> </div>'),a.put("partials/showcard.html",'<aside class="ass-aside-menu ass-slide-{{slide}}" style="background-color: #0a141a"> <iframe style="border:0;height:800px;width:320px" ng-src="{{uri}}"></iframe> </aside> <div ng-if="state" class="ass-aside-overlay" ng-click="closeSidebar()"></div>')}]);