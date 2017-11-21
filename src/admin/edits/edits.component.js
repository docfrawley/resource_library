(function () {
"use strict";

angular.module('ResourceApp')
.component('makeEdits', {
  templateUrl: 'src/admin/edits/makeedits.php',
  controller: editsController,
  bindings: {
    title:        '@'
  }
});

editsController.$inject = ['HomeService', 'AdminService', '$scope', '$element',  '$animate', '$log']
function editsController(HomeService, AdminService, $scope, $element, $animate, $timeout, $q, $log) {
  var $ctrl = this;

  $ctrl.$onInit = function () {
    $ctrl.madeDelete = false;
  };

  $ctrl.updated = false;
  $ctrl.editing = false;
  $ctrl.gotoEdit = false;
  $ctrl.simulateQuery = false;
  $ctrl.isDisabled    = false;
  $ctrl.noCache = false;

  $ctrl.clearSearch = function (actionType) {
    if (actionType=='updated'){
      $ctrl.gotoEdit = false;
      $ctrl.updated = true;
    } else {
      $ctrl.resetAll();
    }
  };

  $ctrl.editUpdate = function (index){
    $ctrl.gotoEdit = true;
    $ctrl.toEdit = false;
    $ctrl.madeDelete = false;
    $ctrl.which_resource = $ctrl.ed_resources[index];
  };

  $ctrl.madeDelete = false;
  $ctrl.deleteUpdate = function (index){
    $ctrl.madeDelete = false;
    $ctrl.which_resource = $ctrl.ed_resources[index];
  };

  $ctrl.resetAll = function(){
    $ctrl.toEdit = false;
    $ctrl.gotoEdit = false;
    $ctrl.showSuperList = false;
    $ctrl.updated = false;
  }

  $ctrl.doDelete=function(status){
    AdminService.doDelete($ctrl.which_resource.numid, status)
    .then(function (response){
      $ctrl.whathappened = (response.data.success=='ps')
        ? "reduced to pending status":'deleted';
      $ctrl.madeDelete = true;
      console.log("what happened:", response.data);
    })
    .catch(function (error) {
      console.log(error);
    });

  }

  $ctrl.searchBy = "";
  $ctrl.showSuperList = false;
  $ctrl.showForm = false;
  $ctrl.search = "";
  $ctrl.selectedItem = "";

  $ctrl.howSearch=function(howDo){
    $ctrl.notag = false;
    $ctrl.updated = false;
    $ctrl.toEdit = false;
    $ctrl.gotoEdit = false;
    $ctrl.searchwhat = howDo;
    $ctrl.showForm = false;
    $ctrl.search = "";
    $ctrl.searchText = "";
    $ctrl.selectedItem = "";

    switch (howDo) {
      case 'latest':
      AdminService.getResources('latest', 'none')
      .then(function (response){
        $ctrl.ed_resources = response.data;
        $ctrl.showForm = false;
        $ctrl.showSuperList = true;
      })
      .catch(function (error) {
        console.log(error);
      });
      break;
      case 'tag':
        HomeService.getTags()
        .then(function (response){
          $ctrl.states_array = response.data;
          $ctrl.states = [];
          for (var x=0; x<$ctrl.states_array.length; x++){
            $ctrl.states.push($ctrl.states_array[x].display);
          }
          $ctrl.showSuperList = false;
          $ctrl.showForm = true;
        })
        .catch(function (error) {
          console.log(error);
        });
        break;
      case 'title':
        AdminService.getTitles()
        .then(function (response){
          $ctrl.states_array = response.data;
          $ctrl.states = [];
          for (var x=0; x<$ctrl.states_array.length; x++){
            $ctrl.states.push($ctrl.states_array[x].display);
          }
          $ctrl.showSuperList = false;
          $ctrl.showForm = true;
        })
        .catch(function (error) {
          console.log(error);
        });
      break;
      case 'person':
        AdminService.getUsers()
        .then(function (response){
          $ctrl.states_array = response.data;
          $ctrl.states = [];
          for (var x=0; x<$ctrl.states_array.length; x++){
            $ctrl.states.push($ctrl.states_array[x].display);
          }
          $ctrl.showSuperList = false;
          $ctrl.showForm = true;
        })
        .catch(function (error) {
          console.log(error);
        });
      break;
      default:
        break;
    };
  }

  $ctrl.querySearch = function (query) {
    var results = query ? $ctrl.states_array.filter( createFilterFor(query) ) : $ctrl.states_array,
        deferred;
      return results;
  }

  function createFilterFor(query) {
    var lowercaseQuery = angular.lowercase(query);
    return function filterFn(state) {
      var thestate = angular.lowercase(state.display);
      return (thestate.indexOf(lowercaseQuery) === 0);
    };

  }

  $ctrl.search = "";
  $ctrl.notag = false;

  $ctrl.newSearch = function(){
    $ctrl.search = "";
    $ctrl.notag = false;
    $ctrl.searchText = "";
    $ctrl.toEdit = false;
  }
  $ctrl.goSearch = function(){
    $ctrl.updated = false;
    $ctrl.showForm = false;
    $ctrl.search = $ctrl.searchText;
    console.log("go search: ", $ctrl.search, $ctrl.searchwhat);
    if ($ctrl.states.indexOf($ctrl.search)>-1){
      AdminService.getResources($ctrl.searchwhat, $ctrl.search)
      .then(function (response){
        $ctrl.toEdit = true;
        $ctrl.ed_resources = response.data;
        console.log($ctrl.ed_resources);
        // $ctrl.moreThanOne = ($ctrl.ed_res.length>1);
        // if (!$ctrl.moreThanOne){
        //   $ctrl.ed_resources = $ctrl.ed_res[0];
        // } else {
        //   $ctrl.ed_resources = $ctrl.ed_res;
        // }
      })
      .catch(function (error) {
        console.log(error);
      });
    } else {
      $ctrl.notag = true;
    }

  };


}

})();
