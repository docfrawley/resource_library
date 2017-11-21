(function () {
"use strict";

angular.module('ResourceApp')
.component('userAdmin', {
  templateUrl: 'src/admin/useradmin/useradmin.html',
  controller: uaController,
  bindings: {
    title:      '@'
  }
});

uaController.$inject = ['AdminService', '$scope', '$element']
function uaController(AdminService, $scope, $element) {
  var $ctrl = this;

$ctrl.madeAction = false;

  $ctrl.$onInit = function () {
    AdminService.getUsers()
    .then(function (response){
      $ctrl.user_list = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
  };

  $ctrl.whatDo = function(index, what){
    $ctrl.whatDid = "";
    $ctrl.madeAction = false;
    $ctrl.whatDoing = what;
    if (what=='editUser') {
      AdminService.getUser($ctrl.user_list[index].numindex)
      .then(function(response){
        $ctrl.user = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    } else if (what=='addUser') {
      $ctrl.user = {};
    } else {
      $ctrl.user = $ctrl.user_list[index];
    }
  };

  $ctrl.aOreUser = function(aOre){
    console.log($ctrl.user);
    AdminService.adOredUser(aOre, $ctrl.user)
    .then(function(response){
      console.log((response.data));
      AdminService.getUsers()
      .then(function (response){
        $ctrl.user_list = response.data;
        $ctrl.madeAction = true;
      })
      .catch(function (error) {
        console.log(error);
      });
    })
    .catch(function (error) {
      console.log(error);
    });
  };

  $ctrl.deleteUser = function(){
    AdminService.deleteUser($ctrl.user.numindex)
    .then(function(response){
      AdminService.getUsers()
      .then(function (response){
        $ctrl.user_list = response.data;
        $ctrl.madeAction = true;
      })
      .catch(function (error) {
        console.log(error);
      });
    })
    .catch(function(error){
      console.log(error);
    });
  }

}

})();
