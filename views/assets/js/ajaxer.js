/**
 * ajaxer.js
 *
 * Creation time: 19:37
 * Creation date: 25.04.2014
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

/**
 * Singleton handling most of ajax requests
 */
var phonebookAjaxer = new function () {

    /**
     * Additional field for checking forms in modals
     * @type {string}
     */
    var initialFormValue = '';

    /**
     * Returns the initial form value from the modal
     * @returns {string}
     */
    var getInitialFormValue = function () {
        return initialFormValue;
    };

    /**
     * Sets the initial form value from the modal
     * @param form
     */
    var setInitialFormValue = function (form) {
        initialFormValue = $(form).serialize();
    };

    /**
     * Refreshes the index page with pagination
     * @param actualPage
     */
    this.refreshIndexSite = function (actualPage) {
        $.post('/phonebook/'+actualPage, {filter: filterHandler.getFilterValue()},function(paginationData){
            $("#mainContainer").html(paginationData);
            $('#modalMessages').modal('show');
        });
    };

    /**
     * Sends remove number request
     * @param id
     * @param actualPage
     */
    this.sendRemove = function (id, actualPage) {
        if(!confirm('Are you sure ?'))
            return;

        $.post('/phonebook/remove/'+id, function(removeData){
            modalHandler.setModalOutput(removeData);
            phonebookAjaxer.refreshIndexSite(actualPage);
        });
    };

    /**
     * Gets the import form from csv controller, sets additional listener
     * @param {number} actualPage
     */
    this.getImportForm = function(actualPage) {
        $.get('/phonebook/csv/import', function(response){
            $("#modal").html(response);
            $("#modalMessages").modal('show');
            $("#fileUpload").submit(function(e){
                e.preventDefault();
                uploader.readFiles(actualPage);
            });
        });
    };

    /**
     * Gets the form for editing the appropiate object
     * @param {number} id           Edited object ID
     * @param {number} actualPage   Actual page from index
     * @param {string} type         Type of object
     */
    this.getEditForm = function(id, actualPage, type) {
        $.get('/phonebook/edit/'+type+'/'+id, function(response) {
            $("#modal").html(response);
            $("#modalMessages").modal('show');
            enableFormListener(id,actualPage,type);
        });
    };
    /**
     * Sends data from edit form
     * @param {number}  id          Edited object ID
     * @param {form}    data        Form Data
     * @param {number}  actualPage  Actual page from index
     * @param {string}  type        Type of object
     */
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

    /**
     * Listener action on edit form
     * @param event
     */
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

    /**
     * Enables listener on edit form
     * @param {number}  id          Edited object ID
     * @param {string}  actualPage  Actual page from index
     * @param {string}  type        Type of object
     */
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