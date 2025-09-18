

<%@page import="com.mycompany.pw1.models.Publicaciones"%>
<%@page import="java.util.List"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Home</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/bootstrap.min.css">



</head>
<body>
   <div class="container"></div>
<div id="idDivRow1" class="row"></div>
<div class="col-6"></div>
<h1>Home</h1>
<div class="col-6"></div>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="Home.jsp">Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="Login.jsp">Cerrar secion </a>
        </li>
     
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="crearpub.jsp">Crear Publicacion</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Perfil.jsp">Perfil</a>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Dropdown link
        </a>
        <div class="NOJALO :c" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
        </div>
    </li>
      </ul>
        
        <form class="d-flex" role="search" id="formBuscarPublicacion" action="Busquedasencilla" method="post">
        
        <label for="txtBusqueda">Busque por Titulo, Descripcion y/o Categoria:</label><br>
        <input class="inputTitulo" type="text" id="txtBusqueda" name="txtBusqueda"><br>
        <br>
        <input class="btnBuscar" type="submit" id="buscarPublicacion" name="buscarPublicacion" value="Busquele">
       
        </form>
        
      
        
    </div>
  </div>
</nav>
 
      <% String idUsuario = String.valueOf(request.getSession().getAttribute("idUsuario"));
        List<Publicaciones> listaPublicaciones =(List) request.getAttribute("ListaPublicaciones");
        String urlImg = "imagenes/usuarios/"+String.valueOf(request.getSession().getAttribute("urlImg"));
        %>
        
  <div class="card">
    <<h1><%=idUsuario%></h1>
        <img src=<%=urlImg%> />
        <% if(listaPublicaciones!=null)
            for(Publicaciones item : listaPublicaciones){     %>
            <h1><%=item.getTitulo()%></h1> <h6><%=item.getCategoria()%></h6>
                   <p><%=item.getDescripcion()%></p>
             <% }  %>
    </div>
  

  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>


  <nav aria-label="Page navigation example">
    <ul class="pagination">
      <li class="page-item"><a class="page-link" href="#">Previous</a></li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>

<br>
<br>

<!-- Footer -->
<footer class="text-center text-lg-start bg-body-tertiary text-muted">
  <!-- Section: Social media -->
  <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
    <!-- Left -->
    <div class="me-5 d-none d-lg-block">
      <span><S></S>iguenos en nuestras redes sociales:</span>
    </div>
    <!-- Left -->

    <!-- Right -->
    <div>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-google"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-linkedin"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-github"></i>
      </a>
    </div>
    <!-- Right -->
  </section>
  <!-- Section: Social media -->

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            <i class="fas fa-gem me-3"></i>Facecat
          </h6>
          <p>
           Pagina dedicada solo y exclusivamente para gatos, cualquier otro contenido 
           sobre otros animeles o personas sera motivo de eliminacion de dicha cuenta.
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Products
          </h6>
          <p>
            <a href="#!" class="text-reset">Angular</a>
          </p>
          <p>
            <a href="#!" class="text-reset">React</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Vue</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Laravel</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Tambien pueden servir 
          </h6>
          <p>
            <a href="#!" class="text-reset">Pricing</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Configuracion</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Ayuda</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Otros</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">Contactanos</h6>
          <p><i class="fas fa-home me-3"></i> Monterrey NL, FCFM</p>
          <p>
            <i class="fas fa-envelope me-3"></i>
            hola_soporte.com
          </p>
          <p><i class="fas fa-phone me-3"></i> + 81 2526 5498</p>
          <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2023 Copyright:
    <a class="text-reset fw-bold" href="Login.jsp">Facecat.com</a>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
