(function () {
'use strict';

angular.module('ResourceApp')
.config(RoutesConfig);

RoutesConfig.$inject = ['$stateProvider', '$urlRouterProvider'];
function RoutesConfig($stateProvider, $urlRouterProvider) {

  // Redirect to home page if no other URL matches
  $urlRouterProvider.otherwise('/');

  // *** Set up UI states ***
  $stateProvider

  // Home page
  .state('home', {
    url: '/',
    controller: 'HomeController',
    controllerAs: 'hctrl',
    templateUrl: 'src/home/home.html',
    resolve: {
      fplist: ['HomeService',function (HomeService) {
        return HomeService.FrontPage();
      }],
      tags: ['HomeService',function (HomeService) {
        return HomeService.getTags();
      }],
      prompts: ['HomeService',function (HomeService) {
        return HomeService.getPrompts();
      }],
      events: ['HomeService', function (HomeService) {
        return HomeService.getEvents();
      }],
      tools: ['HomeService', function (HomeService) {
        return HomeService.getTools();
      }]
    }
  })

    .state('feedback', {
      url: '/feedback',
      controller: 'FeedbackController',
      controllerAs: 'fctrl',
      templateUrl: 'src/feedback/feedback.html'
    })

  .state('admin', {
    url: '/admin',
    controller: 'AdminController',
    controllerAs: 'adctrl',
    templateUrl: 'src/admin/admin.php',
    resolve: {
      pending: ['AdminService',function (AdminService) {
        return AdminService.getPending();
      }],
      netids: ['AdminService',function (AdminService) {
        return AdminService.GetNetids();
      }],
      dates: ['AdminService',function (AdminService) {
        return AdminService.GetDates();
      }]
    }
  });
}

})();
