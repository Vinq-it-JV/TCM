/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Roles.service
 * @description
 *
 * ## TCM V2.0 Rollen service
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Roles', ['$rootScope', '$translate',
    function ($rootScope, $translate) {

    /*  ROLE_USER = User
        ROLE_VIP_USER = VIP user
        ROLE_ADMIN = Admin
        ROLE_SUPER_ADMIN = Super admin
        ROLE_BOOKMAKER = Bookmaker
        ROLE_AFFILIATE = Affiliate
     */

    var d_roles = [];
    var d_role = [];

    var e_role = { };

    function recordOnIndex (record_id)
    {
        for (index in d_roles)
            if (d_roles[index].Id == record_id)
                return index;
        return -1;
    }

    function isValidObject (object)
    {
        if (typeof object !== 'object')
            return false;
        if (object.length === 0)
            return false;
        if (Object.keys(object).length === 0)
            return false;
        return true;
    }

    return {
        rolesDS: function ()
        {
            return this;
        },
        rolesSet: function (data)
        {
            if (typeof data == 'undefined')
                return d_roles;
            d_roles = angular.copy(data);
            return d_roles;
        },
        roles: function ()
        {
            return d_roles;
        },
        role: function ()
        {
            return d_role;
        },
        getRecord: function (record_id)
        {
            var index = recordOnIndex(record_id);
            if (index === -1)
                return null;
            d_role = angular.copy(d_roles[index]);
            return d_role;
        },
        setRecord: function (record_data)
        {
            var index = recordOnIndex(record_data.Id);
            if (index === -1)
                return null;
            d_roles[index] = angular.copy(record_data);
            return record_data;
        },
        addRecord: function (record_data)
        {
            d_roles.push(record_data);
            return record_data;
        },
        clrRecord: function ()
        {
            d_role = angular.copy(e_role);
            return d_role;
        },
        delRecord: function (record_id)
        {
            var index = recordOnIndex(record_id);
            if (index === -1)
                return null;
            d_roles.splice(index, 1);
            return true;
        },
        updRecord: function (record_data)
        {
            d_role = angular.copy(record_data);
            return d_role;
        },
        isValidObject: function (object)
        {
            return isValidObject(object);
        }
    };
}]);