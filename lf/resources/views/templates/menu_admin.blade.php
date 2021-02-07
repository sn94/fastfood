<nav class="navbar navbar-expand-lg    navbar-dark ftco_navbar bg-dark ftco-navbar-light">
  <a class="navbar-brand"   href="{{url('/')}}" >Pizzeria</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    
    


    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          EL NEGOCIO
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{url('sucursal')}}">SUCURSALES</a>

          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{url('cargo')}}">CARGOS</a>
        </div>
      </li>



      <li class="nav-item">
        <a class="nav-link"  href="{{url('proveedores')}}" >PROVEEDORES</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{url('clientes')}}">CLIENTES</a>
      </li>


      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          DEPÓSITO
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{url('familia')}}">FAMILIA DE PRODUCTOS</a>
          <a class="dropdown-item" href="{{url('productos')}}">PRODUCTOS</a>
          <a class="dropdown-item" href="{{url('materia-prima')}}">MATERIA PRIMA</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{url('deposito')}}">DEPÓSITO</a>
        </div>
      </li>

 

<!--
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          PROVEEDORES
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
-->

       
      
      
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>