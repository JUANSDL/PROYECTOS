<%-- 
    Document   : index
    Created on : 4 oct 2023, 15:35:23
    Author     : LMAD 205-08
--%>
<%@page import="com.mycompany.pw1.models.Publicaciones"%>
<%@page import="java.util.List"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYePlace- blog de DC</title>
    <link rel="shortcut icon" href="images/logodc.png" type="image/x-icon">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    
<header class="hero">
   <div class="nav_container">
         <nav class="nav container">
          <div class="nav_logo">
              <h2 class="nav_title">MYePlace</h2>
          </div>
         
             
              <form class="d-flex" role="search" id="formBuscarPublicacion" action="Busquedasencilla" method="post">
        
        <label for="txtBusqueda">Busque por Categoria, Descripcion o Titulo:</label><br>
        <input class="inputTitulo" type="text" id="txtBusqueda" name="txtBusqueda"><br>
        <br>
        <input class="btnBuscar" type="submit" id="buscarPublicacion" name="buscarPublicacion" value="Buscar">
       
        </form>


            <ul class="nav_link nav_link--menu">

                <li class="nav_items">
                   <a href="#" class="nav_links">Inicio</a>
                </li>

                <li class="nav_items">
                    <a href="InicioRegistro.jsp" class="nav_links"></a>
                </li>

                <li class="nav_items">
                    <a href="Login.jsp" aria-current="page" class="nav_links">Cerrar sesion</a>
                </li>

                <li class="nav_items">
                    <a href="Perfil.jsp" class="nav_links">Perfil de Usuario</a>
                </li>

                <img src="images/close.svg" alt="" class="nav_close">
            </ul>

           <div class="nav_menu">
                <img src="images/menu.svg" class="nav_img">
           </div>

        </nav>
    </div>

    <section class="hero_container container">
      <h1 class="hero_title">Comienza con 1 pregunta... Es gratis</h1>
      <p class="hero_paragraph">Foro semioficial de DC comics</p>
      <a href="crearpub.jsp" aria-current="page" class="cta">Subir publicacion</a>
   </section>

</header>

 <main>
    <section class="container about">
        <h2 class="subtitle">Como usar el foro?</h2>

<div class="about_main">
    <article class="about_icons">
        <img src="images/iniciosesion.svg" alt="" class="about_icon">
        <h3 class="about_title">Entra.</h3>
        <p class="about_paragrah">Inicia Sesion o Registrate si eres nuevo, esta en la pestaña de arriab sobre el incio de sesion</p>
    </article> 

    <article class="about_icons">
      <img src="images/pregunta.svg" alt="" class="about_icon">
      <h3 class="about_title">Pregunta.</h3>
      <p class="about_paragrah">En la seccion de subir pregunta, podras ingresar las dudas que tengas, asi como ver las que los demas han publicado y tambien ver sus respuetas</p>
    </article> 

    <article class="about_icons">
        <img src="images/respuesta.svg" alt="" class="about_icon">
        <h3 class="about_title">Espera.</h3>
        <p class="about_paragrah">Una ves hayas mandado tu pregunta solo es cuestion de esperar que los mas de 2Mil usuarios en la aplicacion puedan ayudarte</p>
        </article> 

</div>

    </section>

    <section class="knowledge">
        <div class="knowledege_container container">
            <div class="knowledge_texts">
               <h2 class="subtitle">Ultimos 10 Publicaciones</h2>
               <p class="knowledge_paragraph">Aqui se podran ver las ultimas publicaciones que los demas fans que han puesto
                puedes ver las respuestas dando click a las preguntas</p>
                <a href="#" class="cta">Ver todas la preguntas</a>
            </div>

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

        </div>

       
 
    </section>
</main>

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
            <i class="fas fa-gem me-3"></i>dclovers
          </h6>
          <p>
           Pagina dedicada solo y exclusivamente para fans, cualquier otro contenido 
           sobre otros franquisas sera motivo de eliminacion de dicha cuenta.
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
            <a href="#!" class="text-reset">comics</a>
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
          <p><i class="fas fa-home me-3"></i> Monterrey NL, la plaza de la tecnologia</p>
          <p>
            <i class="fas fa-envelope me-3"></i>
            hola_soporte.com
          </p>
          <p><i class="fas fa-phone me-3"></i> + 8281463973</p>
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
    <a class="text-reset fw-bold" href="Login.jsp">myePlace.com</a>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>