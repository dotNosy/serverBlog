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
                                    switch (value)
                                    {
                                        case "starting":
                                            //Quitar otras pos de esta imagen
                                            $('.'+id+'[value=inline]').removeClass("btn-danger");
                                            $('.'+id+'[value=inline]').addClass("btn-outline-danger");
                                            $('.'+id+'[value=ending]').removeClass("btn-dark");
                                            $('.'+id+'[value=ending]').addClass("btn-outline-dark");
                                            $('.'+id+'[value=portada]').removeClass("btn-success");
                                            $('.'+id+'[value=portada]').addClass("btn-outline-success");
                                            $('.'+id+'[value=side]').removeClass("btn-primary");
                                            $('.'+id+'[value=side]').addClass("btn-outline-primary");
                                            
                                            //Quitar estilo a otra imagen puesta en esta pos
                                            $(':button[value='+value+']').removeClass("btn-success");
                                            $(':button[value='+value+']').addClass("btn-outline-success");
                                           
                                            //Poner este boton en la pos
                                            $(myBtn).addClass("btn-success");
                                            $(myBtn).removeClass("btn-outline-success");
                                        break;

                                        case "inline":
                                            //Quitar otras pos de esta imagen
                                            $('.'+id+'[value=starting]').removeClass("btn-success");
                                            $('.'+id+'[value=starting]').addClass("btn-outline-success");
                                            $('.'+id+'[value=ending]').removeClass("btn-dark");
                                            $('.'+id+'[value=ending]').addClass("btn-outline-dark");
                                            $('.'+id+'[value=portada]').removeClass("btn-success");
                                            $('.'+id+'[value=portada]').addClass("btn-outline-success");
                                            $('.'+id+'[value=side]').removeClass("btn-primary");
                                            $('.'+id+'[value=side]').addClass("btn-outline-primary");

                                            //Quitar estilo al anterior imagen puesta en esta pos
                                            $(':button[value='+value+']').removeClass("btn-danger");
                                            $(':button[value='+value+']').addClass("btn-outline-danger");

                                            //Poner este boton en la pos
                                            $(myBtn).addClass("btn-danger");
                                            $(myBtn).removeClass("btn-outline-danger");
                                        break;

                                        case "ending":
                                            //Quitar otras pos de esta imagen
                                            $('.'+id+'[value=starting]').removeClass("btn-success");
                                            $('.'+id+'[value=starting]').addClass("btn-outline-success");
                                            $('.'+id+'[value=inline]').removeClass("btn-danger");
                                            $('.'+id+'[value=inline]').addClass("btn-outline-danger");
                                            $('.'+id+'[value=portada]').removeClass("btn-success");
                                            $('.'+id+'[value=portada]').addClass("btn-outline-success");
                                            $('.'+id+'[value=side]').removeClass("btn-primary");
                                            $('.'+id+'[value=side]').addClass("btn-outline-primary");

                                            //Quitar estilo al anterior imagen puesta en esta pos
                                            $(':button[value='+value+']').removeClass("btn-dark");
                                            $(':button[value='+value+']').addClass("btn-outline-dark");
                                        
                                            //Poner este boton en la pos
                                            $(myBtn).addClass("btn-dark");
                                            $(myBtn).removeClass("btn-outline-dark");
                                        break;

                                        case "side":
                                            //Quitar otras pos de esta imagen
                                            $('.'+id+'[value=starting]').removeClass("btn-success");
                                            $('.'+id+'[value=starting]').addClass("btn-outline-success");
                                            $('.'+id+'[value=inline]').removeClass("btn-danger");
                                            $('.'+id+'[value=inline]').addClass("btn-outline-danger");
                                            $('.'+id+'[value=portada]').removeClass("btn-success");
                                            $('.'+id+'[value=portada]').addClass("btn-outline-success");
                                            $('.'+id+'[value=ending]').removeClass("btn-dark");
                                            $('.'+id+'[value=ending]').addClass("btn-outline-dark");

                                            //Poner este boton en la pos
                                            $(myBtn).addClass("btn-primary");
                                            $(myBtn).removeClass("btn-outline-primary");
                                        break;

                                        case "portada":
                                            //Quitar otras pos de esta imagen
                                            $('.'+id+'[value=starting]').removeClass("btn-success");
                                            $('.'+id+'[value=starting]').addClass("btn-outline-success");
                                            $('.'+id+'[value=inline]').removeClass("btn-danger");
                                            $('.'+id+'[value=inline]').addClass("btn-outline-danger");
                                            $('.'+id+'[value=ending]').removeClass("btn-dark");
                                            $('.'+id+'[value=ending]').addClass("btn-outline-dark");
                                            $('.'+id+'[value=side]').removeClass("btn-primary");
                                            $('.'+id+'[value=side]').addClass("btn-outline-primary");

                                            //Quitar estilo al anterior imagen puesta en esta pos
                                            $(':button[value='+value+']').removeClass("btn-success");
                                            $(':button[value='+value+']').addClass("btn-outline-success");

                                            //Poner este boton en la pos
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