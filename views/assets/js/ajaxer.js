/**
 * ajaxer.js
 *
 * Creation time: 19:37
 * Creation date: 25.04.2014
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

var phonebookAjaxer = new function () {

    var initialFormValue = '';

    var getInitialFormValue = function () {
        return initialFormValue;
    };

    var setInitialFormValue = function (form) {
        initialFormValue = $(form).serialize();
    };

    this.sendRemove = function (id, actualPage) {
        if(!confirm('Are you sure ?'))
            return;

        $.post('/phonebook/remove/'+id, function(removeData){
            modalHandler.setModalOutput(removeData);
            $.post('/phonebook/'+actualPage, {filter: filterHandler.getFilterValue()},function(paginationData){
                $("#mainContainer").html(paginationData);
                $('#modalMessages').modal('show');
            });
        });
    };
    this.getEditForm = function(id, actualPage, type) {
        $.get('/phonebook/edit/'+type+'/'+id, function(response) {
            $("#modal").html(response);
            $("#modalMessages").modal('show');
            enableFormListener(id,actualPage,type);
        });
    };
    var sendEdit = function(id, data, actualPage, type) {
        $.post('/phonebook/edit/'+type+'/'+id, data, function(response){
            modalHandler.setModalOutput(response);
            if('4' === response.status.charAt(0))
            {
                enableFormListener(id,actualPage,type);
                return;
            }
            $.post('/phonebook/'+actualPage, {filter: filterHandler.getFilterValue()},function(paginationData){
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
        sendEdit(event.data.id,submitValue,event.data.actualPage,event.data.type);
    };

    var enableFormListener = function (id,actualPage,type) {
        var form = $("#editForm");
        if('' === getInitialFormValue())
            setInitialFormValue(form);
        $(form).submit(
            {id: id, actualPage: actualPage, type: type,compareValue: getInitialFormValue()},
            formSubmitListener
        );
    };
};