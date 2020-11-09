$(function () { 
    $(".showNotifications").click(function (e) { 
        $.ajax({
            type: "post",
            url: "/profile/notifications",
            data: {},
            dataType: "json",
            success: function (response) {
                $("#notificaciones-content").empty();
                for (let i = 0; i < response.notificaciones.length; i++) {
                    const element = response.notificaciones[i];
                    // console.log(element);
                    $("#notificaciones-content").append(element);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#notificaciones-content").on("click", ".deleteNotifications", function (e) {
        const id = $(this).attr("id");

        $.ajax({
            type: "post",
            url: "/profile/deleteNotificationsByNotificationID",
            data: {id:id},
            dataType: "json",
            success: function (response) {
                if (response.status == true) {

                }
                else if (response.error != "") {
                    errorBorrar(response.error);
                }
                else {
                    errorBorrar("Error al eliminar las notificaciones.");
                }
            },
            error: function (error) {
                errorBorrar(error);
            }
        });
    });

    $("#borrarTodos").on("click", function (e) {
        $.ajax({
            type: "post",
            url: "/profile/deleteNotificationsByUserID",
            data: {},
            dataType: "json",
            success: function (response) {
                if (response.status == true) {
                    $('#staticBackdrop').modal('hide');
                }
                else if (response.error != "") {
                    errorBorrar(response.error);
                }
                else {
                    errorBorrar("Error al eliminar las notificaciones.");
                }
            },
            error: function (error) {
                //errorBorrar(error);
                console.log(error);
            }
        });
    });
});

function errorBorrar($error) {
    $.confirm({
        title: 'Error inesperado',
        content: $error,
        type: 'red',
        typeAnimated: true,
        buttons: {
            close: function () {
            }
        }
    });
}