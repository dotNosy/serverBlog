$(function () { 
    $(".btnpos").click(function (e) {
        const myBtn = $(this);
        $.confirm({
            title: 'Estabelcer posicion de la foto',
            content: '',
            type: 'green',
            typeAnimated: true,
            buttons: {
                Si: () => {
                    const id = $(this).attr('id');
                    const value = $(this).attr('value');

                    $.ajax({
                        type: "post",
                        url: "/post/setImgPosInPost",
                        data: {id:id, pos:value},
                        dataType: "json",
                            success: function (response) {
                                console.log(response);
                                //Error validaciones del back
                                if (response.status === 'error') {
                                    errorSetPos();
                                }
                                else if(response.status === true)
                                {
                                    switch (response.pos)
                                    {
                                        case "starting":
                                            $(myBtn).addClass("btn-success");
                                            $(myBtn).removeClass("btn-outline-success");
                                        break;

                                        case "inline":
                                            $(myBtn).addClass("btn-danger");
                                            $(myBtn).removeClass("btn-outline-danger");
                                        break;

                                        case "ending":
                                            $(myBtn).addClass("btn-dark");
                                            $(myBtn).removeClass("btn-outline-dark");
                                        break;

                                        case "side":
                                            $(myBtn).addClass("btn-primary");
                                            $(myBtn).removeClass("btn-outline-primary");
                                        break;

                                        case "portada":
                                            $(myBtn).addClass("btn-success");
                                            $(myBtn).removeClass("btn-outline-success");
                                        break;
                                    }
                                }
                                else {
                                    errorSetPos();
                                }
                            },
                            error: function (error) {
                                console.log(error);
                                errorSetPos();
                            }
                    });
                },
                No: () => {
                }
            }
        });
    });
});

function errorSetPos() {
    $.confirm({
        title: 'Error inesperado',
        content: 'Un error ocurrio al actualizar la posicion de la imagen',
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}