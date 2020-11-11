$(function () {
    $("button[name='comment']").on("click",function (e) {
        const button = $(this);
        const id = $(this).siblings("input[name=id]").val();
        const text = $(this).siblings("textarea").val();
        const idPAdre = $(this).siblings("input[name=id_padre]").val();
        
        $("#spinner").fadeIn(function (){
            $(this).show();
        });

        $.ajax({
            type: "POST",
            url: "/post/addComment",
            data: {id:id, text:text, id_padre: idPAdre},
            dataType: "JSON",
            success: function (response) {
                $(button).siblings("textarea").val("");

                if (response.error !== undefined && response.error != "") {
                    console.log(response.error);
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
                }
                else if (response.status == true) {
                    if (response.comment !== undefined && response.comment != "") {
                        $("#comments").append(response.comment);
                    }
                    else if(response.answer !== undefined && response.answer != "") {
                        $("#" + idPAdre + "-response").append(response.answer);
                    }
                }
                else {
                    errorInesperado();
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