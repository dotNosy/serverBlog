$(function () {
    //AÑADIR COMENTARIO
    $(".container").on("click", "button[name='comment']",function (e) {
        const button = $(this);
        //Datos
        const id = $(this).siblings("input[name=id]").val();
        const text = $(this).siblings("textarea").val();
        const idPAdre = $(this).siblings("input[name=id_padre]").val();
        
        //Animacion de carga
        $("#spinner").fadeIn(function (){
            $(this).show();
        });

        $.ajax({
            type: "POST",
            url: "/post/addComment",
            data: {id:id, text:text, id_padre:idPAdre},
            dataType: "JSON",
            success: function (response) {
                $(button).siblings("textarea").val("");
                //Error controlado
                if (response.error !== undefined && response.error != "") {
                    switch(response.error)
                    {
                        case "Tienes que estar logueado para comentar un post":
                            $.confirm({
                                title: '¡Error!',
                                content: response.error,
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    close: function () {
                                    }
                                    ,Ir: function() {
                                        window.location.assign("http://www.talde2blog.com/login/")
                                    }
                                }
                            });
                        break;

                        default:
                            $.confirm({
                                title: '¡Error!',
                                content: response.error,
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    close: function () {
                                    }
                                }
                            });
                        break;
                    }

                    console.log(response.error);
                }
                //Añadido
                else if (response.status == true) {
                    if (response.comment !== undefined && response.comment != "") {
                        $("#comments").prepend(response.comment);
                    }
                    else if(response.answer !== undefined && response.answer != "") {
                        $("#" + idPAdre + "-response").prepend(response.answer);
                    }
                }
                else {
                    errorInesperado();
                    console.log(response.error);
                }
            },
            error: function(error) {
                console.log(error);
                errorInesperado();
            }
        });

        $("#spinner").fadeOut(function (){
            $(this).hide();
        });
    });

    //eLIMINAR COMENTARIO
    $(".container").on("click", "button[name='eliminar']",function (e) {
        const button = $(this);
        const id = $(button).attr("id");
        alert(id);
        $.ajax({
            type: "POST",
            url: "/post/deleteComment",
            data: {id:id},
            dataType: "JSON",
            success: function (response) {
                //Error controlado
                if (response.error !== undefined && response.error != "") 
                {
                    $.confirm({
                        title: '¡Error!',
                        content: response.error,
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                            }
                        }
                    });

                    console.log(response.error);
                }
                //Eliminado
                else if (response.status == true) 
                {
                    $(button).parent().parent().parent().remove();
                    $.confirm({
                        title: '!Eliminado!',
                        content: "El comentario se borro con exito",
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                            }
                        }
                    });
                }
                else {
                    errorEliminar();
                    console.log(response.error);
                }
            },
            error: function(error) {
                console.log(error);
                errorEliminar();
            }
        });
    });
});

function errorInesperado()
{
    $.confirm({
        title: 'Error inesperado',
        content: 'Un error ocurrio al intentar añadir el comentario',
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}

function errorEliminar()
{
    $.confirm({
        title: '!Error!',
        content: "El comentario no se pudo borrar",
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}