(function () {
"use strict";

angular.module('ResourceApp')
.component('pendingResources', {
  templateUrl: 'src/admin/pending/pending.html',
  controller: pendingController,
  bindings: {
    title:      '@',
    list:       '<',
    updatePendings: '&'
  }
});

pendingController.$inject = ['AdminService', 'HomeService', '$scope', '$element']
function pendingController(AdminService, HomeService, $scope, $element, $timeout) {
  var $ctrl = this;

$ctrl.madeAction = false;

  $ctrl.$onInit = function () {
    $ctrl.madeAction = false;
  };

  $ctrl.approveUpdate = function (index){
    $ctrl.resource = $ctrl.list[index];
    $ctrl.tagstring = $ctrl.resource.tags;
    $ctrl.rtags = $ctrl.tagstring.split(",");
    HomeService.getTags()
    .then(function (response){
      $ctrl.tags = response.data;
      $ctrl.tagsin = [];
      for (var i = 0; i < $ctrl.tags.length; i++) {
        var temp_array=[];
        temp_array.value = $ctrl.tags[i].value;
        temp_array.display = $ctrl.tags[i].display;
        temp_array.id = $ctrl.tags[i].id;
        temp_array.show = ($ctrl.rtags.includes(temp_array.id));
        $ctrl.tags[i].show = !temp_array.show;
        $ctrl.tagsin.push(temp_array);
        $ctrl.whatDo = 'approve';
      }
    })
    .catch(function (error) {
      console.log(error);
    });
  };

  $ctrl.deleteUpdate = function (index){
    $ctrl.resource = $ctrl.list[index];
    $ctrl.tagstring = "";
    $ctrl.tagstring = $ctrl.resource.tags;
    $ctrl.whatDo = 'delete';
  };

  $ctrl.deleteResource = function () {
    AdminService.dResource($ctrl.resource.numid)
      .then(function (response){
        AdminService.getPending()
        .then(function (response){
          $ctrl.list = response.data;
          $ctrl.updatePendings();
          $ctrl.madeAction = true;
        });
      })
      .catch(function (error) {
        console.log(error);
      });
  };

  $ctrl.changeShow = function(index){
    $ctrl.tags[index].show = !$ctrl.tags[index].show;
    $ctrl.tagsin[index].show = !$ctrl.tagsin[index].show;
  };

  $ctrl.approveResource = function(){
    var tagstring= "";
    for (var i = 0; i < $ctrl.tagsin.length; i++) {
      if ($ctrl.tagsin[i].show){
        tagstring += $ctrl.tagsin[i].id+',';
      }
    };
    tagstring = tagstring.slice(0, -1);
    AdminService.approveResource($ctrl.resource.numid, tagstring,
                $ctrl.resource.title, $ctrl.resource.description)
      .then(function (response){
        AdminService.getPending()
        .then(function (response){
          $ctrl.list = response.data;
          $ctrl.updatePendings();
          $ctrl.madeAction = true;
        });
      })
      .catch(function (error) {
        console.log(error);
      });
  };

  $ctrl.resetM = function (){
    $ctrl.madeAction = false;
    $ctrl.whatDo = false
  };

}

})();
