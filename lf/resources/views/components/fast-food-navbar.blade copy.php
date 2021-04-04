<style>
    .dropdown-menu {

        background-color: #060606;
    }

    .dropdown-item {
        /* color: #212529; */
        color: wheat;
    }

    /* En línea #20 | http://192.168.0.14/fastfood/deposito/ficha-produccion */

    @media (min-width: 992px) {
        .dropdown-menu li {
            border-bottom: 1px solid yellow;
        }
    }

    @media (min-width: 992px) {
        .dropdown-menu .dropdown-toggle:after {
            border-top: .3em solid transparent;
            border-right: 0;
            border-bottom: .3em solid transparent;
            border-left: .3em solid;
        }

        .dropdown-menu .dropdown-menu {
            margin-left: 0;
            margin-right: 0;
        }

        .dropdown-menu li {
            position: relative;
        }

        .nav-item .submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: -7px;
        }

        .nav-item .submenu-left {
            right: 100%;
            left: auto;
        }

        .dropdown-menu>li:hover {
            background-color: #242424
        }

        .dropdown-menu>li:hover>.submenu {
            display: block;
        }
    }
</style>



 
<nav style="background-color: #060606;padding: 0px;" class="navbar navbar-expand-lg">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav">
        <span class="navbar-toggler-icon" style="display: flex; flex-direction: row;align-items: center;">
            <img style="width: 25px; height: auto;" src="{{url('assets/icons/burger_icon.png')}}" />
            Menú
        </span>
    </button>
    <div class="collapse navbar-collapse" id="main_nav">



        <ul class="navbar-nav">

            <li class="nav-item" style="background-color: red;">
                <a class="nav-link" href="#" style="font-size: 16px;text-shadow: 2px 2px 2px black;color: white;">
                    {{ session("USUARIO") }}</a>
            </li>


            @foreach( $links as $sl)

            @if( is_array( $sl["link"]) )
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> {{$sl['label']}}</a>
                <ul class="dropdown-menu">
                    @foreach( $sl["link"] as $sublink)


                    @if( is_array( $sublink['link']) )

                    <li class="nav-item dropdown dropdown-item">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> {{$sl['label']}}</a>
                        <ul class="dropdown-menu">
                        @foreach( $sublink['link'] as $subli)
                        <li><a class="dropdown-item" href="{{$subli['link']}}"> {{$subli['label']}} </a></li>
                        @endforeach
                        </ul>
                    </li>
                    @else
                    <li><a class="dropdown-item" href="{{$sublink['link']}}"> {{$sublink['label']}} </a></li>
                    @endif



                    @endforeach
                </ul>
            </li>
            @else
            <li class="nav-item"> <a class="nav-link" href="{{ is_array($sl['link']) ? '#' : $sl['link'] }}">
                    {{$sl["label"]}}</a> </li>
            @endif

            </li>
            @endforeach
        </ul>

    </div> <!-- navbar-collapse.// -->
</nav>

<script defer>
    // Prevent closing from click inside dropdown
    $(document).on('click', '.dropdown-menu', function(e) {
        e.stopPropagation();
    });
</script>