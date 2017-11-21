(function () {
"use strict";

angular.module('ResourceApp')
.component('showResults', {
  templateUrl: 'src/home/show-results/show-results.html',
  bindings: {
    list:  '<'
  },
    controller: showController
});

showController.$inject = ['HomeService', '$scope', '$element']
function showController(HomeService, $scope, $element) {
  var $ctrl = this;
  $ctrl.watched = false;
  $scope.$on('youtube.player.playing', function ($event, player) {
    console.log("which vid: ", $ctrl.list.numid);
    HomeService.watched($ctrl.list.numid,'numviews')
      .then(function (response){
        console.log("done");
      })
      .catch(function (error) {
        console.log(error);
      });
  });

  $ctrl.didClick = function(){
    HomeService.watched($ctrl.list.numid,'numviews')
      .then(function (response){
        console.log("done");
      })
      .catch(function (error) {
        console.log(error);
      });
  }

  // $ctrl.didwatch = function() {
  //   $ctrl.watched=true;
  //   console.log("here", $ctrl.watched);
  // };
}

})();
