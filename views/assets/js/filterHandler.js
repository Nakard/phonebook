/**
 * filterHandler.js
 *
 * Creation date: 2014-04-27
 * Creation time: 01:49
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

var filterHandler = new function () {

    var filter = $("#filter");

    var filterValue = null;

    var timeout = null;

    var delay = 1000;

    var actualPage = null;

    var clear = function() {
        clearTimeout(timeout);
    };

    this.startListening = function (page) {
        $(filter).keyup(listen);
        actualPage = page;
    };

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

    this.getFilterValue = function () {
        return filterValue;
    };
};