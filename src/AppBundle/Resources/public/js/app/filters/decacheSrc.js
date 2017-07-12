/**
 * Created by jeroenvisser on 03/02/2017.
 */

angular
    .module('tcmApp')
    .filter('decacheSrc', function () {
        return function (input) {
            if (input)
                return input + '?r=' + Math.round(Math.random() * 999999);
        }
    });
