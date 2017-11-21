(function () {
"use strict";

angular.module('ResourceApp')
.component('thumbs', {
  templateUrl: 'src/home/thumbs-component/thumbs.html',
  controller: thumbsController,
  bindings: {
    numviews:     '<',
    thumbsup:     '<',
    thumbsdown:   '<',
    numid:        '<'
  }
});

thumbsController.$inject = ['HomeService', '$scope', '$element']
function thumbsController(HomeService, $scope, $element) {
  var $ctrl = this;
  // $ctrl.tup = $ctrl.thumbsup;
  // $ctrl.tdown = $ctrl.thumbsdown;
  $ctrl.pressed = 'free';
  $ctrl.thumbing = function(whichone){
    HomeService.thumbchange($ctrl.numid, whichone, $ctrl.pressed)
      .then(function (response){
        switch (whichone) {
          case 'thumbsup':
            if ($ctrl.pressed=='free'){
              $ctrl.thumbsup++;
              $ctrl.pressed = 'thumbsup';
            } else if ($ctrl.pressed=='thumbsup') {
              $ctrl.thumbsup--;
              $ctrl.pressed='free';
            } else{
              $ctrl.thumbsup++;
              $ctrl.thumbsdown--;
              $ctrl.pressed='thumbsup';
            }
          break;
          case 'thumbsdown':
            if ($ctrl.pressed=='free'){
              $ctrl.thumbsdown++;
              $ctrl.pressed = 'thumbsdown';
            } else if ($ctrl.pressed=='thumbsdown') {
              $ctrl.thumbsdown--;
              $ctrl.pressed='free';
            } else{
              $ctrl.thumbsdown++;
              $ctrl.thumbsup--;
              $ctrl.pressed = 'thumbsdown';
            }
          break;
          default:
          break;
        };
        console.log("all stuff: ", $ctrl.thumbsup, $ctrl.thumbsdown, $ctrl.pressed);
      })
      .catch(function (error) {
        console.log(error);
      });
  };

}

})();
