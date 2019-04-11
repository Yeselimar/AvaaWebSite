<?php

namespace avaa\Http\Controllers;

use avaa\Noticia;
use avaa\Storage;
use Auth;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use Redirect;
use Yajra\Datatables\Datatables;
use Laracasts\Flash\Flash;
use avaa\Http\Requests\NoticiaRequest;
use avaa\Http\Requests\NoticiaActualizarRequest;
use Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;


class MantenimientoNoticiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('editor');
    }

    public function getPublicaciones()
    {
        $publicaciones = Noticia::all();
        $paux = collect();

        foreach($publicaciones as $p){

            $paux->push(array(
                "publicacion" => $p,
                "editor" => $p->editor->nombreyapellido(),
                "fecha_actualizacion" => $p->fechaActualizacion()
                ));
        }
        return response()->json(['publicaciones'=>$paux]);
    }
    public function index(Request $request)
    {
        $noticias = Noticia::all();
        dd('hola');
        return view('sisbeca.noticias.mantenimientoNoticia')->with(compact('noticias'));
    }

    public function create(Request $request)
    {
        return view('sisbeca.noticias.crearNoticia');
    }
    public function deleteStoragePublicacion(Request $request){ 
        $raiz= \Request::root();

        if($request->id_noticia == -1 || $request->id_noticia == '-1'){
            $publicaciones = Storage :: where('noticia_id','=',null)->where('user_id','=',Auth::user()->id)->get();
            foreach($publicaciones as $p){
                $es_archivo=file_exists(public_path() .$p->url);
                if($es_archivo){
                    unlink(public_path().$p->url);
                }
            }
            Storage::where('noticia_id','=',null)->where('user_id','=',Auth::user()->id)->delete();
        }else
        {
            $publicaciones = Storage :: where('noticia_id','=',$request->id_noticia)->where('user_id','=',Auth::user()->id)->get();
            foreach($publicaciones as $p){
                    $es_archivo=file_exists(public_path() .$p->url);
                    if($es_archivo){
                        unlink(public_path().$p->url);
                    }
                }
            Storage::where('noticia_id','=',$request->id_noticia)->where('user_id','=',Auth::user()->id)->delete();
        }

        return response()->json(['res'=>1]);

    }
    public function insertImgApi(Request $request){
        $file = $request->file;
        if($file && is_file($file)) {
            
            if ($file->getSize() > 5 * pow(1024, 2)) {
                return response()->json(['msg'=>'Error al subir: Archivo excede de 5 MB.','res'=> 0,'url'=> '']);
            }
            $extensions = array(
                'png', 'jpg', 'jpeg', 'jpe', 'svg', 'bmp', 'tif', 'tiff', 'ico','avi', 'mpeg', 'mpg', 'mpe', 'mp4'
            );
            if (!in_array(strtolower($file->getClientOriginalExtension()), $extensions)) {
                return response()->json(['msg'=>'Error al subir: Extension de archivo no permitida','res'=> 0,'url'=> '']);
            }
            $name = 'noticiasAVAA_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/storage/noticias/img';
            $file->move($path, $name);
            $url = '/storage/noticias/img/'.$name;
            $imagen = new Storage();
            $imagen->name = $name;
            $imagen->url = $url;
            $imagen->in_noticia = false;
            $imagen->user_id = \Auth::user()->id; 
            $imagen->save();

            return response()->json(['msg'=>'Imagen subida exitosament','res'=> 1,'url'=>$url]);

        }else {
            return response()->json(['msg'=>'Error al subir imagen','res'=> 0,'url'=> '']);
        }

    }
    public function editApiPublicacion(Request $request,$id){
        $noticia = Noticia::find($id);
        $notice = json_decode($request->notice);
        $noticiaAux = $notice->notice;
        $file = $request->file_img;
        if($file && is_file($file)) {
            $es_archivo=is_file(public_path() .$noticia->url_imagen);
            if($es_archivo)
            {
                unlink(public_path() .$noticia->url_imagen);
            }
            $name = 'noticiasAVAA_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/images/noticias/';
            $file->move($path, $name);
            $noticia->url_imagen = '/images/noticias/'.$name;

        }
        $noticia->titulo = $noticiaAux->titulo;
        $noticia->tipo = $noticiaAux->tipo;
        $noticia->informacion_contacto = $noticiaAux->informacion_contacto;
        $noticia->slug = Noticia::getSlug($noticia->titulo);
        $noticia->contenido = $noticiaAux->contenido;
        if($noticia->tipo === 'noticia'){
            $noticia->al_carrousel = ($noticiaAux->al_carrousel=='1') ? 1 : 0;
        }else {
            $noticia->email_contacto = $noticiaAux->email_contacto;
            $noticia->url_articulo = $noticiaAux->url_articulo;
            $noticia->telefono_contacto = $noticiaAux->telefono_contacto;
        }

        $tipo= ( 'noticia' === $noticia->tipo ) ? 'Noticia' : 'Miembro Institucional';
        $noticia->save();
        $msg = 'La publicación tipo: '.$tipo.' fue actualizada exitosamente.';

        return response()->json(['msg'=>$msg,'res'=> 1]);

        
    }
    public function deleteApiPublicacion($id){
        $noticia= Noticia::find($id);
        if(is_null($noticia))
        {
            $msg='Disculpe, el archivo solicitado no ha sido encontrado.';
            $res=0;
        }
        if($noticia->delete())
        {
            $es_archivo=file_exists(public_path() .$noticia->url_imagen);
            if($es_archivo)
                     unlink(public_path() .$noticia->url_imagen);
            $msg = 'La publicación con titulo '.$noticia->titulo.' tipo: '.$noticia->tipo.' fue eliminada exitosamente.';
            $res= 1;
        }
        else
        {
            $msg='Disculpe, ha ocurrido un error al eliminar publicación.';
            $res=0;
        }
        return response()->json(['msg'=>$msg,'res'=> $res]);
    }
    public function createApiPublicacion(Request $request){
        $notice = json_decode($request->notice);
        $noticiaAux = $notice->notice;
        $file = $request->file_img;
        if($file && is_file($file)) {
            $noticia = new Noticia();
            $name = 'noticiasAVAA_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/images/noticias/';
            $file->move($path, $name);
            $noticia->url_imagen = '/images/noticias/'.$name;
            $noticia->titulo = $noticiaAux->titulo;
            $noticia->tipo = $noticiaAux->tipo;
            $noticia->informacion_contacto = $noticiaAux->informacion_contacto;
            $noticia->slug = Noticia::getSlug($noticia->titulo);
            $noticia->user_id = \Auth::user()->id;
            $noticia->contenido = $noticiaAux->contenido;
            if($noticia->tipo === 'noticia'){
                $noticia->al_carrousel = ($noticiaAux->al_carrousel=='1') ? 1 : 0;
            }else {
                $noticia->email_contacto = $noticiaAux->email_contacto;
                $noticia->url_articulo = $noticiaAux->url_articulo;
                $noticia->telefono_contacto = $noticiaAux->telefono_contacto;
            }
    
            $tipo= ( 'noticia' === $noticia->tipo ) ? 'Noticia' : 'Miembro Institucional';
            $noticia->save();
            $msg = 'La publicación tipo: '.$tipo.' fue registrada exitosamente.';
            return response()->json(['msg'=>$msg,'res'=> 1]);

        } else {
            $msg = "Ocurrio un error con la imagen principal";
            return response()->json(['msg'=>$msg,'res'=> 0]);

        }
       


        
    }
    public function store(NoticiaRequest $request)
    {
        $file= $request->file('url_imagen');
        $name = 'noticiasAVAA_' . time() . '.' . $file->getClientOriginalExtension();
        $path = public_path() . '/images/noticias/';
        $file->move($path, $name);
        $noticia = new Noticia($request->all());
        $noticia->slug = Noticia::getSlug($noticia->titulo);
        $noticia->user_id = \Auth::user()->id;
        $noticia->url_imagen = '/images/noticias/'.$name;
        $noticia->al_carrousel = ($request->destacada=='1') ? 1 : 0;
        $tipo= ( 'noticia' === $noticia->tipo ) ? 'Noticia' : 'Miembro Institucional';

        if($noticia->save())
        {
            flash('La publicación tipo: '.$tipo.' fue registrada exitosamente.','success')->important();
        }
        else
        {
            flash('Disculpe, ha ocurrido un error al registrar la publicación.')->error()->important();
        }
        return redirect()->route('noticia.index');

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $noticia = Noticia::find($id);
        if(is_null($noticia))
        {
            flash('Disculpe, el archivo solicitado no ha sido encontrado.')->error()->important();
            return back();
        }
        return view('sisbeca.noticias.editarNoticia')->with('noticia',$noticia);
    }

    public function update(NoticiaActualizarRequest $request, $id)
    {
        $noticia = Noticia::find($id);
        $file= $request->file('url_imagen');
        if($request->tipo==='miembroins')
        {
            $errores = Validator::make($request->all(),
                [
                'url_articulo' => 'required|url',
                'email_contacto' => [
                    Rule::unique('noticias')->ignore($noticia->id),
                ],
                'email_contacto' => 'nullable|email|max:30',

            ])->validate();
        }

        if($request->url_imagen)
        {
            //return "hay algo";
            $es_archivo=is_file(public_path() .$noticia->url_imagen);
            if($es_archivo)
            {
                unlink(public_path() .$noticia->url_imagen);
            }
            $name = 'noticiasAVAA_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/images/noticias/';
            $file->move($path, $name);
        }


        $noticia->fill($request->all());
        $noticia->slug = Noticia::getSlug($noticia->titulo);
        $noticia->user_id = \Auth::user()->id;
        if($request->url_imagen)
        {
            $noticia->url_imagen = '/images/noticias/'.$name;
        }
        $noticia->al_carrousel = ($request->destacada=='1') ? 1 : 0;

        if($noticia->save())
        {
            flash('La publicación fue actualizada exitosamente.','success')->important();
        }
        else
        {
            flash('Disculpe, ha ocurrido un error al actualizar la publicación.')->error()->important();
        }
        return  redirect()->route('noticia.index');
    }

    public function destroy($id)
    {
        $noticia= Noticia::find($id);
        if(is_null($noticia))
        {
            flash('Disculpe, el archivo solicitado no ha sido encontrado.')->error()->important();
            return back();
        }
        if($noticia->delete())
        {
            $es_archivo=file_exists(public_path() .$noticia->url_imagen);
            if($es_archivo)
                     unlink(public_path() .$noticia->url_imagen);
            flash('La publicación fue eliminada exitosamente.','success')->important();
        }
        else
        {
            flash('Disculpe, ha ocurrido un error al eliminar publicación.')->error()->important();
        }
        return  redirect()->route('noticia.index');
    }
}
