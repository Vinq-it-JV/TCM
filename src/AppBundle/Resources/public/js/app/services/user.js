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
    .factory('User', [ '$rootScope', '$translate', 'localStorageService',
    function($rootScope, $translate, localStorageService) {

    var d_user = {
        "loggedin" : false,
        "username" : '',
        "email" : '',
        "name" : $translate.instant('USER.WELCOME'),
        "initials" : '',
        "lastname" : '',
        "firstname" : '',
        "language": '',
        "roles" : null,
        "terms" : false
    };

    var d_credentials = { _username: $("#username").val(),
                          _password: $("#password").val() };

    var d_password = '';
    var e_password = {
        "password" : "",
        "verify" : ""
    };

    /* ROLE_USER = User
       ROLE_VIP_USER = VIP user
       ROLE_ADMIN = Admin
       ROLE_SUPER_ADMIN = Super admin
       ROLE_BOOKMAKER = Bookmaker
       ROLE_AFFILIATE = Affiliate
    */

    return {
        userSet : function(data) {
            if (data) {
                d_user = angular.extend(d_user, data);
                console.log(d_user);
                $rootScope.$broadcast("userData");
            }
        },
        user : function() {
            return d_user;
        },
        fullName: function() {
            if (this.isLoggedin())
            {   if (d_user.firstname == null && d_user.lastname == null)
                    return d_user.username;
                var fullname = d_user.firstname + " " + d_user.lastname;
                if (fullname.length <= 1)
                    return d_user.username;
                return fullname;
            }
            return $translate.instant('USER.WELCOME');
        },
        isLoggedin : function() {
            if (d_user.loggedin)
                return true;
            return false;
        },
        hasRole : function(rolestr) {
            var roles = rolestr.split(',');
            if (this.isAdmin())
                return true;
            for (rindex in roles)
                for (index in d_user.roles)
                    if (d_user.roles[index] == roles[rindex].trim())
                        return true;
            return false;
        },
        password : function () {
            return d_password;
        },
        credentials: function () {
            return d_credentials;
        },
        clrPassword : function () {
            d_password = angular.copy(e_password);
            return d_password;
        },
        isAdmin : function() {
            for (index in d_user.roles)
                if (d_user.roles[index] == "ROLE_ADMIN")
                    return true;
            return false;
        },
        isUser : function(username) {
            if (d_user.username == username)
                return true;
            return false;
        }
    };
} ]);