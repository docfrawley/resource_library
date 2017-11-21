(function () {
"use strict";

angular.module('ResourceApp')
.component('showcase', {
  templateUrl: 'src/home/showcase/showcase.html',
  controller: scController,
  bindings: {
    title:    '@',
    list:    '<'
  }
});

scController.$inject = ['HomeService', '$scope', '$element']
function scController(HomeService, $scope, $element) {
  var $ctrl = this;
  $ctrl.watched = false;
  $ctrl.$onInit = function () {
    $ctrl.numid = $ctrl.list.numid;
  };
  $scope.$on('youtube.player.playing', function ($event, player) {
    HomeService.watched($ctrl.numid,'numviews')
      .then(function (response){
        console.log("done");
      })
      .catch(function (error) {
        console.log(error);
      });
  });

  // $ctrl.didwatch = function() {
  //   $ctrl.watched=true;
  //   console.log("here", $ctrl.watched);
  // };
}

})();
