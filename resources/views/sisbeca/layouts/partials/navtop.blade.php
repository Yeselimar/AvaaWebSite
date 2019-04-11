<nav class="navbar top-navbar navbar-expand-md navbar-light">

    <!-- Logo -->

    <div class="navbar-header">

        <a class="navbar-brand" href="{{asset(route('seb'))}}">
            <b><img src="{{asset('public_sisbeca/images/logo.png')}}" alt="homepage" class="dark-logo" /></b>
            <span><img src="{{asset('public_sisbeca/images/logo-text.png')}}" alt="homepage" class="dark-logo" /></span>
        </a>

    </div>

    @guest

    <div class="navbar-collapse">
        <ul class="navbar-nav mr-auto mt-md-0">
            <ul class="nav navbar-nav navbar-left">
                <li><span style="color: white;font-weight: bold"> {{ 'AVAA - Sistema de Administración' }}</span></li>

            </ul>
        </ul>
    </div>
    @else
    <div class="navbar-collapse">

        <ul class="navbar-nav mr-auto mt-md-0">

            <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
            <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>

        </ul>
        <!-- User profile and search -->
        <ul class="navbar-nav my-lg-0">

            <!-- Notificaciones-->
            @include('sisbeca.layouts.partials.partialsNotificaciones')
            <!-- Fin de Notificaciones -->

            <!-- Profile -->
            <li class="nav-item dropdown">
                @if($image_perfil->count()>0)
                    <a class="nav-link dropdown-toggle text-muted"  id="myBtn" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{asset($image_perfil[0]->url)}}" alt="user" class="profile-pic" />
                    </a>
                @else

                    @if(Auth::user()->sexo==='femenino')
                        <a class="nav-link dropdown-toggle text-muted" id="myBtn" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{asset('images/perfil/femenino.png')}}" alt="user" class="profile-pic" />
                        </a>
                    @else
                        <a class="nav-link dropdown-toggle text-muted" id="myBtn"  href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{asset('images/perfil/masculino.png')}}" alt="user" class="profile-pic" />
                        </a>
                    @endif
                @endif

                <div id="mydrop" class="dropdown-menu dropdown-menu-right animated zoomIn">
                    <ul class="dropdown-user">
                        <li>
                            <a>
                                <i class="fa fa-check"></i> 
                                <strong>{{Auth::user()->nombreyapellido()}}</strong>
                            </a>
                        </li>

                        <li>
                            <a>
                                <strong>
                                    <i class="fa fa-star-o"></i> {{Auth::user()->getRol()}}
                                </strong>
                            </a>
                        </li>

                        <li>
                            @if(Auth::user()->rol==='mentor')
                                <a href="{{route('ver.miPerfilMentor')}}"><i class="ti-user"></i> Perfil</a>
                            @else
                                @if(Auth::user()->rol=='becario' || Auth::user()->rol=='postulante_becario')
                                    <a href="{{ route('postulanteObecario.perfil',Auth::user()->id) }}"><i class="ti-user"></i> Perfil</a>
                                @else
                                    @if(Auth::user()->rol==='postulante_mentor')
                                        <a href="{{route('ver.miPerfilPostulanteMentor')}}"><i class="ti-user"></i> Perfil </a>
                                    @endif
                                @endif
                            @endif
                        </li>
                        @if(Auth::user()->esBecario())
                            <li>
                                <a href="{{route('becarios.editar.datos',Auth::user()->id)}}">
                                    <i class="fa fa-cog"></i> Editar Perfil
                                </a>
                            </li>
                        @endif
                        
                        {{--<li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li> --}}
                        
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out"></i> 
                                Cerrar Sesión
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

    </div>
    @endguest
 </nav>

