<!-- Comment -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle text-muted text-muted  "  id="myBtnAlert" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell"></i>
      @if($cantNotif>0)
        <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
          @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn" id="myAlert">
        <ul>
            <li>
                <div class="drop-title">Notificaciones</div>
            </li>
            <li>

            @if($cantNotif>0)
                <div class="message-center">

                    <!-- Message -->
                    @for($i=0;$i<$alertas->count(); $i++)
                        @if(Auth::user()->rol==='coordinador')
                            <a href="{{route('solicitud.revisar',$alertas[$i]->solicitud)}}">
                                <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div>
                                <div class="mail-contnet">
                                    <h6>{{$alertas[$i]->titulo}}</h6> <span class="mail-desc">{{$alertas[$i]->descripcion}}</span> <span class="time">{{$alertas[$i]->created_at}}</span>
                                </div>
                            </a>
                        @else
                            @if(Auth::user()->rol==='becario'||Auth::user()->rol==='mentor')
                            <a href="
                               @if(is_null($alertas[$i]->solicitud))
                                    @if(Auth::user()->rol==='becario')
                                        {{route('ver.mentorAsignado')}}
                                    @else
                                    {{route('listar.becariosAsignados')}}
                                    @endif
                                    @else
                                      {{route('solicitud.show',$alertas[$i]->solicitud)}}
                                    @endif
                                    "
                               >
                                <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div>
                                <div class="mail-contnet">
                                    <h6>{{$alertas[$i]->titulo}}</h6> <span class="mail-desc">{{$alertas[$i]->descripcion}}</span> <span class="time">{{$alertas[$i]->created_at}}</span>
                                </div>
                            </a>
                                @else
                                    @if(Auth::user()->rol==='directivo')
                                        @if($alertas[$i]->titulo==='Nomina(s) pendiente(s) por procesar')

                                          <a href="{{route('nomina.procesar')}}">
                                                <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h6>{{$alertas[$i]->titulo}}</h6> <span class="mail-desc">{{$alertas[$i]->descripcion}}</span> <span class="time">{{$alertas[$i]->updated_at}}</span>
                                                </div>
                                            </a>
                                        @else
                                            @if(Auth::user()->esEntrevistador())
                                               fdfdfd
                                            @else
                                                @if($alertas[$i]->titulo==='Desincorporacion(es) pendiente(s) por procesar')

                                                    <a href="{{route('desincorporaciones.listar')}}">
                                                        <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div>
                                                        <div class="mail-contnet">
                                                            <h6>{{$alertas[$i]->titulo}}</h6> <span class="mail-desc">{{$alertas[$i]->descripcion}}</span> <span class="time">{{$alertas[$i]->created_at}}</span>
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="{{route('solicitud.revisar',$alertas[$i]->solicitud)}}">
                                                        <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div>
                                                        <div class="mail-contnet">
                                                            <h6>{{$alertas[$i]->titulo}}</h6> <span class="mail-desc">{{$alertas[$i]->descripcion}}</span> <span class="time">{{$alertas[$i]->created_at}}</span>
                                                        </div>
                                                    </a>
                                                @endif
                                            @endif
                                            @endif
                                        @else

                                        <a href="javascript:void(0)">
                                            <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div>
                                            <div class="mail-contnet">
                                                <h6>{{$alertas[$i]->titulo}}</h6> <span class="mail-desc">{{$alertas[$i]->descripcion}}</span> <span class="time">{{$alertas[$i]->created_at}}</span>
                                            </div>
                                        </a>

                                        @endif
                                @endif
                            @endif
                    @endfor


            </div>
            @else
                <p class="text-center">No Tiene Notificaciones Nuevas</p>
            @endif
        </li>
<li>
<a class="nav-link text-center" href="{{route('notificaciones.showAll')}}"> <strong>Ver todas las notificaciones</strong> <i class="fa fa-angle-right"></i> </a>
</li>
</ul>
</div>
</li>
<!--Mensajes-->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle text-muted  " href="#" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-envelope"></i>
        <div class="notify"> <span class="heartbit"></span> <span class="point"></span> <p class="num-msj">4</p></div>

      <!-- <span class="num-msj label label-danger ">4</span> -->
    </a>
    <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn" aria-labelledby="2">
        <ul>
            <li>
                <div class="drop-title">Usted tiene mensajes</div>
            </li>
            <li>
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><div class="message-center" style="overflow: hidden; width: auto; height: 250px;">
                    <!-- Message -->
                    <a href="#">
                        <div class="user-img"> <i class="fa fa-envelope"></i><span class="profile-status busy pull-right"></span> </div>
                        <div class="mail-contnet">
                            <b><h5><b>John Doe</b></h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span></b>
                        </div>
                    </a>
                    <a href="#">
                        <div class="user-img"> <i class="fa fa-envelope-open"></i><span class="profile-status online pull-right"></span> </div>
                        <div class="mail-contnet">
                            <h5>John Doe</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>
                        </div>
                    </a>

                </div><div class="slimScrollBar" style="background: rgb(220, 220, 220); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
            </li>
            <li>
                <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
            </li>
        </ul>
    </div>
</li>