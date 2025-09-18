<%-- 
    Document   : index2
    Created on : 4 oct 2023, 16:21:13
    Author     : LMAD 205-08
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYePlace- blog de DC</title>
    <link rel="shortcut icon" href="images/logodc.png" type="image/x-icon">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/RLestilos.css">
</head>
<body>
    <main>
        <div class="container_all">
            <div class="caja_trasera">
                <div class="caja_trasera_login">
                    <h3>Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para ingresar</p>
                    <form id="formLogin" action="login" method="post">
                        <input class="inputLogin" type="text" id="fusuario" placeholder="Nombre de Usuario" name="fusuario"><br>
                        <input class="inputLogin" type="password" id="fcontra" placeholder="Contraseña" name="fcontra">
                        <br><br>
                        <button class="btnLogin" type="submit" id="Iniciar" name="bIniciarSesion" value="Ingresar">Iniciar Sesión</button>
                    </form>
                </div>
                <div class="caja_trasera_reggister">
                    <h3>Regístrate</h3>
                    <p>Regístrate, ¡Es gratis!</p>
                    <a href="registro.jsp" class="btn_registrarse">Registrarse</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
