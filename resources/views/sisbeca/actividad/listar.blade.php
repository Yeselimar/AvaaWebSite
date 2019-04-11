@extends('sisbeca.layouts.main')
@section('title',"Todos los talleres y chat clubs")
@section('personalcss')

<link href="{{asset('plugins/full-calendar/fullcalendar-bootsnipp.css')}}" rel="stylesheet"><!--Boostrap Full Calendar de Bootsnipp -->
<link href="{{asset('plugins/full-calendar/fullcalendar-custom.css')}}" rel="stylesheet"><!--Boostrap Full Calendar de Bootsnipp -->

@endsection
@section('content')
  <div class="col-lg-12 text-right">
    @if(Auth::user()->admin() )
      <a href="{{route('actividad.crear')}}" class="btn btn-sm sisbeca-btn-primary">Crear Taller/ChatClub</a>
    @endif
  </div>
  <div class="col-lg-12">
    <div id='calendar' style="border:1px solid #003865">
    </div>

    <div style='clear:both'>
    </div>
  </div>
@endsection

@section('personaljs')

<script src="{{asset('js/jquery-ui.js')}}"></script> <!--Borrar--> 

<script src="{{asset('/plugins/full-calendar/fullcalendar-bootsnipp.js')}}"></script><!--Boostrap Full Calendar de Bootsnipp -->


<script>

  $(document).ready(function() {
    var date = new Date("2018-12-31");
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
    /*  className colors
    
    className: default(transparent), important(red), chill(pink), success(green), info(blue)
    
    */    
    
      
    /* initialize the external events
    -----------------------------------------------------------------*/
  
    $('#external-events div.external-event').each(function() {
    
      // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
      // it doesn't need to have a start or end
      var eventObject = {
        title: $.trim($(this).text()) // use the element's text as the event title
      };
      
      // store the Event Object in the DOM element so we can get to it later
      $(this).data('eventObject', eventObject);
      
      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });
      
    });
    var eventos = [];
    
    @foreach($actividades as $actividad)
      @if((Auth::user()->esBecario() and ($actividad->estaDisponible() or $actividad->estaSuspendido())) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
      var dia = new Date("{{$actividad->fecha}}");
      var cadena1 = "2050-01-01 "+"{{$actividad->hora_inicio}}";
      var cadena2 = "2050-01-01 "+"{{$actividad->hora_fin}}";
      var horainicio = new Date (cadena1);
      var horafin = new Date (cadena2);
      var modalidad = '{{$actividad->modalidad}}';
      var estatus = '{{$actividad->status}}';
      var info="";
      if(modalidad=='virtual')
      {
        icono =  "<i class='fa fa-laptop'></i>";
      }
      else
      {
        icono =  "<i class='fa fa-male'></i>"
      }
      if(estatus=='suspendido')
      {
        info = "(SUSPENDIDO)";
      }
      else
      {
        if(estatus=='oculto')
        {
          info = "(OCULTO)";
        }
        else
        {
          if(estatus=='disponible')
          {
            info = "(DISPONIBLE)";
          }
          else
          {
            info = "(CERRADO)";
          }
        }
      }
      eventos.push({
        title: '{{$actividad->tipo}}'.toUpperCase()+': '+'{{$actividad->nombre}}'+' '+icono+' '+info,
        start: new Date(dia.getFullYear(), dia.getMonth(), dia.getDate(),horainicio.getHours(),horainicio.getMinutes()),
        end: new Date(dia.getFullYear(), dia.getMonth(), dia.getDate(),horafin.getHours(),horafin.getMinutes()),
        allDay: false,
        url: '{{route('actividad.detalles',$actividad->id)}}',
      });
      @endif
    @endforeach
      
    /* initialize the calendar
    -----------------------------------------------------------------*/
    
    var calendar =  $('#calendar').fullCalendar({
      header: {
        left: 'title',
        center: 'agendaDay,agendaWeek,month',
        right: 'prev,next today'
      },
      editable: false,
      firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
      selectable: false,
      defaultView: 'month',
      
      axisFormat: 'h:mm',
      columnFormat: {
                month: 'ddd',    // Mon
                week: 'ddd d', // Mon 7
                day: 'dddd M/d',  // Monday 9/7
                agendaDay: 'dddd d'
            },
            titleFormat: {
                month: 'MMMM yyyy', // September 2009
                week: "MMMM yyyy", // September 2009
                day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
            },
      allDaySlot: false,
      selectHelper: true,
      select: function(start, end, allDay) {
        var title /*= prompt('Event Title:')*/;
        if (title) {
          calendar.fullCalendar('renderEvent',
            {
              title: title,
              start: start,
              end: end,
              allDay: allDay
            },
            true // make the event "stick"
          );
        }
        calendar.fullCalendar('unselect');
      },
      droppable: false, // this allows things to be dropped onto the calendar !!!
      drop: function(date, allDay) { // this function is called when something is dropped
      
        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject');
        
        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);
        
        // assign it the date that was reported
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        
        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
        
        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove();
        }
        
      },
      
      events: eventos,      
    });
    
    
  });

</script>
@endsection