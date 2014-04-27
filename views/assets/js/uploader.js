/**
 * uploader.js
 *
 * Creation date: 2014-04-27
 * Creation time: 14:23
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

/**
 * Singleton for uploading data
 */
var uploader = new function () {

    /**
     * Object of accepted types for upload
     */
    var acceptedTypes = {
        "text/csv" : true
    };

    /**
     * Generates FormData object to pass, checks files for appropriate type
     * @param actualPage
     */
    this.readFiles = function (actualPage) {
        removeFileError();
        var formElement = $("#fileUpload").get(0);
        var formdata = new FormData(formElement);
        var files = $(formElement).find("#upload_file").get(0).files;
        var passed = false;
        $.each(files, function(index,file){
            if(!acceptedTypes[file.type])
            {
                $("#modalMessage").append($('<div/>',{
                    id: 'fileError'
                }));
                $("#fileError").append($('<p/>',{
                    class: 'label label-danger',
                    text: 'The only supported MIME type is text/csv, please choose the appropriate file'
                }));
                return false;
            }
            passed = true;
            return true;
        });
        if(passed)
        {
            removeFileError();
            createProgressBar();
            $.ajax({
                url: '/phonebook/csv/import',
                data: formdata,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response){
                    console.log(response);
                    modalHandler.setModalOutput(response);
                    removeProgressBar();
                    phonebookAjaxer.refreshIndexSite(actualPage);
                    var count = response.count;
                    if(count > 0)
                        $("#exportCsv, #filter").removeClass('hidden');
                }
            });
        }
    };

    /**
     * Removes element containing uploaders errors
     */
    var removeFileError = function () {
        $("#fileError").remove();
    };

    /**
     * Pretty self explanatory
     */
    var removeProgressBar = function () {
        $("#progress").remove();
    };

    /**
     * Creates the progress bar seen during the import ajax call
     */
    var createProgressBar = function () {
        var subDiv = $("<div/>",{
            class: 'progress-bar',
            role: 'progressbar',
            'aria-valuenow': '100',
            'aria-valuemin': '0',
            'aria-valuemax': '100',
            style: 'width:100%'
        }).append($('<span/>',{
                class: 'sr-only',
                text: '45% Complete'
            }));
        var progressDiv = $("<div/>",{
            class: 'progress progress-striped active',
            id: 'progress'
        }).append(subDiv);

        $("#modalMessage").append(progressDiv);
    };
};