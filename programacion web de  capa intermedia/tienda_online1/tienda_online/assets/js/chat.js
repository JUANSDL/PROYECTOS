let chatActual = null;

function abrirChatExistente(id_chat, nombre_contacto) {
    chatActual = id_chat;
    $("#nombre-contacto").text(nombre_contacto);
    $("#chat-input").removeClass("d-none");
    cargarMensajes(id_chat);
    $("#mensaje-texto").focus();
}

function iniciarChat(id_usuario, nombre_usuario) {
    $.ajax({
        url: "ajax/create_chat.php",
        method: "POST",
        data: { id_usuario2: id_usuario },
        success: function(response) {
            abrirChatExistente(JSON.parse(response).id_chat, nombre_usuario);
            actualizarListaChats();
        }
    });
}

function cargarMensajes(id_chat) {
    $.ajax({
        url: "ajax/get_messages.php",
        data: { id_chat: id_chat },
        success: function(response) {
            $("#chat-messages").html(response);
            $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
        }
    });
}

function enviarMensaje() {
    if (!chatActual) return;
    let mensaje = $("#mensaje-texto").val().trim();
    if (mensaje === "") return;

    $.ajax({
        url: "ajax/send_message.php",
        method: "POST",
        data: {
            id_chat: chatActual,
            texto: mensaje
        },
        success: function() {
            $("#mensaje-texto").val("");
            cargarMensajes(chatActual);
            actualizarListaChats();
        }
    });
}

function actualizarListaChats() {
    $.ajax({
        url: "ajax/load_chats.php",
        success: function(response) {
            $("#lista-chats").html(response);
        }
    });
}

$(document).ready(function() {
    $("#buscarUsuario").autocomplete({
        source: "ajax/search_users.php",
        minLength: 2,
        select: function(event, ui) {
            iniciarChat(ui.item.id, ui.item.label);
        }
    });

    $("#mensaje-texto").keypress(function(e) {
        if (e.which == 13 && !e.shiftKey) {
            e.preventDefault();
            enviarMensaje();
        }
    });

    $("#btn-enviar").click(enviarMensaje);

    actualizarListaChats();
});
