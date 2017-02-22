/**
 * Created by jeroenvisser on 03/02/2017.
 */

angular
    .module('tcmApp')
    .filter('GMT', function () {
    return function (input, format) {
        return moment(input, "YYYYMMDD h:mm:ss").format(format);
    };
});
