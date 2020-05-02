<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
   public function __construct(){
        $this->middleware('auth');
    }
    
    public function save(Request $request){
        //validacion
        $validate = $this->validate($request, [
            'image_id' => 'integer|required',
            'content' => 'string|required'
        ]);
        
        //recoger datos
        $user = \Auth::user();
        $image_id = $request->input('image_id');
        $content = $request->input('content');
        
        //asignar valores al objeto a guardar
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;
        
        //guardar en database
        $comment->save();
        
        //redireccion
        return redirect()->route('image.detail', ['id' => $image_id])
                         ->with([
                             'message' => 'Comentario realizado correctamente'
                         ]);
        
    }
    
    public function delete($id){
        //obtener datos del usuario identificado
        $user = \Auth::user();
        
        //obtener objeto del comentario
        $comment = Comment::find($id);
        
        //comprobar si es dueño del comentario o de la publicacion
        if($user && ($comment->user_id == $user->id) || $comment->image->user_id == $user->id){
            $comment->delete();
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                         ->with([
                             'message' => 'Comentario eliminado correctamente'
                         ]);
        }else{
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                         ->with([
                             'message' => 'El comentario no se pudo eliminar'
                         ]);
        }
        
    }
}
