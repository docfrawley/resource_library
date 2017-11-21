(function () {
    'use strict';

    angular.module('ResourceApp', ['ui.router', 'common', 'youtube-embed',
        'ui.bootstrap', 'ngAnimate', 'ngMaterial', 'ngFileUpload',
        'ui.calendar', 'angular-google-analytics'])
        .config(['AnalyticsProvider', function (AnalyticsProvider) {
        // Add configuration code as desired
        AnalyticsProvider.setAccount('UA-107515321-1'); 
    }]).run(['Analytics', function (Analytics) { }]);
        
})();