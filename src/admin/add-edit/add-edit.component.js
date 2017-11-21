(function () {
"use strict";

angular.module('ResourceApp')
.component('addEdit', {
  templateUrl: 'src/admin/add-edit/add-edit.html',
  controller: addEditController,
  bindings: {
    what:     '@',
    resource: '<',
    clearSearch: '&'
  }
});

addEditController.$inject = ['HomeService', 'AdminService', '$scope', '$element', 'Upload']
function addEditController(HomeService, AdminService, $scope, $element, Upload) {
  var $ctrl = this;

  $ctrl.$onInit = function () {
    $ctrl.completed = false;
    $ctrl.partOne = true;
    $ctrl.howUpload = false;
    $ctrl.pdfFile = "";
    $ctrl.added = false;
    $ctrl.updated = false;
    $ctrl.title           = "";
    $ctrl.rlink           = "";
    $ctrl.description     = "";
    $ctrl.tags            = "";
    $ctrl.type_resource   = "Link";
    $ctrl.numid           = "";
    if ($ctrl.what=='edit'){
      $ctrl.title           = $ctrl.resource.title;
      $ctrl.rlink           = $ctrl.resource.rlink;
      $ctrl.description     = $ctrl.resource.description;
      $ctrl.rtags           = $ctrl.resource.tags.split(",");
      $ctrl.numid           = $ctrl.resource.numid;
      $ctrl.type_resource   = $ctrl.resource.type_resource;
      $ctrl.howUpload = ($ctrl.type_resource =='PDF Upload');
    }
    HomeService.getTags().then(function (response){
      $ctrl.initial_list_tags = response.data;
      $ctrl.tags = response.data;
      $ctrl.tagsin = [];
      for (var i = 0; i < $ctrl.tags.length; i++) {
        var temp_array=[];
        temp_array.value = $ctrl.tags[i].value;
        temp_array.display = $ctrl.tags[i].display;
        temp_array.id = $ctrl.tags[i].id;
        temp_array.show = ($ctrl.what=='edit' && $ctrl.rtags.includes(temp_array.id));
        $ctrl.tags[i].show = !temp_array.show;
        $ctrl.tagsin.push(temp_array);
      }
    })
    .catch(function (error) {
      console.log(error);
    });
  };

  $ctrl.changeUpload = function(){
    $ctrl.howUpload = ($ctrl.type_resource=="PDF Upload");
  };

  $ctrl.partTwo = function(){
    $ctrl.partOne = !$ctrl.partOne;
  }

  $ctrl.partTwopdf = function(){
    if ($ctrl.pdfFile !=""){
      Upload.upload({
        method: "POST",
        // url: ('https://csrl.princeton.edu/ajax/' + "uploadPDF.php"),
        url: ('http://localhost:8888/rportal/ajax/' +"uploadPDF.php"),
        file: $ctrl.pdfFile
      }).then(function (response){
          $ctrl.rlink = response.data.link;
          $ctrl.partOne = !$ctrl.partOne;
          })
          .catch(function (error) {
            console.log(error);
          });
    } else {
      $ctrl.partOne = !$ctrl.partOne;
    }
  };

  $ctrl.changeShow = function(index){
    $ctrl.tags[index].show = !$ctrl.tags[index].show;
    $ctrl.tagsin[index].show = !$ctrl.tagsin[index].show;
  };

  $ctrl.clearSignage = function (){
    $ctrl.updated = false;
    $ctrl.added = false;
    $ctrl.completed = false;
  };

  $ctrl.cancelThat = function (){
    if ($ctrl.what=="add"){
      $ctrl.type_resource = "Link";
      $ctrl.title = "";
      $ctrl.description = "";
      $ctrl.rlink = "";
    } else {
      $ctrl.clearSearch({actionType: 'cancel'});
    }
  };

  $ctrl.nowUpload = function(){
    var tagstring= "";
    for (var i = 0; i < $ctrl.tagsin.length; i++) {
      if ($ctrl.tagsin[i].show){
        tagstring += $ctrl.tagsin[i].id+',';
      }
    };
    tagstring = tagstring.slice(0, -1);
    AdminService.aOreResource($ctrl.numid, $ctrl.pdfFile, $ctrl.what, tagstring,
              $ctrl.type_resource, $ctrl.title, $ctrl.description, $ctrl.rlink)
      .then(function (response){
        if ($ctrl.what=='add'){
          // for (var i = 0; i < $ctrl.tagsin.length; i++) {
          //   if ($ctrl.tagsin[i].show){
          //     $ctrl.tagsin[i].show = false;
          //   }
          // };
          $ctrl.tags = $ctrl.initial_list_tags;
          $ctrl.tagsin = [];
          $ctrl.type_resource = "Link";
          $ctrl.title = "";
          $ctrl.description = "";
          $ctrl.rlink = "";
          $ctrl.added = true;
        } else {
          $ctrl.type_resource = "Link";
          $ctrl.title = "";
          $ctrl.description = "";
          $ctrl.rlink = "";
          $ctrl.clearSearch({actionType: 'updated'});
        }
        $ctrl.partOne = !$ctrl.partOne;
      })
      // .then(function (response){
      //   AdminService.sendNotice().then(function(response){
      //     console.log("what I got back: ", response.data);
      //   });
      // })
      .catch(function (error) {
        console.log(error);
      });
  };

}

})();
