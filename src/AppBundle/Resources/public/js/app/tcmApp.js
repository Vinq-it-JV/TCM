'use strict';
/**
 * @ngdoc controller
 * @author Jeroen Visser
 * @name controller.tcmApp
 * @description
 *
 * ## TCM V2.0 Application controller
 *  
 * TCM application routing en translation initialisation.
 *
 */
angular
    .module('tcmApp', ['ui.router', 'ui.select', 'toggle-switch', 'ui.tree', 'highcharts-ng', 'ngResource', 'ngCookies', 'ngSanitize', 'LocalStorageModule', 'smart-table', 'ngAnimate', 'ghiscoding.validation', 'pascalprecht.translate', 'cgBusy', 'mgcrea.ngStrap', 'ngMap', 'ng.dropzone', 'html5.sortable'])
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
        function ($urlRouterProvider, $stateProvider) {
            $stateProvider
            .state('tcm_splash', {
                url: Routing.generate('tcm_splash')
            });
    }]);
