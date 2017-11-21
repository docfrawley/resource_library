(function () {
"use strict";

angular.module('common')
.service('AdminService', AdminService)
.constant('ApiPath', 'http://localhost:8888/resource_portal/ajax/');


AdminService.$inject = ['$http', 'ApiPath', 'Upload'];
function AdminService($http, ApiPath, Upload) {
  var service = this;

  service.GetNetids = function() {
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'netids'
      }
    });
    return response;
  };

  service.getFpageResource = function(numid){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'getFpageResource',
        numid: numid
      }
    });
    return response;
  };

  service.GetDates = function() {
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'getDates'
      }
    });
    return response;
  };

  service.getPending = function(){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'getPending'
      }
    });
    return response;
  };

  service.getWho = function(){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'getWho'
      }
    });
    return response;
  };

  service.getTitles = function(){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'getTitles'
      }
    });
    return response;
  };

  service.getUsers = function(){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'getUsers'
      }
    });
    return response;
  };

  service.getUser = function(index) {
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:   'getUser',
        index:  index
      }
    });
    return response;
  };

  service.getResources = function(searchwhat, searching){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task:       'getEditResources',
        what: searchwhat,
        searching:  searching
      }
    });
    return response;
  };

  service.dResource = function(numid){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task: 'dresource',
        numid:  numid
      }
    });
    return response;
  };

  service.doDelete = function(numid, status){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task: 'deleteresource',
        numid:  numid,
        status: status
      }
    });
    return response;
  };

  service.deleteUser = function(numindex) {
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task: 'deleteuser',
        numindex:  numindex
      }
    });
    return response;
  };

  service.deleteFpagePrompts = function(id) {
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task: 'deleteprompt',
        id:  id
      }
    });
    return response;
  };

  service.getFpagePrompt = function(numid){
    var response = $http({
      method: "GET",
      url: (ApiPath +"ajaxfiles.php"),
      params: {
        task: 'getFpagePrompt',
        numid:  numid
      }
    });
    return response;
  };

  service.aOreResource =function(numid, pdfFile, what, tagstring, type, title, description, link){
    var response = $http({
      method: "POST",
      url: (ApiPath +"aOreResource.php"),
      data: {
        numid:        numid,
        pdfFile:      pdfFile,
        what:         what,
        tags:         tagstring,
        type:         type,
        title:        title,
        description:  description,
        link:         link
      }
    });
    return response;
  };

  service.approveResource = function(numid, tagstring, title, description){
    var response = $http({
      method: "POST",
      url: (ApiPath +"approveResource.php"),
      data: {
        numid:        numid,
        tags:         tagstring,
        title:        title,
        description:  description
      }
    });
    return response;
  };

  service.adOredUser = function(whatDo, user){
    var response = $http({
      method: "POST",
      url: (ApiPath +"aoreuser.php"),
      data: {
        whatDo:   whatDo,
        numindex: user.numindex,
        fname:    user.fname,
        lname:    user.lname,
        netid:    user.netid,
        level:    user.level,
        webhook:  user.webhook
      }
    });
    return response;
  };

  service.aOreFPResource = function(whatDo, whichnumid, whichview, edate){
    var response = $http({
      method: "POST",
      url: (ApiPath +"aorefresource.php"),
      data: {
        whatDo:     whatDo,
        numid:      whichnumid,
        whichview:  whichview,
        edate:      edate
      }
    });
    return response;
  };

  service.editFpagePrompt = function(prompt_string, date, id){
    var response = $http({
      method: "POST",
      url: (ApiPath +"aorefresource.php"),
      data: {
        whatDo:     'editP',
        numid:      id,
        whichview:  prompt_string,
        edate:      date
      }
    });
    return response;
  };

  service.addFpagePrompt = function(date, prompt_string){
    var response = $http({
      method: "POST",
      url: (ApiPath +"aorefresource.php"),
      data: {
        whatDo:     'addP',
        numid:      0,
        whichview:  prompt_string,
        edate:      date
      }
    });
    return response;
  }

}

})();
