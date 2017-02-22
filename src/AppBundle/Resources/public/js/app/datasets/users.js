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
    var d_template = [];
    var d_lists = [];

    var d_email = {};
    var d_phone = {};
    var d_address = {};

    var d_roles = DS_Roles;
    var d_rolesavailable = [];

    function recordOnIndex (record_id)
    {
        for (index in d_users)
            if (d_users[index].Id == record_id)
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
        usersCollection: function ()
        {
            return d_usersCollection;
        },
        templateSet: function (template)
        {
            d_template = template;
            this.clrEmail();
            this.clrPhone();
            this.clrAddress();
        },
        template: function ()
        {
            return d_template;
        },
        listsSet: function (lists)
        {
            d_lists = lists;
        },
        lists: function ()
        {
            return d_lists;
        },
        user: function ()
        {
            return d_user;
        },
        email: function ()
        {
            return d_email;
        },
        emails: function ()
        {
            if (!d_user.hasOwnProperty('Emails'))
                return [];
            return d_user.Emails;
        },
        phone: function ()
        {
            return d_phone;
        },
        address: function ()
        {
            return d_address;
        },
        clrEmail: function ()
        {
            if (d_template.hasOwnProperty('email'))
                d_email = angular.copy(d_template.email);
        },
        clrPhone: function ()
        {
            if (d_template.hasOwnProperty('phone'))
                d_phone = angular.copy(d_template.phone);
        },
        clrAddress: function ()
        {
            if (d_template.hasOwnProperty('address'))
                d_address = angular.copy(d_template.address);
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
            d_user = angular.copy(d_template.user);
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
        addEmail: function ()
        {
            if (!d_user.hasOwnProperty('Emails'))
                d_user.Emails = [];

            d_email.Id = moment().unix();
            d_email.New = true;
            d_user.Emails.push(d_email);

            this.clrEmail();
        },
        addPhone: function ()
        {
            if (!d_user.hasOwnProperty('Phones'))
                d_user.Phones = [];

            d_phone.Id = moment().unix();
            d_phone.New = true;
            d_user.Phones.push(d_phone);

            this.clrPhone();
        },
        addAddress: function ()
        {
            if (!d_user.hasOwnProperty('Addresses'))
                d_user.Addresses = [];

            d_address.Id = moment().unix();
            d_address.Type = 1;
            d_address.New = true;
            d_user.Addresses.push(d_address);

            this.clrAddress();
        },
        deleteEmail: function (id)
        {
            var emailId = d_user.Emails[id].Id;
            if (d_user.Emails[id].hasOwnProperty('New'))
                emailId = 0;
            d_user.Emails.splice(id);
            return emailId;
        },
        deletePhone: function (id)
        {
            var phoneId = d_user.Phones[id].Id;
            if (d_user.Phones[id].hasOwnProperty('New'))
                phoneId = 0;
            d_user.Phones.splice(id);
            return phoneId;
        },
        deleteAddress: function (id)
        {
            var addressId = d_user.Addresses[id].Id;
            if (d_user.Addresses[id].hasOwnProperty('New'))
                addressId = 0;
            d_user.Addresses.splice(id);
            return addressId;
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
        },
        isValidObject: function (object)
        {
            return isValidObject(object);
        }
    };
}]);