$(function ()
{
    $("input[name='imagenes[]'").change(function (e) {
        uploadFile();
    });
    
    $("#listImgs").on("click", ".delete-img", function (e) {
        //LIST OF FILES FROM THE DOM ELEMENT INPUT
        let inputFileList = $("input[name='imagenes[]']").prop('files');

        //Name of the file to delete
        const fileToDelete = $(this).parent().text();

        //Make an array of files from the DOM element
        let fileList = Array.from(inputFileList);

        //Recorrer todos los files del input
        for (let i = 0; i < fileList.length; i++) 
        {
            const file = fileList[i];

            //Borrar fichero
            if (file.name.trim() == fileToDelete.trim()) {
                fileList.splice(i, 1);
                $(this).parent().fadeOut();
            }
        }

        //Crear lista que sobreescribira la existente
        let newList = new DataTransfer();

        //Añadir a newList los ficheros que quedaron tras borrar
        for (let i = 0; i < fileList.length; i++) {
            const file = fileList[i];
            newList.items.add(file);
        }

        //DOM ELEMENT INPUT FILE
        let inputFile = $("input[name='imagenes[]']")[0];

        //SET new file list to the input file
        inputFile.files = newList.files;
    });
});

function uploadFile()
{
    const fileList = $("input[name='imagenes[]']").prop('files');
    $("#listImgs").empty();

    for (let i = 0; i < fileList.length; i++) 
    {
        const file = fileList[i];    
        
        let fragmented_file = file.name.split(".");

        if (fragmented_file.length > 2 || fragmented_file.length <= 0) {
            errorFormato();
            return false;
        }

        fragmented_file = file.name.split(".").pop();
        
        const allowedExtension = ["jpg","png","gif"];

        //? pos 1 tiene que ser la extension si no error
        if (!allowedExtension.includes(fragmented_file)) {
            errorExtension();
            return false;
        }

        //Listar las imagenes
        $("#listImgs").append("<p><i class='delete-img btn-outline-danger fas fa-times-circle mr-2'></i>"+file.name+"</p>");
    }
}

function errorFormato()
{
    $.confirm({
        title: 'Error en el fichero',
        content: 'El nombre/formato del fichero es incorrecto',
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });

    cleanFileInput();
}

function errorExtension()
{
    $.confirm({
        title: 'Error en el fichero',
        content: 'La extension de la imagen no es valida',
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });

    cleanFileInput();
}

function cleanFileInput() {
    $("input[name='imagenes[]']").val(null);
}