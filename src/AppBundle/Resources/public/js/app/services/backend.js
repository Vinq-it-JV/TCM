/**
 * Created by jeroenvisser on 14-01-2015
 */

angular
    .module('tcmApp')
    .factory('Backend', [ 'Http', '$q',
    function(Http, $q) {
	
	var loadingPromise = null;
	var waittext = "Please wait";
    var loaderdelay = 0;

    var message = "";
    var errorcode = 0;
    var contents = {};

	return {
		initialize : function(cb_Success, cb_Fail) {
			var postdata = {
				'url' : Routing.generate('tcm_login_status'),
				'payload' : ''
			};
			Http.put(postdata, cb_Success, cb_Fail);
		},
		post : function(postdata, cb_Success, cb_Fail) {
			loadingPromise = Http.post(postdata, cb_Success, cb_Fail);
		},
		put : function(putdata, cb_Success, cb_Fail) {
			loadingPromise = Http.put(putdata, cb_Success, cb_Fail);
		},
		get : function(getdata, cb_Success, cb_Fail) {
			loadingPromise = Http.get(getdata, cb_Success, cb_Fail);
		},
        delete : function(deletedata, cb_Success, cb_Fail) {
            loadingPromise = Http.delete(deletedata, cb_Success, cb_Fail);
        },
        setResult : function (data)
        {
            message = angular.copy(data.message);
            errorcode = angular.copy(data.errorcode);
            contents = angular.copy(data.contents)
            return data;
        },
        message : function ()
        {
            return message;
        },
        errorcode : function ()
        {
            return errorcode;
        },
        contents : function ()
        {
            return contents;
        },
		getHttpError : function ()
		{
			return { 'type': Http.error_type(), 'message': Http.error_message(), 'content': Http.error_content(), 'details': false };
		},
		clrHttpError : function ()
		{
			return Http.error_clr();
		},
		promise : function () {
            return loadingPromise;
		},
		waitText : function () {
            return waittext;
		},
		waitTextSet : function (text)
		{
			waittext = angular.copy(text);
			return waittext;
		},
		setLoaderDelay : function (delay)
        {
            loaderdelay = delay;
            return loaderdelay;
        },
        getLoaderDelay : function ()
        {
            return loaderdelay;
        },
        showLoader : function (mode)
		{
			loadingPromise = this.fakeLoader(mode);
		},
		fakeLoader : function (mode)
		{
			var deferred = $q.defer();

			if (mode == false)
				return deferred.resolve();
			return deferred.promise;
		}
	}
} ]);
