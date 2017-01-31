/**
 * Created by jeroenvisser on 23-05-14. Modal controller, creates popup
 */

angular
    .module('tcmApp')
	.factory('Modal', [	'$rootScope', '$modal',	'$translate',
    function($rootScope, $modal, $translate) {

    var $scope = $rootScope.$new();

    $scope.modalDefaults = {
        keyboard : true,
        templateUrl : templatePrefix + "modal.tpl.html",
        backdrop : 'static'
    };

    $scope.modalOptions = {
        closeButtonText : 'Close',
        actionButtonText : 'OK',
        headerText : 'Proceed?',
        bodyText : 'Perform this action?',
        onCancel : null,
        onAction : null
    };

    var show = function(customModalDefaults, customModalOptions)
    {
        var modalDefaults = {};
        var modalOptions = {};

        angular.extend(modalDefaults, $scope.modalDefaults, customModalDefaults);
        angular.extend(modalOptions, $scope.modalOptions, customModalOptions);

        $scope.modalOptions = modalOptions;

        console.log(modalDefaults.templateUrl);

        return $modal({	scope : $scope,	templateUrl : modalDefaults.templateUrl, backdrop : modalDefaults.backdrop });
    };

    return {
        open : function(customModalDefaults, customModalOptions)
        {
            if (!customModalDefaults)
                customModalDefaults = {};
            return show(customModalDefaults, customModalOptions);
        }
    }
} ]);