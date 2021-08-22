 
<nav class="fast-food-navbar navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">



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
            
            <div >
             </div>
          </ul>
         

        </li>
        @else
        <li class="nav-item"> <a class="nav-link" href="{{ is_array($sublink['link']) ? '#' : $sublink['link'] }}">
            {{$sublink["label"]}}</a> </li>
        @endif


        @endforeach
        <!-- end iteracion -->
       
      </ul>

      <a class="navbar-brand" href="#"> {{ session("USUARIO") }} </a>
      

    </div>
  </div>
</nav>