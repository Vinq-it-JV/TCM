/**
 * Translate filter for ui-select.
 * By: Jeroen Visser
 * 01-03-2017
 */
angular
    .module('tcmApp')
    .filter('filterTranslate', ['$translate', function ($translate) {
        return function (items, props) {
            var out = [];

            if (angular.isArray(items)) {
                var keys = Object.keys(props);

                // Scan all items for filter
                items.forEach(function (item) {
                    var itemMatches = false;

                    for (var i = 0; i < keys.length; i++) {
                        var prop = keys[i];
                        var text = props[prop].toLowerCase();
                        var transtext = $translate.instant(item[prop].toString()).toLowerCase();
                        if (transtext.indexOf(text) !== -1) {
                            itemMatches = true;
                            break;
                        }
                    }

                    if (itemMatches) {
                        out.push(item);
                    }
                });
            } else {
                // Just use the items in the list without filter!
                out = items;
            }

            return out;
        };
    }]);
