<?php

namespace avaa\Http\Controllers;

use avaa\Costo;
use DateTime;
use Timestamp;
use Illuminate\Http\Request;

class MantenimientoEditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('editor');
    }

    public function index()
    {
        $costos= Costo::first();
        if(is_null($costos))
        {
            $costos = null;
            $id=0;
            flash('Disculpe, no se han definido los costos, por favor defínalos.')->error()->important();
        }
        else
        {
            $id=$costos->id;
        }
        return view('sisbeca.costos.index')->with('costo',$costos)->with('id',$id);
    }

    public function edit($id)
    {
        $costos= Costo::first();
        if(is_null($costos))
        {
            $costos = null;
            $id=0;
            flash('Disculpe, no se han definido los costos, por favor defínalos.')->error()->important();
        }
        else
        {
            $id=$costos->id;
        }
        return view('sisbeca.costos.edit')->with('costo',$costos)->with('id',$id);
    }

    public function update(Request $request, $id)
    {
        //return $request->all();
        if(is_null($request->get('sueldo_becario')))
        {
            if($id==0)
            {  
                $costo = new Costo($request->all());
                $costo->fecha_valido=  DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_valido').' 00:00:00');
                $costo->save();
                flash('Los costos fueron definidos exitosamente.','success')->important();
            }
            else
            {
                $costo = Costo::first();
                $costo->fill($request->all());
                $costo->fecha_valido=  DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_valido').' 00:00:00');
                $costoAux = str_replace(".","",$request->get('costo_ases_basica'));
                $costoAux= str_replace(",",".",$costoAux);
                $costo->costo_ases_basica=$costoAux;
                $costoAux = str_replace(".","",$request->get('costo_ases_intermedia'));
                $costoAux= str_replace(",",".",$costoAux);
                $costo->costo_ases_intermedia=$costoAux;
                $costoAux = str_replace(".","",$request->get('costo_ases_completa'));
                $costoAux= str_replace(",",".",$costoAux);
                $costo->costo_ases_completa=$costoAux;
                $costoAux = str_replace(".","",$request->get('costo_membresia'));
                $costoAux= str_replace(",",".",$costoAux);
                $costo->costo_membresia=$costoAux;
                $costoAux = str_replace(".","",$request->get('costo_adicional1'));
                $costoAux= str_replace(",",".",$costoAux);
                $costo->costo_adicional1=$costoAux;
                $costo->save();
                flash('Los costos fueron actualizados exitosamente','success')->important();
            }
        }
        else
        {
            flash('Disculpe, ocurrio un error inesperado.','danger')->important();
        }
        return  redirect()->route('costos.index');
    }

    
}
