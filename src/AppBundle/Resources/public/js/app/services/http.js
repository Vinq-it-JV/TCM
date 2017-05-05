/**
 * @ngdoc httpService
 * @name portalApp.service:httpService
 * @description Dit is een http service
 *  ## Echt waar!!
 */
angular
    .module('tcmApp')
    .factory('Http', [ '$http', '$window', '$document', '$sce', '$interpolate',
    function($http, $window, $document, $sce, $interpolate) {

    var message_header;
	var error_state = false;
	var error_message = '';
	var error_content = '';
	var error_type = 'data';
	
	var buildHeader = function(cb_Fail, urlEncode) {
		
        message_header = {
			'headers' : 
			{
				'content-type' : 'application/json',
				'cache-control' : 'no-cache, no-store, max-age=0, must-revalidate',
				'pragma' : 'no-cache'
			},
		    'cache': false,
			'transformResponse': function (data, headersGetter) 
		    {
		        try 
		        {
		        	var jsonObject = JSON.parse(data);
		            return jsonObject;
		        }
		        catch (e) 
		        {
		        	if (data.substring(0,1) == "<" && data.indexOf('loginForm') != -1)
			    	{	
			    		$window.location.href = Routing.generate('tcm_splash');
			    		return {};
			    	}
		        	
		        	error_state = true;
		            error_message = e.message;
		        	error_content = data;
		        	error_type = 'data';
		        	
		            if (cb_Fail)
		            	cb_Fail();
		        }
		    }
		};
	};

	var checkStatus = function (status)	{
		
		switch(status)
		{
			case 500:
				error_state = true;
				error_message = "Server error " + status;
				error_content = $sce.trustAsHtml(error_content.substring(error_content.indexOf("<"), error_content.length));
				error_type = 'html';
				break;
			default:
				break;
		}
	};
	
	var doPost = function(postdata, cb_Success, cb_Fail) {
		var result;

		if (error_state)
			return;
		
		buildHeader(cb_Fail);
		result = $http.post(postdata.url, postdata.payload, message_header);

		result.success(function(data, status, headers, config) {
			if (cb_Success)
				cb_Success(data);
		});

		result.error(function(data, status, headers, config) {
			checkStatus(status);
			if (cb_Fail)
				cb_Fail(data);
		});
		return result;
	};

	var doPut = function(putdata, cb_Success, cb_Fail) {
		var result;
		
		if (error_state)
			return;

		buildHeader(cb_Fail);
		result = $http.put(putdata.url, putdata.payload, message_header);

		result.success(function(data, status, headers, config) {
			if (cb_Success)
				cb_Success(data);
		});

		result.error(function(data, status, headers, config) {
			checkStatus(status);
            if (cb_Fail)
				cb_Fail(data);
		});
		return result;
	};

	var doGet = function(getdata, cb_Success, cb_Fail) {
		var result;

		if (error_state)
			return;

		buildHeader(cb_Fail);
		result = $http.get(getdata.url, message_header);

		result.success(function(data, status, headers, config) {
			if (cb_Success)
				cb_Success(data);
		});

		result.error(function(data, status, headers, config) {
			checkStatus(status);
        	if (cb_Fail)
				cb_Fail(data);
		});
		return result;
	};

	var doDelete = function(deletedata, cb_Success, cb_Fail) {
		var result;

		if (error_state)
			return;

		buildHeader(cb_Fail);
		result = $http.delete(deletedata.url, message_header);

		result.success(function(data, status, headers, config) {
			if (cb_Success)
				cb_Success(data);
		});

		result.error(function(data, status, headers, config) {
			checkStatus(status);
			if (cb_Fail)
				cb_Fail(data);
		});
		return result;
	};

	return {
		post : function(data, cb_Success, cb_Fail) {
			return doPost(data, cb_Success, cb_Fail);
		},
		put : function(data, cb_Success, cb_Fail) {
			return doPut(data, cb_Success, cb_Fail);
		},
		get : function(data, cb_Success, cb_Fail) {
			return doGet(data, cb_Success, cb_Fail);
		},
        delete : function(data, cb_Success, cb_Fail) {
            return doDelete(data, cb_Success, cb_Fail);
        },
		error_state : function () {
			return error_state;
		},
		error_message : function () {
			return error_message;
		},
		error_content : function () {
			return error_content;
		},
		error_type : function () {
			return error_type;
		},
		error_clr : function () {
			error_state = false;
			error_message = '';
			error_content = '';
			error_type = 'data';
			return error_state;
		}
	};
} ]);
