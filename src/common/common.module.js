(function () {
'use strict';

angular.module('common', ['ngFileUpload'])
.config(config);

config.$inject = ['$httpProvider'];
function config($httpProvider) {
    $httpProvider.interceptors.push('loadingHttpInterceptor');
}

})();
