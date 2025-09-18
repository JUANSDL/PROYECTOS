<

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Perfil</title>

<link rel="stylesheet" href="css/fcfm.css">
<link rel="stylesheet" href="css/bootstrap.min.css">



</head>
<body>
   <div class="container"></div>
<div id="idDivRow1" class="row"></div>
<div class="col-6"></div>
<h1>Perfil de Usuario</h1>
<div class="col-6"></div>

<!-- Background image -->
<div style="background-image: url('https://viajerocasual.com/wp-content/uploads/2022/05/paisajes-de-canada-lago-louise.jpg');"></div>
<!-- Background image -->

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="Home.jsp">Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="Login.jsp">Cerrar secion</a>
        </li>
  
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="crearpub.jsp">Crear Publicacion</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Perfil.jsp">Perfil</a>
        </li>
      </ul>
  
      </form>
    </div>
  </div>
</nav>
 

  <div class="card mb-3" style="width: 1000px;">
    <div class="row g-0">

      <div class="col-md-4">
        <img src=<%String urlImg = "imagenes/usuarios/"+String.valueOf(request.getSession().getAttribute("urlImg"));%> class="img-fluid rounded-start" alt="...">
        
      <form id="formRegistro" action="EditarServlet" method="post" enctype="multipart/form-data">
        
        <label for="fusuario">Usuario:</label><br>
        <input class="inputRegistrar" type="text" id="txtNombreUsuario" name="fusuario" placeholder="Nueva informacion"><br>

        <label for="fpass">Contraseña:</label><br>
        <input class="inputRegistrar" type="text" id="txtPassword" name="fcontra" placeholder="Nueva informacion"><br>
        
        <label for="fname">Nombre:</label><br>
        <input class="inputRegistrar" type="text" id="txtnombre" name="fnombre" placeholder="Nueva informacion"><br>

        <label for="fapelliido">Apellidos:</label><br>
        <input class="inputRegistrar" type="text" id="txtapelliido" name="fapelliido" placeholder="Nueva informacion"><br>
        
        <label for="fFoto">Foto de perfiil:</label><br>
        <input class="inputRegistrar" type="file" id="txtFoto" name="fFoto"><br>
        
        <label for="ffecha">Fecha Nacimiento:</label><br>
        <input class="inputRegistrar" type="date" id="txtfecha" name="ffecha" placeholder="Nueva informacion"><br>


        <br>
        <input class="btnRegistrar" type="submit" id="Registrar" name="bRegistrar" value="Actualizar info">
        <br/>                
    </form>
        
        <form id="formEliminar" action="EliminarUs" method="post">
        <br>
        <input class="btnborrar" type="submit" id="Registrar" name="bRegistrar" value="Eliminar Perfil">
 
        <br/>                
        </form>
        
        
    </div>
  </div>

 

  

<ol id="idlista">
    
    </ol>>
<br>


</body>
</html>