'use strict';
/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.tcmApp
 * @description
 *
 * ## TCM V1.0 Application controller
 *  
 * TCM application routing en translation initialisation.
 *
 */
angular
    .module('tcmApp', ['ui.router', 'ngResource', 'ngCookies', 'ngSanitize', 'LocalStorageModule', 'ngAnimate', 'pascalprecht.translate', 'cgBusy', 'mgcrea.ngStrap', 'ghiscoding.validation'])
    .config(function($translateProvider) {
        $translateProvider.useStaticFilesLoader({
        	files: [{
                prefix: translationPrefix,
                suffix: '.json'
            }, {
                prefix: translationPrefix + '/validation/',
                suffix: '.json'
            }]
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.useLocalStorage();
    })
    .config(function (localStorageServiceProvider) {
        localStorageServiceProvider
            .setPrefix('tcmApp')
            .setStorageType('sessionStorage')
            .setNotify(true, true);
    })
    .config(['$urlRouterProvider', '$stateProvider', '$locationProvider', '$translateProvider',
        function ($urlRouterProvider, $stateProvider, $locationProvider, $translateProvider) {
            $stateProvider
            .state('home', {
                url: Routing.generate('tcm_home')
            });
    }]);
