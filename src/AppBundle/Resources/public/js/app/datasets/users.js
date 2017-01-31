/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Users.service
 * @description
 *
 * ## TCM V2.0 Users service
 *  
 */
angular
    .module('tcmApp')
    .factory('DS_Users', ['$rootScope', '$translate', 'DS_Roles',
    function ($rootScope, $translate, DS_Roles) {
        
    var d_users = [];
    var d_user = [];

    var e_user = {  'Username' : '',
                    'Firstname' : '',
                    'Lastname': '',
                    'Email': '',
                    'Usersname': '',
                    'Roles': '' };

    var d_roles = DS_Roles;
    var d_rolesavailable = [];

    function recordOnIndex (record_id)
    {
        for (index in d_users)
            if (d_users[index].Username == record_id)
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
        usersDS: function ()
        {
            return this;
        },
        usersSet: function (data)
        {
            if (typeof data == 'undefined')
                return d_users;
            d_users = angular.copy(data);
            return d_users;
        },
        users: function ()
        {
            return d_users;
        },
        user: function ()
        {
            return d_user;
        },
        getRecord: function (record_id)
        {
            var index = recordOnIndex(record_id);
            if (index === -1)
                return null;
            d_user = angular.copy(d_users[index]);
            return d_user;
        },
        setRecord: function (record_data)
        {
            var index = recordOnIndex(record_data.Username);
            if (index === -1)
                return null;
            d_users[index] = angular.copy(record_data);
            return record_data;
        },
        addRecord: function (record_data)
        {
            d_users.push(record_data);
            return record_data;
        },
        clrRecord: function ()
        {
            d_user = angular.copy(e_user);
            return d_user;
        },
        delRecord: function (record_id)
        {
            var index = recordOnIndex(record_id);
            if (index === -1)
                return null;
            d_users.splice(index, 1);
            return true;
        },
        updRecord: function (record_data)
        {
            d_user = angular.copy(record_data);
            return d_user;
        },
        isValidObject: function (object)
        {
            return isValidObject(object);
        },
        createUsername: function ()
        {
            if (typeof d_user.Email == 'undefined')
                return;
            if (!d_user.Email.length)
                return;
            var atpos = d_user.Email.indexOf('@');
            if (atpos >= 0)
                return d_user.Username = d_user.Email.substring(0, atpos);
            return '';
        },
        roles: function ()
        {
            return d_roles.roles();
        },
        role: function ()
        {
            return d_roles.role();
        },
        rolesDS: function ()
        {
            return d_roles;
        },
        rolesAvailable: function()
        {
            d_rolesavailable = angular.copy(this.roles());

            var user = this.getRecord(this.user().Username);
            if (user === null)
                return d_rolesavailable;

            for (i in user.Roles)
            {
                for (j in d_rolesavailable)
                {
                    if (d_rolesavailable[j].Id == user.Roles[i].Id)
                        d_rolesavailable.splice(j, 1);
                }
            }

            return d_rolesavailable;
        },
        rolesText: function (record_id)
        {
            var roleText = "";
            var user = this.getRecord(record_id);
            if (user === null)
                return roleText;

            var roles = user.Roles;
            for (index in roles)
            {	if (index != 0)
                    roleText += ", ";
                roleText += roles[index].Description;
            }
            return roleText;
        }
    };
}]);