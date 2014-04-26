/**
 * ajaxer.js
 *
 * Creation time: 19:37
 * Creation date: 25.04.2014
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

var phonebookAjaxer = new function () {

    this.sendRemove = function (id, actualPage) {
        if(!confirm('Are you sure ?'))
            return;

        $.post('/phonebook/remove/'+id, function(removeData){
            $.post('/phonebook/'+actualPage, {modalData: removeData} , function(paginationData){
                $("#mainContainer").html(paginationData);
                $('#modalMessages').modal();
            });
        });
    };
};