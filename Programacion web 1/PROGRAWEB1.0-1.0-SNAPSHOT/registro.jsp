

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Registro</title>

<link rel="stylesheet" href="css/ola3.css">

    </head>
    
<body>

<div id="divregistro" style="background-color:rgb(239, 140, 193);">
    <form id="formRegistro" action="registroServlet" method="post" enctype="multipart/form-data">
        
        <label for="fusuario">Usuario:</label><br>
        <input class="inputRegistrar" type="text" id="txtNombreUsuario" name="fusuario"><br>

        <label for="lname">Contraseña:</label><br>
        <input class="inputRegistrar" type="text" id="txtPassword" name="fcontra">
        
        <label for="fname">Nombre:</label><br>
        <input class="inputRegistrar" type="text" id="txtnombre" name="fnombre"><br>

        <label for="fapelliido">Apellidos:</label><br>
        <input class="inputRegistrar" type="text" id="txtapelliido" name="fapelliido"><br>
        
        <label for="fFoto">Foto de perfiil:</label><br>
        <input class="inputRegistrar" type="file" id="txtFoto" name="fFoto"><br>
        
        <label for="ffecha">Fecha Nacimiento:</label><br>
        <input class="inputRegistrar" type="date" id="txtfecha" name="ffecha"><br>

        <br>
        
        <div id="conexiones">
            <a href="Login.jsp">Ya tienes una cuenta?</a>
        </div>
        
        </label>
        <br>
        <input class="btnRegistrar" type="submit" id="Registrar" name="bRegistrar" value="Registrar">
        <label>
        <br/>                
    </form>

<script src="js/regis.js"></script>
</body>
</html>
