/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Languages.service
 * @description
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Languages', ['$rootScope', '$translate',
    function ($rootScope, $translate) {
        
    var d_languages = [{'Id': 'en', 'Name': 'LNG.ENGLISH', 'Flag': ''},
                       {'Id': 'nl', 'Name': 'LNG.DUTCH', 'Flag': ''}];

    var d_language = [];

    var e_language = {
        'Id': '',
        'Name': '',
        'Flag': ''
    };

    function recordOnIndex (record_id)
    {
        for (index in d_languages)
            if (d_languages[index].Id == record_id)
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
        languagesDS: function ()
        {
            return this;
        },
        changeLanguage: function (id)
        {
            var language = this.getRecord(id);
            if (language)
                if (language.Id.length)
                    $translate.use(language.Id);
        },
        languagesSet: function (data)
        {
            if (typeof data == 'undefined')
                return d_languages;
            d_languages = angular.copy(data);
            return d_languages;
        },
        languages: function ()
        {
            return d_languages;
        },
        language: function ()
        {
            return d_language;
        },
        getRecord: function (record_id)
        {
            var index = recordOnIndex(record_id);
            if (index === -1)
                return null;
            d_language = angular.copy(d_languages[index]);
            return d_language;
        },
        setRecord: function (record_data)
        {
            var index = recordOnIndex(record_data.Id);
            if (index === -1)
                return null;
            d_languages[index] = angular.copy(record_data);
            return record_data;
        },
        addRecord: function (record_data)
        {
            d_languages.push(record_data);
            return record_data;
        },
        clrRecord: function ()
        {
            d_language = angular.copy(e_language);
            return d_language;
        },
        delRecord: function (record_id)
        {
            var index = recordOnIndex(record_id);
            if (index === -1)
                return null;
            d_languages.splice(index, 1);
            return true;
        },
        updRecord: function (record_data)
        {
            d_language = angular.copy(record_data);
            return d_language;
        },
        isValidObject: function (object)
        {
            return isValidObject(object);
        }
    };
}]);