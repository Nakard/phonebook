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
            modalHandler.setModalOutput(removeData);
            $.post('/phonebook/'+actualPage, function(paginationData){
                $("#mainContainer").html(paginationData);
                $('#modalMessages').modal('show');
            });
        });
    };
    this.getEditPersonForm = function(id, actualPage){
        $.get('/phonebook/edit/person/'+id, function(response){
            $("#modal").html(response);
            $("#modalMessages").modal('show');
            enableFormListerner(id,actualPage);
        });
    };
    var sendEditPerson = function (id, data, actualPage) {
        $.post('/phonebook/edit/person/'+id, data, function(response){
            $.post('/phonebook/'+actualPage, function(paginationData){
                modalHandler.setModalOutput(response);
                if('4' === response.status.charAt(0))
                {
                    enableFormListerner(id,actualPage);
                }
                $("#mainContainer").html(paginationData);
            });
        });
    };

    var formSubmitListener = function (event) {
        event.preventDefault();
        var submitValue = $(this).serialize();
        if(event.data.compareValue === submitValue)
        {
            $("#modalMessages").modal('hide');
            return;
        }
        sendEditPerson(event.data.id,submitValue,event.data.actualPage);
    };

    var enableFormListerner = function (id,actualPage) {
        var form = $("#editPersonForm");
        var compareValue = $(form).serialize();
        $(form).submit({id: id, actualPage: actualPage, compareValue: compareValue}, formSubmitListener);
    };
};