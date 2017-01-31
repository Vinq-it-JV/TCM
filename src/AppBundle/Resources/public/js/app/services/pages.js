/**
 * @author Jeroen Visser
 * @name Pages.service
 * @description
 *
 *  ## TCM V1.0 Page service
 *
 */
angular
    .module('tcmApp')
    .factory('Pages', [ '$rootScope', '$translate', '$window',
    function($rootScope, $translate, $window) {

    var d_pages = [];
    var d_callbacks = [];
    var d_pageIndex = -1;

    function pageIndex() {
        if (d_pageIndex === -1)
            return null;
        return d_pageIndex;
    }
    ;

    function unknownPage() {
        return "???";
    }
    ;

    return {
        page : function() {
            var index = pageIndex();
            if (index === null)
                return unknownPage();

            return d_pages[index];
        },
        execute : function() {
            var index = pageIndex();
            if (index === null)
                return null;

            // Don't use pages with callback functions in callback
            // function
            // to prevent infinite loops!
            var callback = d_callbacks[index];
            if (callback)
                callback();
            return null;
        },
        showpage : function(pageId, executeFunction) {
            d_pageIndex++;
            d_pages.push(pageId);
            if (executeFunction !== undefined)
                d_callbacks.push(executeFunction);
            return this.page();
        },
        previouspage : function() {
            var index = pageIndex();
            if (index === null)
                return unknownPage();
            if (index === 0)
                return this.startpage();
            this.execute();
            d_pageIndex--;
            d_pages = d_pages.slice(0, -1);
            d_callbacks = d_callbacks.slice(0, -1);
            return this.page();
        },
        backbutton : function() {
            $window.history.back();
        },
        startpage : function() {
            var index = pageIndex();
            if (index === null)
                return unknownPage();
            this.execute();
            d_pages = d_pages.splice(0, 1);
            d_callbacks = d_callbacks.splice(0, 1);
            d_pageIndex = 0;
            this.execute();
            return this.page();
        },
        isstartpage : function() {
            if (d_pageIndex == 0)
                return true;
            return false;
        },
        init : function(pageId, executeFunction) {
            d_pages = [];
            d_callbacks = [];
            d_pageIndex = 0;
            d_pages.push(pageId);
            d_callbacks.push(executeFunction);
            return this.page();
        }
    };
} ]);