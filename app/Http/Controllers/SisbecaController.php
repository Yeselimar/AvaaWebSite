<?php

namespace avaa\Http\Controllers;

use avaa\Alerta;
use avaa\Costo;
use avaa\Events\SolicitudesAlerts;
use avaa\Solicitud;
use avaa\User;
use Illuminate\Http\Request;
use Auth;
use DateTime;
use Timestamp;
use avaa\Mentor;
use avaa\Becario;
use avaa\Imagen;
use avaa\Documento;
use avaa\Actividad;
use avaa\Aval;
use avaa\FactLibro;

class SisbecaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario =  Auth::user();
        $becario = Becario::where('user_id','=',$usuario->id)->first();
        if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
        {
            $actividades = Actividad::ordenadaPorFecha('asc')->where('fecha','>=',date('Y-m-d 00:00:00'))->take(10)->get();
        }
        else
        {
            $actividades = Actividad::menosConEstatus('oculto')->ordenadaPorFecha('asc')->where('fecha','>=',date('Y-m-d 00:00:00'))->take(10)->get();
        }
        //Conteo de CVA, Notas Académicas, Voluntariados en estatus pendiente.
        $periodos_pendiente = Aval::dePeriodos()->conEstatus('pendiente')->count();
        $cva_pendiente = Aval::deCVA()->conEstatus('pendiente')->count();
        $voluntariados_pendiente = Aval::deVoluntariados()->conEstatus('pendiente')->count();
        $justificativos_pendiente  = Aval::justificativos()->conEstatus('pendiente')->count();
        $solicitudes_pendiente  = Solicitud::conEstatus('enviada')->count();
        $facturas_pendiente  = FactLibro::conEstatus('cargada')->count();
        return view('sisbeca.index')->with(compact('becario','usuario','actividades','periodos_pendiente','cva_pendiente','voluntariados_pendiente','justificativos_pendiente','solicitudes_pendiente','facturas_pendiente'));
    }

    public function allNotificaciones()
    {
        if(Auth::user()->rol==='coordinador' or Auth::user()->rol==='directivo')
        {
           /*************  $becariosAsignados= Becario::query()->where('acepto_terminos', '=', true)->whereIn('status', ['probatorio1', 'probatorio2', 'activo','inactivo'])->get();
            $listaBecariosA=$becariosAsignados->pluck('user_id')->toArray();

            $collection = collect();
            foreach ($becariosAsignados as $beca)
            {

                $collection->push($beca->mentor);
            }
            $listMentoresA=$collection->pluck('user_id')->toArray();

            $mentoresAsignados= Mentor::query()->whereIn('user_id',$listMentoresA)->get();

            $listaMentoresA= $mentoresAsignados->pluck('user_id')->toArray();
            */

            $alertas = Alerta::query()->where('status','=','enviada')->get();
            Alerta::where('status','=','enviada')->update(array('leido' => true));
        }
        else
        {
            if(Auth::user()->esEntrevistador())
            {
                $alertas = Alerta::where('user_id', '=', Auth::user()->id)->get();
            }
            else
            {
                if(Auth::user()->esBecario())
                {

                    $alertas = Alerta::where('user_id', '=', Auth::user()->id)->get();
                }
                else
                {
                    $alertas = Alerta::where('user_id', '=', Auth::user()->id)->where('status', '=', 'generada')->get();
                }
               

                Alerta::where('status', '=', 'generada')->where('user_id', '=', Auth::user()->id)->update(array('leido' => true));
            }


        }
        return  view('sisbeca.notificaciones.listarNotificaciones')->with('notificaciones',$alertas);
    }
    public function perfil($id)
    {
        $becario = Becario::find($id);
        if((Auth::user()->esPostulanteBecario() and Auth::user()->id==$id) or 
            (Auth::user()->esBecario() and Auth::user()->id==$id) or 
            (Auth::user()->esPostulanteMentor() and Auth::user()->id==$id) or
            (Auth::user()->esMentor() and Auth::user()->id==$becario->mentor_id) or
            Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
        {
            $user= User::find($id);
            $becario = Becario::find($id);
            $usuario = Auth::user();
            $img_perfil = Imagen::where('user_id','=',$id)->where('titulo','=','img_perfil')->first();
            $fotografia = Imagen::where('user_id','=',$id)->where('titulo','=','fotografia')->first();
            $cedula = Imagen::where('user_id','=',$id)->where('titulo','=','cedula')->first();
            $constancia_cnu = Documento::where('user_id','=',$id)->where('titulo','=','constancia_cnu')->first();
            $calificaciones_bachillerato = Documento::where('user_id','=',$id)->where('titulo','=','calificaciones_bachillerato')->first();
            $constancia_aceptacion = Documento::where('user_id','=',$id)->where('titulo','=','constancia_aceptacion')->first();
            $constancia_estudios = Documento::where('user_id','=',$id)->where('titulo','=','constancia_estudios')->first();
            $calificaciones_universidad = Documento::where('user_id','=',$id)->where('titulo','=','calificaciones_universidad')->first();
            $constancia_trabajo = Documento::where('user_id','=',$id)->where('titulo','=','constancia_trabajo')->first();
            $declaracion_impuestos = Documento::where('user_id','=',$id)->where('titulo','=','declaracion_impuestos')->first();
            $recibo_pago = Documento::where('user_id','=',$id)->where('titulo','=','recibo_pago')->first();
            $referencia_profesor1 = Documento::where('user_id','=',$id)->where('titulo','=','referencia_profesor1')->first();
            $referencia_profesor2 = Documento::where('user_id','=',$id)->where('titulo','=','referencia_profesor2')->first();
            $ensayo = Documento::where('user_id','=',$id)->where('titulo','=','ensayo')->first();
            return view('sisbeca.becarios.perfil')->with('becario',$becario)->with('usuario',$usuario)->with('fotografia',$fotografia)->with('cedula',$cedula)->with('constancia_cnu',$constancia_cnu)->with('calificaciones_bachillerato',$calificaciones_bachillerato)->with('constancia_aceptacion',$constancia_aceptacion)->with('constancia_estudios',$constancia_estudios)->with('calificaciones_universidad',$calificaciones_universidad)->with('constancia_trabajo',$constancia_trabajo)->with('declaracion_impuestos',$declaracion_impuestos)->with('recibo_pago',$recibo_pago)->with('referencia_profesor1',$referencia_profesor1)->with('referencia_profesor2',$referencia_profesor2)->with('ensayo',$ensayo)->with('img_perfil',$img_perfil);
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }
    public function verMiPerfilMentor()
    {
        $mentor= Mentor::find(Auth::user()->id);
        if($mentor){
            $img_perfil=Imagen::query()->where('user_id','=',$mentor->user_id)->where('titulo','=','img_perfil')->get();
            $documento = Documento::query()->where('user_id', '=', $mentor->user_id)->where('titulo', '=', 'hoja_vida')->first();
            return view('sisbeca.mentores.perfilMentor')->with('mentor',$mentor)->with('img_perfil',$img_perfil)->with('documento',$documento);
        } else
        {
            return view('sisbeca.error.404');
        }
    }
    public function statusPostulanteMentor()
    {
        return view('sisbeca.postulaciones.statusMentoria');
    }

    public function postulacionMentorGuardar(Request $request)
    {
        $user =  Auth::user();
        $user->rol='postulante_mentor';
        $user->save();
        $mentor = new Mentor();
        $mentor->user_id=$user->id;
        $mentor->status= 'postulante';
        $mentor->profesion = $request->profesion;
        $mentor->empresa = $request->empresa;
        $mentor->cargo_actual = $request->cargo_actual;
        $mentor->areas_de_interes = $request->areas_interes;
        $mentor->fecha_ingreso_empresa = DateTime::createFromFormat('d/m/Y H:i:s', $request->fecha_ingreso.' 00:00:00');
        $mentor->save();
        $file = $request->file('url_pdf');
        $name = 'HojaDeVida_' . time() . '.' . $file->getClientOriginalExtension();
        $path = public_path() . '/documentos/mentores/';
        $file->move($path, $name);
        $documento = new Documento();
        $documento->user_id = $user->id;
        $documento->url = '/documentos/mentores/'.$name;
        $documento->titulo='hoja_vida';
        $documento->save();
        return view('sisbeca.postulaciones.statusMentoria');
    }
    public function postulacionMentor()
    {
        return view('sisbeca.postulaciones.enviarPostulacionMentor');
    }

}
