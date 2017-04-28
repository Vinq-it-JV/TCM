/**
 * @author Jeroen Visser
 * @name User.service
 * @description
 *
 *  ## TCM V1.0 User service
 *
 */
angular
    .module('tcmApp')
    .factory('User', ['$rootScope', '$translate', 'localStorageService',
        function ($rootScope, $translate, localStorageService) {

            var d_user = {
                "loggedin": false,
                "username": '',
                "email": '',
                "name": $translate.instant('USER.WELCOME'),
                "initials": '',
                "lastname": '',
                "firstname": '',
                "language": '',
                "password": '',
                "pconfirm": '',
                "roles": null,
                "terms": false
            };

            var d_credentials = {
                _username: $("#username").val(),
                _password: $("#password").val()
            };

            var d_password = '';
            var e_password = {
                "password": "",
                "verify": ""
            };

            /* ROLE_USER = User
             ROLE_ADMIN = Admin
             ROLE_SUPER_ADMIN = Super admin
             */

            return {
                userSet: function (data) {
                    console.log(data);
                    d_user = angular.extend(d_user, data.user);
                    $rootScope.$broadcast("userData");
                },
                user: function () {
                    return d_user;
                },
                isLoggedin: function () {
                    if (d_user.IsLoggedin)
                        return true;
                    return false;
                },
                hasRole: function (rolestr) {
                    var roles = rolestr.split(',');
                    if (this.isSuperAdmin())
                        return true;
                    for (rindex in roles)
                        for (index in d_user.Roles)
                            if (d_user.Roles[index] == roles[rindex].trim())
                                return true;
                    return false;
                },
                password: function () {
                    return d_password;
                },
                credentials: function () {
                    return d_credentials;
                },
                clrPassword: function () {
                    d_password = angular.copy(e_password);
                    return d_password;
                },
                isAdmin: function () {
                    for (index in d_user.Roles)
                        if (d_user.Roles[index].Name == "ROLE_ADMIN")
                            return true;
                    return false;
                },
                isSuperAdmin: function () {
                    for (index in d_user.Roles)
                        if (d_user.Roles[index].Name == "ROLE_SUPER_ADMIN")
                            return true;
                    return false;
                },
                isUser: function (username) {
                    if (d_user.Username == username)
                        return true;
                    return false;
                }
            };
        }]);