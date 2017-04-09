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
    .controller('appCtrl', ['$rootScope', '$scope', '$state', '$location', '$window', '$translate', '$timeout', 'Backend', 'User', 'Modal', 'localStorageService', 'DS_Languages',
        function ($rootScope, $scope, $state, $location, $window, $translate, $timeout, Backend, User, Modal, localStorageService, DS_Languages) {
            $scope.AUTH = User;
            $scope.BE = Backend;
            $scope.LNG = DS_Languages;
            $scope.LS = localStorageService;

            $scope.initReady = false;
            $scope.initOnce = false;
            $scope.languageLoaded = false;
            $scope.isCollapsed = true;

            $scope.isLoading = false;

            $scope.requestType = '';
            $scope.callBack = null;

            $rootScope.$on("$translateChangeSuccess", function () {
                $scope.languageLoaded = true;
                $rootScope.$broadcast("languageLoaded");
            });

            $rootScope.$on('modal.show', function () {
                $scope.BE.showLoader(false);
            });

            $scope.$watch("userData", function () {
                console.info("User data changed");
            });

            $scope.$validationOptions = {
                debounce: 1000,
                preValidateFormElements: true
            };

            $scope.init = function () {
                $scope.BE.setLoaderDelay(0);
                if ($scope.initOnce)
                    return;

                $scope.LNG.changeLanguage($translate.proposedLanguage());
                $scope.BE.waitTextSet('PLEASE_WAIT');
                $scope.BE.initialize($scope.initOk, $scope.initFail);
            };

            $scope.initOk = function (data) {
                if ($scope.isValidObject(data)) {
                    if ($scope.isValidObject(data.contents))
                        $scope.AUTH.userSet(data.contents);
                    if ($scope.AUTH.isLoggedin())
                        $scope.LNG.changeLanguage($scope.AUTH.user().LanguageCode);
                }
                $scope.initReady = true;
                $scope.initOnce = true;
            };

            $scope.showProfile = function () {

                console.log('yes');
                var modalDefaults = {
                    templateUrl: templatePrefix + "profile.tpl.html"
                };

                var modalOptions = {
                    closeButtonText: $translate.instant('ANNULEREN'),
                    actionButtonText: $translate.instant('SLUITEN'),
                    headerText: $translate.instant('PROFIEL'),
                    wide: false,
                    onCancel: function () {
                    },
                    onExecute: function () {
                    },
                    parentScope: $scope
                };
                Modal.open(modalDefaults, modalOptions);
            };

            $scope.changePassword = function () {
                $scope.requestType = 'changePassword';
                var postdata = {
                    'url': Routing.generate('tcm_profile_change_password'),
                    'payload': {password: $scope.AUTH.user().password}
                };
                $scope.BE.post(postdata, $scope.fetchDataOk, $scope.fetchDataFail);
            };

            $scope.checkAddress = function (address) {
                if (!$scope.isValidObject(address))
                    return;
                if ($scope.isEmpty(address.HouseNumber) || $scope.isEmpty(address.PostalCode))
                    return;
                var getdata = {
                    'url': Routing.generate('usoft_postcode_api', {
                        'postcode': $scope.trimAll(address.PostalCode),
                        'nummer': $scope.trimAll(address.HouseNumber)
                    })
                };
                $scope.BE.get(getdata, function (data) {
                    if ($scope.isValidObject(data)) {
                        address.City = data.city;
                        address.StreetName = data.street;
                        address.PostalCode = data.zipcode;
                        address.HouseNumber = data.house_number;
                        console.log(address);
                    }
                }, function (data) {
                    if ($scope.isValidObject()) {
                        console.log(data.message);
                    }
                });
            };

            $scope.showNotification = function (message, execute) {
                var modalDefaults = {
                    templateUrl: templatePrefix + "modal.tpl.html"
                };

                var modalOptions = {
                    closeButtonText: $translate.instant('OK'),
                    actionButtonText: '',
                    headerText: $translate.instant('NOTIFICATION'),
                    bodyText: $translate.instant(message),
                    wide: false,
                    onCancel: execute,
                    onExecute: function () {
                    },
                    parentScope: $scope
                };
                Modal.open(modalDefaults, modalOptions);
            };

            $scope.showHttpError = function (data) {
                var modalDefaults = {
                    templateUrl: templatePrefix + "httperror.tpl.html"
                };

                var modalOptions = {
                    closeButtonText: '',
                    actionButtonText: '',
                    headerText: '',
                    bodyText: Backend.getHttpError(),
                    wide: true,
                    onCancel: function () {
                        Backend.clrHttpError();
                    },
                    onExecute: function () {
                    },
                    parentScope: $scope
                };

                Modal.open(modalDefaults, modalOptions);
            };

            $scope.showUrl = function (url) {
                $window.location.href = url;
            };

            $scope.initFail = function (data) {
                $scope.showHttpError(data);
            };

            $scope.fetchDataFail = function (data) {
                $scope.showHttpError(data);
            };

            $scope.fetchDataOk = function (data) {
                switch ($scope.requestType) {
                    case 'changePassword':
                        Modal.close();
                        break;
                    default:
                        break;
                }
            };

            $scope.applicationVersion = function () {
                var year = new Date().getFullYear();
                var versionString = "Version 1.0.0.0";
                var copyString = " \u00A9 " + year + " TCM";

                return copyString + " : " + versionString;
            };

            $scope.isValidObject = function (object) {
                if (object == null)
                    return false;
                if (typeof object !== 'object')
                    return false;
                if (object.length === 0)
                    return false;
                if (Object.keys(object).length === 0)
                    return false;
                return true;
            };

            $scope.isValidIBAN = function (number) {
                return IBAN.isValid(number);
            };

            $scope.isEmpty = function (data) {
                if (data == null)
                    return true;
                if (typeof(data) == 'undefined')
                    return true;
                if (!data.length)
                    return true;
                return false;
            };

            $scope.trimAll = function (data) {
                return data.replace(/\s+/g, '');
            };
        }]);
