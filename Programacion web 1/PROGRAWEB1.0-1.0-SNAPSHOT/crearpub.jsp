

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Crear publicacion</title>

<link rel="stylesheet" href="css/fcfm.css">
<link rel="stylesheet" href="css/bootstrap.min.css">

</head>
<body>
   <div class="container"></div>
<div id="idDivRow1" class="row"></div>
<div class="col-6"></div>
<h1>Crear publicacion</h1>
<div class="col-6"></div>


<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="Home.jsp">Inicio</a>
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
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Busqueda</button>
      </form>
    </div>
  </div>
</nav>

 <form id="formRegistro" action="crearpub" method="post" enctype="multipart/form-data">
     
<div class="mb-3">
    <label for="ftitulo" class="form-label">Titulo de publicacion</label>
    <input type="text" class="form-control" name="ftitulo" id="ftitulo" placeholder="flash,superman, batman, etc">
  </div>

  <div class="mb-3">
    <label for="fdesc" class="form-label">Texto de publicacion:</label>
    <input type="text" class="form-control"name="fdesc" id="fdesc" placeholder="comic superman no1">
  </div>

<div class="mb-3">
    <label for="fcat" class="form-label">Categoria de publicacion:</label>
    <input type="text" class="form-control" name="fcat" id="fcat" placeholder="Comic">
  </div>

  <div class="mb-3">
    <label for="fFoto" class="form-label">Foto (opcional)</label>
    <input type="file" id="txtFoto" name="fFoto" class="form-control">
    </label>
  <br>
  <script src="Crear.js"></script>
  <input type="submit" id="Publicar" name="btnpubli" value="Publicar">
<label>
  </div>
     
 </form>

<br>
<br>

</body>
</html>
