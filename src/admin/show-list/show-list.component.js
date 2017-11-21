(function () {
"use strict";

angular.module('ResourceApp')
.component('showList', {
  templateUrl: 'src/admin/show-list/show-list.html',
  controller: showController,
  bindings: {
    whatdoing:    '@',
    list:         '<',
    title:        '@',
    level:        '<',
    editAction:   '&',
    deleteAction: '&'
  }
});

showController.$inject = ['$scope', '$element']
function showController($scope, $element) {
  var $ctrl = this;
  $ctrl.whatToDo = function(action, indexItem){
    (action=='edit')? $ctrl.editAction({index: indexItem})
        :$ctrl.deleteAction({index: indexItem});
  };

}

})();
