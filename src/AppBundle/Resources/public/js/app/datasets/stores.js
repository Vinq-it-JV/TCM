/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Stores
 * @description
 *
 * ## TCM V2.0 Stores
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Stores', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_stores = [];
            var d_store = [];
            var d_template = [];
            var d_lists = [];

            var d_email = {};
            var d_phone = {};
            var d_address = {};

            function recordOnIndex(record_id) {
                for (index in d_stores)
                    if (d_stores[index].Id == record_id)
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
                storesDS: function () {
                    return this;
                },
                storesSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_stores;
                    d_stores = angular.copy(data);
                    return d_stores;
                },
                stores: function () {
                    return d_stores;
                },
                store: function () {
                    return d_store;
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
                    if (!d_store.hasOwnProperty('Emails'))
                        return [];
                    return d_store.Emails;
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
                    d_store = angular.copy(d_stores[index]);
                    return d_store;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_stores[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_stores.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_store = angular.copy(d_template.store);
                    return d_store;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_stores.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_store = angular.copy(record_data);
                    return d_store;
                },
                addEmail: function () {
                    if (!d_store.hasOwnProperty('Emails'))
                        d_store.Emails = [];

                    d_email.Id = moment().unix();
                    d_email.New = true;
                    d_store.Emails.push(d_email);

                    this.clrEmail();
                },
                addPhone: function () {
                    if (!d_store.hasOwnProperty('Phones'))
                        d_store.Phones = [];

                    d_phone.Id = moment().unix();
                    d_phone.New = true;
                    d_store.Phones.push(d_phone);

                    this.clrPhone();
                },
                addAddress: function () {
                    if (!d_store.hasOwnProperty('Addresses'))
                        d_store.Addresses = [];

                    d_address.Id = moment().unix();
                    d_address.Type = null;
                    d_address.New = true;
                    d_store.Addresses.push(d_address);

                    this.clrAddress();
                },
                deleteEmail: function (id) {
                    var emailId = d_store.Emails[id].Id;
                    if (d_store.Emails[id].hasOwnProperty('New'))
                        emailId = 0;
                    d_store.Emails.splice(id);
                    return emailId;
                },
                deletePhone: function (id) {
                    var phoneId = d_store.Phones[id].Id;
                    if (d_store.Phones[id].hasOwnProperty('New'))
                        phoneId = 0;
                    d_store.Phones.splice(id);
                    return phoneId;
                },
                deleteAddress: function (id) {
                    var addressId = d_store.Addresses[id].Id;
                    if (d_store.Addresses[id].hasOwnProperty('New'))
                        addressId = 0;
                    d_store.Addresses.splice(id);
                    return addressId;
                },
                copyCompanyData: function (company) {
                    d_store.Code = angular.copy(company.Code);
                    d_store.Name = angular.copy(company.Name);
                    d_store.Description = angular.copy(company.Description);
                    d_store.Region = angular.copy(company.Region);
                    d_store.Website = angular.copy(company.Website);
                    d_store.VatNumber = angular.copy(company.VatNumber);
                    d_store.CocNumber = angular.copy(company.CocNumber);
                    d_store.PaymentMethod = angular.copy(company.PaymentMethod);
                    d_store.BankAccountNumber = angular.copy(company.BankAccountNumber);
                    d_store.Emails = angular.copy(company.Emails);
                    d_store.Phones = angular.copy(company.Phones);
                    d_store.Addresses = angular.copy(company.Addresses);
                    d_store.Owners = angular.copy(company.Owners);
                    d_store.Contacts = angular.copy(company.Contacts);
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);