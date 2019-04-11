<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Http\Requests\BecarioRequest;
use avaa\Http\Controllers\Controller;
use Auth;
use Validator;
use DateTime;
use Response;
use Asset;
use File;
use Barryvdh\DomPDF\Facade as PDF;
use avaa\Becario;
use avaa\User;
use avaa\Imagen;
use avaa\Documento;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PostulanteBecarioController extends Controller
{
    public function volverapostularse(){
//voler.a.postularse
$becario = Becario::find(Auth::user()->id);
$becario->status='prepostulante';
$becario->save();
return redirect()->route('seb');

}
    public function statusPostulanteBecario()
    {
        $postulante = Becario::find(Auth::User()->id);
        $entrevistadores = User::entrevistadores()->get();
        return view('sisbeca.postulaciones.statusPostulanteBecario')->with('postulante',$postulante)->with('entrevistadores',$entrevistadores);
    }
    public function terminosCondicionesAprobar(Request $request)
    {
        if (Auth::user()->becario->acepto_termino == 1) {
            return back();
        }
        $becario = User::find(Auth::user()->id)->becario;
        Auth::user()->rol='becario';
        Auth::user()->save();
        $becario->acepto_terminos = 1;
        //$becario->save();

        if ($becario->save()) {

            flash('Ha Aceptado Terminos y Condiciones Exitosamente', 'success')->important();
        } else {
            flash('Disculpe, ha ocurrido un error inesperado.', 'success')->important();
        }
        return redirect()->route('seb');
    }
    public function enviarPostulacionGuardar($progreso)
    {
        $becario = Becario::find(Auth::user()->id);
        $usuario = User::find(Auth::user()->id);

        if(($becario->datos_personales==true)&&($becario->estudios_secundarios==true)&&($becario->estudios_universitarios==true)&&($becario->informacion_adicional==true)&&($becario->documentos==true))
        {
            $becario->status='postulante';
            $becario->save();

            //Enviar correo a la persona notificando
            $mail = new PHPMailer();
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "TLS";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->Username = "delgadorafael2011@gmail.com";
            $mail->Password = "scxxuchujshrgpao";
            $mail->setFrom("no-responder@avaa.org", "Sisbeca");
            $mail->Subject = "Notificación";
            $body = view("emails.postulantebecario.postulacion-enviada")->with(compact("solicitud","becario"));
            $mail->MsgHTML($body);
            $mail->addAddress($becario->user->email);
            //$mail->send();

            return redirect()->route('seb');

        }
        else
        {
            flash('Debe llenar todos las secciones requeridas para la postulación','danger');
        }
        return view('sisbeca.postulantebecario.enviarPostulacion')->with('becario',$becario)->with('usuario',$usuario)->with('progreso',$progreso);
    }


    public function enviarPostulacion()
    {
        $becario = Becario::find(Auth::user()->id);
        $usuario = User::find(Auth::user()->id);
        $progreso=0;
        //dd($becario);
        if($becario->datos_personales===1)
        {
           $progreso=$progreso+20;
        }
        if($becario->estudios_secundarios===1)
        {
           $progreso=$progreso+20;
        }
        if ($becario->estudios_universitarios===1)
        {
            $progreso=$progreso+20;
        }
        if($becario->informacion_adicional===1)
        {
            $progreso=$progreso+20;
        }
        if($becario->documentos===1)
        {
            $progreso=$progreso+20;
        }
        return view('sisbeca.postulantebecario.enviarPostulacion')->with('becario',$becario)->with('usuario',$usuario)->with('progreso',$progreso);
    }

    public function datospersonales()
    {
    	$becario = Becario::find(Auth::user()->id);
    	$usuario = User::find(Auth::user()->id);
    	return view('sisbeca.postulantebecario.datospersonales')->with('becario',$becario)->with('usuario',$usuario);
    }

    public function datospersonalesguardar(Request $request)
    {
    	$validation = Validator::make($request->all(), BecarioRequest::rulesDatosPersonales());
		if ( $validation->fails() )
		{
			flash("Por favor, verifique el formulario.",'danger');
			return back()->withErrors($validation,'becario')->withInput();
		}
    	$becario = Becario::find(Auth::user()->id);
    	$becario->direccion_permanente = $request->get('direccion_permanente');
    	$becario->direccion_temporal = $request->get('direccion_temporal');
    	$becario->celular = $request->get('celular');
    	$becario->telefono_habitacion = $request->get('telefono_habitacion');
    	$becario->telefono_pariente = $request->get('telefono_pariente');
    	$becario->lugar_nacimiento = $request->get('lugar_nacimiento');
    	$becario->ingreso_familiar = $request->get('ingreso_familiar');
    	$becario->trabaja = ($request->get('trabaja')=='1') ? 1 : 0;
    	$becario->lugar_trabajo = $request->get('lugar_trabajo');
    	$becario->cargo_trabajo = $request->get('cargo_trabajo');
    	$becario->horas_trabajo = $request->get('horas_trabajo');
    	$becario->contribuye_ingreso_familiar = ($request->get('contribuye_ingreso_familiar')=='1') ? 1 : 0;
    	$becario->porcentaje_contribuye_ingreso = $request->get('porcentaje_contribuye_ingreso');
    	$becario->vives_con = ($request->get('vives_con')=='otros') ? 'otros' : 'padres';
    	$becario->vives_otros = $request->get('vives_otros');
    	$becario->tipo_vivienda = $request->get('tipo_vivienda');
    	$becario->composicion_familiar = $request->get('composicion_familiar');
    	//--
    	$becario->ocupacion_padre = $request->get('ocupacion_padre');
    	$becario->nombre_empresa_padre = $request->get('nombre_empresa_padre');
    	$becario->experiencias_padre = $request->get('experiencias_padre');
    	//--
    	$becario->ocupacion_madre = $request->get('ocupacion_madre');
    	$becario->nombre_empresa_madre = $request->get('nombre_empresa_madre');
    	$becario->experiencias_madre = $request->get('experiencias_madre');
    	$becario->datos_personales = true;
        $becario->save();
        //return $becario;
    	flash("Los datos personales fueron cargados exitosamente.",'success');
    	return redirect()->route('postulantebecario.datospersonales');
    }

    public function estudiossecundarios()
    {
        $becario = Becario::find(Auth::user()->id);
        //return $becario;
        return view('sisbeca.postulantebecario.estudiossecundarios')->with('becario',$becario);
    }

    public function estudiossecundariosguardar(Request $request)
    {
        $validation = Validator::make($request->all(), BecarioRequest::rulesEstudiosSecundarios());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation,'becario')->withInput();
        }
        $becario = Becario::find(Auth::user()->id);
        $becario->nombre_institucion = $request->get('nombre_institucion');
        $becario->direccion_institucion = $request->get('direccion_institucion');
        $becario->director_institucion = $request->get('director_institucion');
        $becario->bachiller_en = $request->get('bachiller_en');
        $becario->promedio_bachillerato = $request->get('promedio_bachillerato');
        $becario->actividades_extracurriculares = $request->get('actividades_extracurriculares');
        $becario->lugar_labor_social = $request->get('lugar_labor_social');
        $becario->direccion_labor_social = $request->get('direccion_labor_social');
        $becario->supervisor_labor_social = $request->get('supervisor_labor_social');
        $becario->aprendio_labor_social = $request->get('aprendio_labor_social');
        $becario->habla_otro_idioma = ($request->get('habla_otro_idioma')=='1') ? 1 : 0;
        $becario->habla_idioma = $request->get('habla_idioma');
        $becario->nivel_idioma = $request->get('nivel_idioma');
        $becario->estudios_secundarios = true;
        $becario->save();

        flash("Los estudios secundarios fueron cargados exitosamente.",'success');
        return redirect()->route('postulantebecario.estudiossecundarios');
    }

    public function estudiosuniversitarios()
    {
        $becario = Becario::find(Auth::user()->id);
        return view('sisbeca.postulantebecario.estudiosuniversitarios')->with('becario',$becario);
    }

    public function estudiosuniversitariosguardar(Request $request)
    {
        $validation = Validator::make($request->all(), BecarioRequest::rulesEstudiosUniversitarios());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation,'becario')->withInput();
        }
        $becario = Becario::find(Auth::user()->id);
        $becario->inicio_universidad = DateTime::createFromFormat('d/m/Y H:i:s', $request->get('inicio_universidad').' 00:00:00');//--
        $becario->nombre_universidad = $request->get('nombre_universidad');
        $becario->carrera_universidad = $request->get('carrera_universidad');
        $becario->costo_matricula = $request->get('costo_matricula');
        $becario->promedio_universidad = $request->get('promedio_universidad');
        $becario->periodo_academico = $request->get('periodo_academico');
        $becario->regimen = $request->get('regimen');
        $becario->estudios_universitarios = true;
        $becario->save();
        flash("Los estudios universitarios fueron cargados exitosamente.",'success');
        return redirect()->route('postulantebecario.estudiosuniversitarios');
    }

    public function informacionadicional()
    {
        $becario = Becario::find(Auth::user()->id);
        return view('sisbeca.postulantebecario.informacionadicional')->with('becario',$becario);
    }

    public function informacionadicionalguardar(Request $request)
    {
        $validation = Validator::make($request->all(), BecarioRequest::rulesInformacionAdicional());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation,'becario')->withInput();
        }
        $becario = Becario::find(Auth::user()->id);
        $becario->medio_proexcelencia = $request->get('medio_proexcelencia');
        $becario->otro_medio_proexcelencia = $request->get('otro_medio_proexcelencia');
        $becario->motivo_beca = $request->get('motivo_beca');
        $becario->link_video = $request->get('link_video');
        $becario->informacion_adicional = true;
        $becario->save();
        flash("La información adicional fue cargada exitosamente.",'success');
        return redirect()->route('postulantebecario.informacionadicional');
    }

    public function documentos()
    {
        $becario = Becario::find(Auth::user()->id);

        $id = Auth::user()->id;
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
        //return Response::json($fotografia);
        return view('sisbeca.postulantebecario.documentos')->with('becario',$becario)->with('fotografia',$fotografia)->with('cedula',$cedula)->with('constancia_cnu',$constancia_cnu)->with('calificaciones_bachillerato',$calificaciones_bachillerato)->with('constancia_aceptacion',$constancia_aceptacion)->with('constancia_estudios',$constancia_estudios)->with('calificaciones_universidad',$calificaciones_universidad)->with('constancia_trabajo',$constancia_trabajo)->with('declaracion_impuestos',$declaracion_impuestos)->with('recibo_pago',$recibo_pago)->with('referencia_profesor1',$referencia_profesor1)->with('referencia_profesor2',$referencia_profesor2)->with('ensayo',$ensayo);

    }

    public function documentosguardar(Request $request)
    {
        $validation = Validator::make($request->all(), BecarioRequest::rulesDocumentos());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation,'becario')->withInput();
        }
        $id = Auth::user()->id;
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
        //-- Fotografia
        if(!isset($fotografia))
        {
            $file= $request->file('fotografia');
            $name = 'fotografia_'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaImagenes();
            $file->move($path, $name);

            $fotografia = new Imagen;
            $fotografia->url= Becario::getCarpetaImagenes().$name;
            $fotografia->titulo = 'fotografia';
            $fotografia->user_id = $id;
            $fotografia->save();
        }
        //-- Cédula
        if(!isset($cedula))
        {
            $file= $request->file('cedula');
            $name = 'cedula_'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaImagenes();
            $file->move($path, $name);

            $cedula = new Imagen;
            $cedula->url= Becario::getCarpetaImagenes().$name;
            $cedula->titulo = 'cedula';
            $cedula->user_id = $id;
            $cedula->save();
        }
        //-- Constancia CNU
        if(!isset($constancia_cnu))
        {
            $file= $request->file('constancia_cnu');
            $name = 'constancia_cnu_'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $constancia_cnu = new Documento;
            $constancia_cnu->url= Becario::getCarpetaDocumentos().$name;
            $constancia_cnu->titulo = 'constancia_cnu';
            $constancia_cnu->user_id = $id;
            $constancia_cnu->save();
        }
        //-- Calificaciones Bachillerato
        if(!isset($calificaciones_bachillerato))
        {
            $file= $request->file('calificaciones_bachillerato');
            $name = 'calificaciones_bachillerato_'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $calificaciones_bachillerato = new Documento;
            $calificaciones_bachillerato->url= Becario::getCarpetaDocumentos().$name;
            $calificaciones_bachillerato->titulo = 'calificaciones_bachillerato';
            $calificaciones_bachillerato->user_id = $id;
            $calificaciones_bachillerato->save();
        }
        //-- Constancia Aceptacion
        if(!isset($constancia_aceptacion))
        {
            $file= $request->file('constancia_aceptacion');
            $name = 'constancia_aceptacio'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $constancia_aceptacion = new Documento;
            $constancia_aceptacion->url= Becario::getCarpetaDocumentos().$name;
            $constancia_aceptacion->titulo = 'constancia_aceptacion';
            $constancia_aceptacion->user_id = $id;
            $constancia_aceptacion->save();
        }
        //-- Constancia Estudios
        if(!isset($constancia_estudios))
        {
            $file= $request->file('constancia_estudios');
            $name = 'constancia_estudios'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $constancia_estudios = new Documento;
            $constancia_estudios->url= Becario::getCarpetaDocumentos().$name;
            $constancia_estudios->titulo = 'constancia_estudios';
            $constancia_estudios->user_id = $id;
            $constancia_estudios->save();
        }
        //-- Calificaciones Universidad
        if(!isset($calificaciones_universidad))
        {
            $file= $request->file('calificaciones_universidad');
            $name = 'calificaciones_universidad'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $calificaciones_universidad = new Documento;
            $calificaciones_universidad->url= Becario::getCarpetaDocumentos().$name;
            $calificaciones_universidad->titulo = 'calificaciones_universidad';
            $calificaciones_universidad->user_id = $id;
            $calificaciones_universidad->save();
        }
        //-- Constancia de Trabajo
        if(!isset($constancia_trabajo))
        {
            $file= $request->file('constancia_trabajo');
            $name = 'constancia_trabajo'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $constancia_trabajo = new Documento;
            $constancia_trabajo->url= Becario::getCarpetaDocumentos().$name;
            $constancia_trabajo->titulo = 'constancia_trabajo';
            $constancia_trabajo->user_id = $id;
            $constancia_trabajo->save();
        }
        //-- Declaración de Impuestos
        if(!isset($declaracion_impuestos))
        {
            $file= $request->file('declaracion_impuestos');
            $name = 'declaracion_impuestos'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $declaracion_impuestos = new Documento;
            $declaracion_impuestos->url= Becario::getCarpetaDocumentos().$name;
            $declaracion_impuestos->titulo = 'declaracion_impuestos';
            $declaracion_impuestos->user_id = $id;
            $declaracion_impuestos->save();
        }
        //-- Recibo de Pago
        if(!isset($recibo_pago))
        {
            $file= $request->file('recibo_pago');
            $name = 'recibo_pago'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $recibo_pago = new Documento;
            $recibo_pago->url= Becario::getCarpetaDocumentos().$name;
            $recibo_pago->titulo = 'recibo_pago';
            $recibo_pago->user_id = $id;
            $recibo_pago->save();
        }
        //-- Carta de Referencia Profesor 1
        if(!isset($referencia_profesor1))
        {
            $file= $request->file('referencia_profesor1');
            $name = 'referencia_profesor_1_'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $referencia_profesor1 = new Documento;
            $referencia_profesor1->url= Becario::getCarpetaDocumentos().$name;
            $referencia_profesor1->titulo = 'referencia_profesor1';
            $referencia_profesor1->user_id = $id;
            $referencia_profesor1->save();
        }
        //-- Carta de Referencia Profesor 2
        if(!isset($referencia_profesor2))
        {
            $file= $request->file('referencia_profesor2');
            $name = 'referencia_profesor_2_'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $referencia_profesor2 = new Documento;
            $referencia_profesor2->url= Becario::getCarpetaDocumentos().$name;
            $referencia_profesor2->titulo = 'referencia_profesor2';
            $referencia_profesor2->user_id = $id;
            $referencia_profesor2->save();
        }
        //-- Ensayo
        if(!isset($ensayo))
        {
            $file= $request->file('ensayo');
            $name = 'ensayo'.$id.'.'.$file->getClientOriginalExtension();
            $path = public_path().Becario::getCarpetaDocumentos();
            $file->move($path, $name);

            $ensayo = new Documento;
            $ensayo->url= Becario::getCarpetaDocumentos().$name;
            $ensayo->titulo = 'ensayo';
            $ensayo->user_id = $id;
            $ensayo->save();
        }
        $becario = Becario::find(Auth::user()->id);
        $becario->documentos = true;
        $becario->save();
        flash("Los documentos fueron cargados exitosamente.",'success');
        return redirect()->route('postulantebecario.documentos');
    }

    public function verdocumento($titulo)
    {
        //return $id;
        //$pdf = Documento::where('user_id','=',Auth::user()->id)->where('titulo','=',$titulo)->first();
        //$pdf = PDF::loadView('sisbeca.nomina.generadopdf', compact('nominas','mes','anho'));
        //return $pdf;
        //$file = File::get(url($pdf->url));
        //$response = Response::file($file, 200);
        //$response->header('Content-Type', 'application/pdf');
        //return $response;
        //return redirect()->route(response()->file(url($pdf->url)));
    }
}
