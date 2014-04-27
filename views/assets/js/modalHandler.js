/**
 * modalHandler.js
 *
 * Creation date: 2014-04-26
 * Creation time: 19:20
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

/**
 * Singleton handling the behaviour of modal messages
 */
var modalHandler = new function(){

    /**
     * Sets the appropriate fields in the modal based on passed data
     * @param {object} modalData
     */
    this.setModalOutput = function(modalData){
        $("#modalLabel").text(modalData.title);
        var message = $("#modalMessage");
        var header = $("#modalHeader");
        resetHeader(header);
        if(modalData.status.charAt(0) === '4')
        {
            var errors = createListFromErrors(modalData.formErrors);
            var panel = createPanel(errors);
            $(message).html(panel);
            $(message).append(modalData.form);
            $(header).addClass('bg-danger');
            return;
        }
        $(message).text(modalData.message);
        $(header).addClass('bg-primary');
    };

    /**
     * Resets modal header coloring
     * @param {jQuery} header
     */
    var resetHeader = function(header){
        $(header).removeClass('bg-primary bg-danger');
    };

    /**
     * Creates Panel with errors
     * @param   {array}                 errorsList
     * @returns {jQuery|HTMLElement}
     */
    var createPanel = function(errorsList){
        var panel = $('<div/>', {
            class: "panel panel-default"
        });
        var panelHeading = $('<div/>', {
            class:  "panel-heading",
            id:     "panelHeading"
        }).append('Form errors');
        var panelBody = $('<div/>', {
            class:  "panel-body",
            id:     "panelBody"
        }).append(errorsList);
        $(panel).append(panelHeading).append(panelBody);
        return panel;
    };

    /**
     * Parses errors and generates an unordered list
     * @param   {array}                  errors
     * @returns {*|jQuery|HTMLElement}
     */
    var createListFromErrors = function (errors) {
        var list = $('<ul/>',{
            class: 'list-group'
        });
        for(formKey in errors)
        {
            if(errors.hasOwnProperty(formKey))
            {
                if('object' === $.type(errors[formKey]))
                {
                    for(key in errors[formKey])
                    {

                        if(errors[formKey].hasOwnProperty(key))
                        {
                            list.append(createErrorItemForList(formKey + ': ' +errors[formKey][key]));
                        }
                    }
                }
                else
                {
                    list.append(createErrorItemForList(errors[formKey]))
                }
            }
        }
        return list;
    };

    /**
     * Creates single item for the list
     * @param   {string}    itemBody
     * @returns {jQuery}
     */
    var createErrorItemForList = function (itemBody) {
        return $('<li/>', {
            class: "list-group-item list-group-item-danger"
        }).append(itemBody);
    }

};