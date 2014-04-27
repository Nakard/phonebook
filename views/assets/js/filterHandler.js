/**
 * filterHandler.js
 *
 * Creation date: 2014-04-27
 * Creation time: 01:49
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

/**
 * Singleton handling actions associated with the name and phone filter
 */
var filterHandler = new function () {

    /**
     * jQuery object to which the handler listen
     * @type {jQuery}
     */
    var filter = $("#filter");

    /**
     * The string that's passed to the pagination mechanism
     * @type {string|null}
     */
    var filterValue = null;

    /**
     * Actually running timeout
     * @type {number}
     */
    var timeout = setTimeout();

    /**
     * Delay between last keyup and actual filter
     * @type {number}
     */
    var delay = 500;

    /**
     * The page that was set when the handler was set to listen
     * @type {number}
     */
    var actualPage = null;

    /**
     * Clears running timeout
     */
    var clear = function() {
        clearTimeout(timeout);
    };

    /**
     * Activates the listener on index open, passes the actual page for reference
     * @param {number} page
     */
    this.startListening = function (page) {
        $(filter).keyup(listen);
        actualPage = page;
    };

    /**
     * Main listening method, send appropriate request after timeout runs out
     */
    var listen = function() {
        if(timeout)
            clear();
        timeout = setTimeout(function(){
            filterValue = $(filter).val();
            $.post('/phonebook/'+actualPage,{filter: filterValue}, function(paginationData){
                $("#mainContainer").html(paginationData);
            });
        },delay);
    };

    /**
     * Gets the filter value
     * @returns {string|null}
     */
    this.getFilterValue = function () {
        return filterValue;
    };
};