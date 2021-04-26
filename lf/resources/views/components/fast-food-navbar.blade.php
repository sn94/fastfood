<style>
  .navbar {
    background-color: black !important;
    background-image: url(/fastfood/assets/images/logo.png);
    background-size: contain;
    background-repeat: no-repeat;
    background-position-x: right;
  }

  .navbar-light .navbar-nav .nav-link {
    color: #f9bf78 !important;
  }

  .nav-link:hover {
    background-color: red !important;
  }

  @media (min-width: 992px) {
    .navbar-expand-lg .navbar-nav .dropdown-menu {
      background-color: black !important;
    }
  }

  .dropdown-item {
    color: #db8851 !important;
    border-bottom: 1px solid #db8851 !important;
  }

  .dropdown-item:hover {
    background-color: red !important;
  }

  .navbar-toggler {
    background-color: #fdd6c4 !important;
  }

  .navbar-brand {
    color: white !important;
  }
</style>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"> {{ session("USUARIO") }} </a>


    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">

      <img style="width: 32px; height: 32px;" src="{{url('assets/icons/burger_icon.png')}}" alt="">
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">




        <!--iterar -->


        @foreach( $links as $sublink)

        @if( is_array( $sublink["link"]) )
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{$sublink['label']}}
          </a>

          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            @foreach( $sublink['link'] as $subsublink )
            <li><a class="dropdown-item" href="{{$subsublink['link']}}"> {{$subsublink['label']}} </a></li>
            @endforeach
          </ul>

        </li>
        @else
        <li class="nav-item"> <a class="nav-link" href="{{ is_array($sublink['link']) ? '#' : $sublink['link'] }}">
            {{$sublink["label"]}}</a> </li>
        @endif


        @endforeach
        <!-- end iteracion -->


      </ul>
    </div>
  </div>
</nav>