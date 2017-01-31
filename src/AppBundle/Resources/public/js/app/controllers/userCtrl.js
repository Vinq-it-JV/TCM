/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.appCtrl
 * @description
 *
 * ## TCM V2.0 Application controller
 *  
 * This is the "parent" controller.
 *
 */
angular
    .module('tcmApp')
    .controller('userCtrl', ['$rootScope', '$scope', '$translate', '$timeout', 'Modal',
    function ($rootScope, $scope, $translate, $timeout, Modal)
    {
        $scope.settingsPage = 'password';

        $scope.requestCredentials = function ()
        {
            $scope.BE.setLoaderDelay(0);
            $scope.requestType = 'requestCredentials';

            var putdata = {
                'url': Routing.generate('gaming_forgot_userpass'),
                'payload': $scope.AUTH.user().email
            };

            $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.$watchCollection('LNG.language()', function () {
        });

        $scope.registerUser = function ()
        {
            $scope.requestType = 'registerUser';

            var putdata = {
                'url': Routing.generate('gaming_register_validate'),
                'payload': $scope.AUTH.registerData()
            };

            $scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };

        $scope.checkBirthdate = function ()
        {
            if ($scope.AUTH.getAge() < 18)
                return false;
            return true;
        };

        $scope.changePassword = function ()
        {
        	$scope.requestType = 'changePassword';
        	
        	var putdata = {
           		'url': Routing.generate('gaming_change_password'),
                'payload': $scope.AUTH.password()
            };

        	$scope.BE.put(putdata, $scope.fetchDataOk, $scope.fetchDataFail);
        };
        
        $scope.showProfile = function ()
        {
            console.log('hgfhd');

            var modalDefaults = {
            	templateUrl: templatePrefix + "profile.tpl.html"
            };

            var modalOptions = {
                closeButtonText: $translate.instant('ANNULEREN'),
                actionButtonText: $translate.instant('SLUITEN'),
                headerText: $translate.instant('PROFIEL'),
                wide: false,
                onCancel: function () { },
                onExecute: function () { },
                parentScope: $scope
            };
            Modal.open(modalDefaults, modalOptions);
        };

        $scope.settingsPage = function (page)
        {
            $scope.settingsPage = page;
        };

        $scope.showSettings = function ()
        {
        	$scope.AUTH.clrPassword();
        	
        	var modalDefaults = {
            	templateUrl: templatePrefix + "settings.tpl.html"
            };

            var modalOptions = {
                closeButtonText: $translate.instant('ANNULEREN'),
                actionButtonText: $translate.instant('SLUITEN'),
                headerText: $translate.instant('INSTELLINGEN.LABEL'),
                wide: true,
                onCancel: function () { },
                onExecute: function () { },
                parentScope: $scope
            };
            Modal.open(modalDefaults, modalOptions);
        };

        $scope.fetchDataOk = function (data)
        {
        	switch ($scope.requestType)
        	{
			    case "requestCredentials":
			    	
			    	if ($scope.isValidObject(data))
			        {
                        $scope.BE.setResult(data);
                        if (!$scope.BE.errorcode())
                            $scope.showNotification($translate.instant($scope.BE.message()), function() {
                                $scope.showUrl('/');
                            });
                    }
			    	break;

                case "registerUser":

                    if ($scope.isValidObject(data))
                    {
                        $scope.BE.setResult(data);
                        if (!$scope.BE.errorcode())
                            $scope.showNotification($scope.BE.message(), function() {
                                $scope.AUTH.clrStoredRegisterData();
                                $scope.showUrl('/');
                            });
                    }
                    break;

        	}
        };
        
    }]);
