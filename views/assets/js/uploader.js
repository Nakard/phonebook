/**
 * uploader.js
 *
 * Creation date: 2014-04-27
 * Creation time: 14:23
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

var uploader = new function () {

    var acceptedTypes = {
        "text/csv" : true
    };

    this.readFiles = function () {
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
                    text: 'The only supported format is text/csv, please choose the appropriate file'
                }));
                return false;
            }
            passed = true;
            return true;
        });
        if(passed)
        {
            removeFileError();
            var request = new XMLHttpRequest();
            request.open("POST", "/phonebook/csv/import");
            request.send(formdata);
        }
    };

    var removeFileError = function () {
        $("#fileError").remove();
    };
};