(function () {
"use strict";

angular.module('ResourceApp')
.component('nudgeAdmin', {
  templateUrl: 'src/admin/nudge/nudgeadmin.html',
  controller: nudgeController,
  bindings: {
    title:  '@',
    dates:  '<'
  }
});

nudgeController.$inject = ['AdminService', '$scope', '$element']
function nudgeController(AdminService, $scope, $element, uiCalendarConfig) {
  var $ctrl = this;

  $ctrl.madeAction = false;
  $ctrl.madeAddition = false;
  $ctrl.whatDoing = '';
  $ctrl.simulateQuery = false;
  $ctrl.isDisabled    = false;
  $ctrl.noCache = false;
  $ctrl.madeEdit = false;
  $ctrl.editprompts = false;
  $ctrl.promptsEdited = false;
  $ctrl.promptsAdded = false;

  $ctrl.$onInit = function () {
    $ctrl.eventSources = $ctrl.dates;
    console.log($ctrl.dates);
  };

  $ctrl.alertEventOnClick = function(date, jsEvent, view){
    $ctrl.madeAction = false;
    $ctrl.madeAddition = false;
    $ctrl.whatDoing = '';
    $ctrl.whichtitle = date.title;
    $ctrl.whichnumid = date.numid;
    if (date.color !='purple'){
      $ctrl.editing = true;
      $ctrl.editprompts = false;
      AdminService.getFpageResource($ctrl.whichnumid)
      .then(function(response){
        $ctrl.eresource = response.data;
      })
      .catch(function(error){
        console.log(error);
      });
    } else {
      $ctrl.editprompts = true;
      $ctrl.editing = false;
      AdminService.getFpagePrompt(date.numid)
      .then(function(response){
        $ctrl.eresource = response.data;
        $ctrl.promptsLength = $ctrl.eresource.prompts.length;
        $ctrl.prompts = $ctrl.eresource.prompts.map(function(v) {
          return {prompt:v};
        });
      })
      .catch(function(error){
        console.log(error);
      });
    }
  };

  $ctrl.whatDo = function(what){
    $ctrl.madeAction = false;
    $ctrl.madeAddition = false;
    $ctrl.promptsEdited = false;
    $ctrl.editprompts = false;
    $ctrl.promptsAdded = false;
    $ctrl.whatDoing = what;
    if (what=='cancel'){
      $ctrl.editing = false;
      $ctrl.editprompts = false;
    } else if (what=='add') {
      $ctrl.search = "";
      $ctrl.searchText = "";
      $ctrl.selectedItem = "";
      AdminService.getTitles()
      .then(function (response){
        $ctrl.notag
        $ctrl.states_array = response.data;
        $ctrl.states = [];
        for (var x=0; x<$ctrl.states_array.length; x++){
          $ctrl.states.push($ctrl.states_array[x].display);
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    } else if (what =='addP') {
        var tlength = $ctrl.dates[3].events.length -1;
        $ctrl.edate = new Date();
        $ctrl.prompts = $ctrl.dates[3].events[tlength].title.map(function(v){
          return {prompt:v};
        });
        $ctrl.promptsLength = $ctrl.prompts.length;
      console.log('here: ', $ctrl.aprompts);
    } else {
      $ctrl.edate = new Date($ctrl.eresource.year, $ctrl.eresource.month-1, $ctrl.eresource.day, 0, 0, 0);
      $ctrl.wview = $ctrl.eresource.wview;
      $ctrl.editprompts = false;
    }
  };

  $ctrl.addPrompt = function() {
    $ctrl.promptsLength ++;
    $ctrl.prompts.push({prompt: ''});
  };

  $ctrl.addNewPrompts = function() {
    var prompt_string = $ctrl.prompts.reduce(function(sum, value) {
      return sum + value.prompt + '|';
    }, "");
    prompt_string = prompt_string.slice(0, -1);
    var date = new Date($ctrl.edate).valueOf()/1000;
    AdminService.addFpagePrompt(date, prompt_string)
    .then(function(response){
      AdminService.GetDates()
        .then(function(response){
          $ctrl.eventSources.splice(0, $ctrl.eventSources.length)
          $ctrl.events = response.data;
          for(var i = 0; i < $ctrl.events.length; ++i) {
            $ctrl.eventSources.push($ctrl.events[i]);
          }
          $ctrl.promptsAdded = true;
        })
        .catch(function(error) {
        });
    })
    .catch(function(error){
      console.log(error);
    });
  };

  $ctrl.editPrompts = function() {
    var prompt_string = $ctrl.prompts.reduce(function(sum, value) {
      return sum + value.prompt + '|';
    }, "");
    prompt_string = prompt_string.slice(0, -1);
    var date = new Date($ctrl.edate).valueOf()/1000;
    AdminService.editFpagePrompt(prompt_string, date, $ctrl.eresource.id)
    .then(function(response){
      AdminService.GetDates()
        .then(function(response){
          $ctrl.eventSources.splice(0, $ctrl.eventSources.length)
          $ctrl.events = response.data;
          for(var i = 0; i < $ctrl.events.length; ++i) {
            $ctrl.eventSources.push($ctrl.events[i]);
          }
          $ctrl.promptsEdited = true;
        })
        .catch(function(error) {
        });
    })
    .catch(function(error){
      console.log(error);
    });
  };

  $ctrl.deleteFPPrompts = function() {
    AdminService.deleteFpagePrompts($ctrl.eresource.id)
    .then(function(response){
      AdminService.GetDates()
        .then(function(response){
          $ctrl.eventSources.splice(0, $ctrl.eventSources.length)
          $ctrl.events = response.data;
          for(var i = 0; i < $ctrl.events.length; ++i) {
            $ctrl.eventSources.push($ctrl.events[i]);
          }
          $ctrl.madeAction = true;
        })
        .catch(function(error) {
        });
    })
    .catch(function(error){
      console.log(error);
    });
  }

  $ctrl.removePrompt = function(index) {
    $ctrl.prompts.splice(index, 1);
    $ctrl.promptsLength --;
  };

  $ctrl.makePromptUpdate = function(index, prompt){
    $ctrl.prompts[index].prompt = prompt;
  };

  $ctrl.changeOrder = function(index, whichway) {
    var to = index+whichway;
    $ctrl.prompts.splice(to,0,$ctrl.prompts.splice(index,1)[0]);
    console.log("result: ", $ctrl.prompts);
  };

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
  };

  $ctrl.deleteFPResource = function (){
    AdminService.aOreFPResource('delete', $ctrl.whichnumid, ' ', 0)
    .then(function(response){
      AdminService.GetDates()
        .then(function(response){
          $ctrl.eventSources.splice(0, $ctrl.eventSources.length)
          $ctrl.events = response.data;
          for(var i = 0; i < $ctrl.events.length; ++i) {
            $ctrl.eventSources.push($ctrl.events[i]);
          }
          $ctrl.madeAction = true;
          $ctrl.editing = false;
        })
        .catch(function(error) {
        });
    })
    .catch(function(error) {
    });
  };

  $ctrl.editFPResource = function (){
    if ($ctrl.edate==undefined){
      $ctrl.edate = "0";
    }
    var date = new Date($ctrl.edate).valueOf()/1000;
    console.log('edit', $ctrl.whichnumid, $ctrl.wview, date);
    AdminService.aOreFPResource('edit', $ctrl.whichnumid, $ctrl.wview, date)
    .then(function(response){
      AdminService.GetDates()
        .then(function(response){
          $ctrl.eventSources.splice(0, $ctrl.eventSources.length)
          $ctrl.events = response.data;
          for(var i = 0; i < $ctrl.events.length; ++i) {
            $ctrl.eventSources.push($ctrl.events[i]);
          }
          $ctrl.madeAction = true;
        })
        .catch(function(error) {
        });
    })
    .catch(function(error) {
    });
  };

  $ctrl.addFPResource = function (){
    if ($ctrl.adate==undefined){
      $ctrl.adate = "0";
    }
    var date = new Date($ctrl.adate).valueOf()/1000;
    AdminService.aOreFPResource('add', $ctrl.searchText, $ctrl.wview, date)
    .then(function(response){
      AdminService.GetDates()
        .then(function(response){
          $ctrl.eventSources.splice(0, $ctrl.eventSources.length)
          $ctrl.events = response.data;
          for(var i = 0; i < $ctrl.events.length; ++i) {
            $ctrl.eventSources.push($ctrl.events[i]);
          }
          $ctrl.madeAddition = true;
          $ctrl.madeAction = true;
        })
        .catch(function(error) {
        });
    })
    .catch(function(error) {
    });
  };

  /* config object */
  $ctrl.uiConfig = {
    calendar:{
      height: 350,
      editable: true,
      header:{
        left: 'month basicWeek basicDay',
        center: 'title',
        right: 'today prev,next'
      },
      eventClick: $ctrl.alertEventOnClick
    }
  };





}

})();
