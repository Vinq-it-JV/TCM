/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Companies
 * @description
 *
 * ## TCM V2.0 Companies
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Companies', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_companies = [];
            var d_company = [];
            var d_template = [];
            var d_lists = [];

            var d_email = {};
            var d_phone = {};
            var d_address = {};

            function recordOnIndex(record_id) {
                for (index in d_companies)
                    if (d_companies[index].Id == record_id)
                        return index;
                return -1;
            }

            function isValidObject(object) {
                if (typeof object !== 'object')
                    return false;
                if (object.length === 0)
                    return false;
                if (Object.keys(object).length === 0)
                    return false;
                return true;
            }

            return {
                companiesDS: function () {
                    return this;
                },
                companiesSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_companies;
                    d_companies = angular.copy(data);
                    return d_companies;
                },
                companies: function () {
                    return d_companies;
                },
                company: function () {
                    return d_company;
                },
                templateSet: function (template) {
                    d_template = template;
                    this.clrEmail();
                    this.clrPhone();
                    this.clrAddress();
                },
                template: function () {
                    return d_template;
                },
                listsSet: function (lists) {
                    d_lists = lists;
                },
                lists: function () {
                    return d_lists;
                },
                email: function () {
                    return d_email;
                },
                emails: function () {
                    if (!d_company.hasOwnProperty('Emails'))
                        return [];
                    return d_company.Emails;
                },
                phone: function () {
                    return d_phone;
                },
                address: function () {
                    return d_address;
                },
                clrEmail: function () {
                    if (d_template.hasOwnProperty('email'))
                        d_email = angular.copy(d_template.email);
                },
                clrPhone: function () {
                    if (d_template.hasOwnProperty('phone'))
                        d_phone = angular.copy(d_template.phone);
                },
                clrAddress: function () {
                    if (d_template.hasOwnProperty('address'))
                        d_address = angular.copy(d_template.address);
                },
                getRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_company = angular.copy(d_companies[index]);
                    return d_company;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_companies[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_companies.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_company = angular.copy(d_template.company);
                    return d_company;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_companies.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_company = angular.copy(record_data);
                    return d_company;
                },
                addEmail: function () {
                    if (!d_company.hasOwnProperty('Emails'))
                        d_company.Emails = [];

                    d_email.Id = moment().unix();
                    d_email.New = true;
                    d_company.Emails.push(d_email);

                    this.clrEmail();
                },
                addPhone: function () {
                    if (!d_company.hasOwnProperty('Phones'))
                        d_company.Phones = [];

                    d_phone.Id = moment().unix();
                    d_phone.New = true;
                    d_company.Phones.push(d_phone);

                    this.clrPhone();
                },
                addAddress: function () {
                    if (!d_company.hasOwnProperty('Addresses'))
                        d_company.Addresses = [];

                    d_address.Id = moment().unix();
                    d_address.Type = null;
                    d_address.New = true;
                    d_company.Addresses.push(d_address);

                    this.clrAddress();
                    console.log(d_company.Addresses);
                },
                deleteEmail: function (id) {
                    var emailId = d_company.Emails[id].Id;
                    if (d_company.Emails[id].hasOwnProperty('New'))
                        emailId = 0;
                    d_company.Emails.splice(id);
                    return emailId;
                },
                deletePhone: function (id) {
                    var phoneId = d_company.Phones[id].Id;
                    if (d_company.Phones[id].hasOwnProperty('New'))
                        phoneId = 0;
                    d_company.Phones.splice(id);
                    return phoneId;
                },
                deleteAddress: function (id) {
                    var addressId = d_company.Addresses[id].Id;
                    if (d_company.Addresses[id].hasOwnProperty('New'))
                        addressId = 0;
                    d_company.Addresses.splice(id);
                    return addressId;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);