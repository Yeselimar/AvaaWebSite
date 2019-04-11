<?php

namespace avaa\Http\Controllers;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use avaa\FactLibro;
use avaa\Becario;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DateTime;


class FactLibrosController extends Controller
{
    public function verfacturas($mes,$anho,$id)
    {
        $factlibros = FactLibro::where('becario_id','=',$id)->where('status','=','por procesar')->get();
        if($factlibros->count()>0)
        {
            $facturas = $factlibros;
            $total = 0;
            foreach ($facturas as $factura)
            {
                $total = $total + $factura->costo;
            }
            return view('sisbeca.factlibros.verfacturas')->with('factlibros', $factlibros)->with('mes', $mes)->with('anho', $anho)->with('total', $total);
        }
        else
        {
            $user = User::find($id);
            flash('El becario '.$user->nombreyapellido().' no ha cargado factura de libros en este mes')->warning()->important();
            return back();
        }
    }

    public function listar()
    {
        $id_user=Auth::user()->id;
        $facturas = FactLibro::query()->where('becario_id','=',$id_user)->get();
        return view('sisbeca.factlibros.cargarVerFacturas')->with('facturas',$facturas);
    }
    public function crear()
    {
        $model = "crear";
        return view('sisbeca.factlibros.model')->with(compact('model'));
    }

    public function guardar(Request $request)
    {
        $becario = Auth::user()->becario;
        //$becario= Becario::find(Auth::user()->id);
        $hoy = new DateTime();
        if ($becario->status == 'activo' || $becario->status == 'probatorio1' || $becario->status == 'probatorio2')
        {
            if(Auth::user()->becario->fecha_inactivo !== null || Auth::user()->becario->fecha_desincorporado <= $hoy) {
                flash('Disculpe no puede continuar con este proceso, usted se encuentra a la espera de un cambio de status por parte de la directiva','danger')->important();
                return redirect()->route('facturas.listar');
            }
            $file= $request->file('url_factura');
            $name = 'fact_'.Auth::user()->cedula . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/documentos/facturas/';
            $file->move($path, $name);
            $factura = new FactLibro();
            $factura->status = "cargada";
            $costoAux = str_replace(".","",$request->get('costo'));
            $costoAux= str_replace(",",".",$costoAux);
            $factura->costo=$costoAux;
            $factura->name= $request->get('name');
            $factura->curso= $request->get('curso');
            $factura->becario_id = Auth::user()->id;
            $factura->url= '/documentos/facturas/'.$name;
            $factura->fecha_cargada = date("Y-m-d H:i:s");
            $factura->save();

            //Enviar correo a la persona notificando que fue recibido su justificativo
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
            $body = view("emails.facturas.notificacion-recibido")->with(compact("factura","becario"));
            $mail->MsgHTML($body);
            $mail->addAddress($becario->user->email);
            //$mail->send();

            //Generar un mensaje
            $mensaje = new Mensajes();
            
            flash('La factura fue cargada exitosamente.','success')->important();


        }
        else
        {
            flash('Disculpe, actualmente su status es: '.$becario->status.' no puede cargar facturas con su status actual')->error()->important();
        }



        return redirect()->route('facturas.listar');
    }

    public function facturaspendientes()
    {
        return view('sisbeca.factlibros.obtenerpendientes');
    }

    public function obtenerpendienteservicio()
    {
        $todos = collect();
        $becarios = Becario::egresados()->probatorio1()->probatorio2()->activos()->get();
        $cursos = FactLibro::conEstatus('cargada')->with("becario")->with("usuario")->orderby('created_at','desc')->get();
        foreach ($becarios as $becario)
        {
            if(count($becario->factlibros)!=0)
            {
                $todos->push(array(
                    'id' => $becario->user_id,
                    'estatus_becario' => $becario->status,
                    'nombre_becario' => $becario->user->nombreyapellido(),
                    'total_pendientes' =>$becario->getTotalFacturasPendientes(),
                    'facturas' => $becario->factlibros
                ));
            }
        }
        return response()->json(['becarios'=>$todos]);
    }

    public function actualizarfactura(Request $request, $id)
    {
        $factura = FactLibro::find($id);
        if($request->status=='por procesar')
        {
            $factura->status = $request->status;
            $factura->fecha_procesada = date("Y-m-d H:i:s");
        }
        else
        {
            $factura->status = $request->status;
        }
       
        $factura->save();

        $becario = $factura->becario;
        //Enviar correo a la persona notificando que fue recibido su justificativo
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
        $body = view("emails.facturas.notificacion-estatus")->with(compact("factura","becario"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
        return response()->json(['success'=>'La factura de '.$becario->user->nombreyapellido().' fue actualizada exitosamente.']);
    }
}
