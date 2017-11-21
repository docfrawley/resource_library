(function () {
    'use strict'

    angular.module('ResourceApp')
        .controller('FeedbackController', FeedbackController)


    FeedbackController.$inject = ['HomeService'];
    function FeedbackController(HomeService) {
        var fctrl = this;
        // hctrl.events = events;
        fctrl.nofeedback = true;
        fctrl.name = "";
        fctrl.email = "";
        fctrl.actual = "";
        fctrl.sendFeedback = function (feedbackForm) {
            console.log("what I got: ", fctrl.name, fctrl.email, fctrl.actual);
            HomeService.sendFeedback(fctrl.name, fctrl.email, fctrl.actual)
                .then(function (response) {
                    fctrl.nofeedback = false; 
                    fctrl.name = "";
                    fctrl.email = "";
                    fctrl.actual = "";       
                    feedbackForm.$setUntouched();            
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
        fctrl.resetPage = function() {
            fctrl.nofeedback = true;
        }

    }



})();