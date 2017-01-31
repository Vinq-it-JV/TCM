/**
 * Created by jeroenvisser on 28-05-14.
 *
 * By setting the focus="true" directive in an <input element this
 * element will be set to focus on page show.
 */

angular
    .module('tcmApp')
    .directive('focus', function ($timeout, $parse) {
    	return {
    		link: function(scope, element, attrs) {
    	    var model = $parse(attrs.focus);
    	    scope.$watch(model, function(value) {
    	    	if(value === true) { 
    	    		$timeout(function() {
    	    			element[0].focus(); 
    	    		});
    	        }
    	    });
    	    element.bind('blur', function() {
    	        element[0].blur();
    	    });
    	}
    };
});