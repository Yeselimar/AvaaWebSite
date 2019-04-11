 <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="content-terminos">
                <div class="alert  alert-warning alert-important" role="alert">
                   Debes Aceptar los Términos y Condiciones de ProExcelencia para Acceder al Sistema como Becario.
                </div>
                    <ul>
                        <strong>El Becario de ProExcelencia AVAA se compromete a:</strong>

                        <li class="list_term"> 	Leer el Manual de Becarios que será enviado vía correo electrónico.</li>
                        <li class="list_term"> 	Mantener una conducta intachable y ejemplar como estudiante y ciudadano.</li>
                        <li class="list_term"> 	Mantener o subir el promedio de notas con el que fue aceptado en el programa.</li>
                        <li class="list_term"> 	Actualizar permanentemente su expediente con sus datos de contacto.</li>
                        <li class="list_term"> 	Entregar las calificaciones de cada trimestre, semestre o año que culmine.</li>
                        <li class="list_term"> 	Presentar sus horarios de clase ante el Comité ProExcelencia al iniciar un trimestre, semestre o año (según el régimen que le corresponda). En ese horario deben incluir las horas en las que prestarán servicio de voluntariado a la Asociación, de modo que sus actividades puedan ser planificadas con anticipación.</li>
                        <li class="list_term"> 	Presentar mensualmente su último comprobante de inscripción del curso de inglés. Y en dado caso, solicitar por escrito al Comité de Educación la autorización para cambiarse de horario en el curso de inglés.</li>
                        <li class="list_term"> 	Asistir a SEIS (6) actividades de formación organizados por el Comité de Educación.</li>
                        <li class="list_term"> 	Asistir a DIEZ (10) Club de Práctica de Conversación en Inglés, CHAT CLUBS.</li>
                        <li class="list_term"> 	Cumplir con un mínimo de 96 horas de voluntariado al año equivalentes a 8 horas de trabajo al mes.</li>
                        <li class="list_term"> 	Cumplir con al menos el 80% de las actividades referentes a talleres, chat clubs y horas de voluntariado.</li>
                        <li class="list_term"> 	Comunicar al Comité de Educación cuándo va a salir de vacaciones. La suspensión del curso de inglés sin previa autorización del Comité incidirá directamente sobre la continuidad o terminación de su beca. Esta comunicación debe hacerla por escrito y con un mes de anticipación.</li>
                        <li class="list_term"> 	Asistir a las entrevistas pautadas con el Comité, participar como voluntario en las actividades educativas, culturales o deportivas de la Asociación.</li>
                        <li class="list_term"> 	Solicitar por escrito al Comité de Educación la autorización para cambiarse de carrera y/o universidad y acatar las normas que el Comité imponga para estos casos.</li>
                        <li class="list_term"> 	Promover la labor de AVAA en todos los ambientes académicos posibles. El becario es el mejor embajador que tiene la Asociación para dar a conocer los programas que lleva acabo.</li>

                    </ul>
                </div>

                <form action="{{route('terminosCondiciones.aprobar')}}" accept-charset="UTF-8"  method="POST" >
                    {{csrf_field()}}
                    <div class="d-flex justify-content-center">
                <button type="submit" class="sisbeca-btn-primary m-r-5">
                    Aceptar Términos y Condiciones
                </button>
               <!--  <button type="submit" class="sisbeca-btn-default ">
                       Rechazar
                </button> -->
                    </div>
                </form>

            </div>
        </div>
    </div>

