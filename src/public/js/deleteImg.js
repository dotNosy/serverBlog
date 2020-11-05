$(function () { 
    $(".deleteImg").click(function (e) { 
        $.confirm({
            title: 'Eliminar fichero',
            content: 'La imagen se eliminara permanentemente y no se podra recuperar, estas seguro?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                Si: () => {
                    const id = $(this).attr('id');
                    $.ajax({
                        type: "post",
                        url: "/post/deleteImg",
                        data: {id:id},
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            //Error validaciones del back
                            if (response.status === 'error') {
                               errorBorrar();
                            }
                            else if(response.status === true)
                            {
                                //TODO: Transiccion de borrar o algo
                                $('#' + id).parent().parent().parent().remove();
                            }
                            else {
                                errorBorrar();
                            }
                        },
                        error: function (error) {
                            console.log(error);
                            errorBorrar();
                        }
                    });
                },
                No: () => {
                    return false;
                }
            }
        });
    });
});

function errorBorrar() {
    $.confirm({
        title: 'Error inesperado',
        content: 'Un error ocurrio al intentar borrar la imagen.',
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}