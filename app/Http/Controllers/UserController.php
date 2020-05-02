<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\User;


class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
     public function index($search = null){
        if(!empty($search)){
            
            $users = User::where('nick', 'LIKE', '%'.$search.'%')
                        ->orWhere('name', 'LIKE','%'.$search.'%')
                        ->orWhere('surname', 'LIKE','%'.$search.'%')
                        ->orderBy('id', 'desc')
                        ->paginate(5);

        }else{
            $users = User::orderBy('id', 'desc')->paginate(5);
            
        }
        
        
        return view('user.index', ['users'=> $users]);
    }
    
    
    public function config(){
        return view('user.config');
    }
    
    public function update(Request $request){
        //conseguir usuario identificado
        $user = \Auth::user();
        $id = $user->id;
        
        //validacion del formulario
        $validate = $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nick' => 'required|string|max:255|unique:users,nick,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id
        ]);
        
        //recoger datos del formulario
        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');
        
        //asignar nuevos valores al objeto
        $user->name = $name;
        $user->surname = $surname;
        $user->nick = $nick;
        $user->email = $email;
        
        //subir imagen
        $image_path = $request->file('image_path');
        if($image_path){
            //pone nombre unico
            $image_path_name = time().$image_path->getClientOriginalName();
            
            //guardarla en la carpeta de storage (storage/app/users)
            Storage::disk('users')->put($image_path_name, File::get($image_path));
            
            // Setear nombre de imagen en el objeto
            $user->image = $image_path_name;
        }
        
        //ejecutar consulta y cambios en db
        $user->update();
        
        return redirect()->route('config')->with(['message' => 'Usuario actualizado correctamente!']);
    }
    
    //foto perfil
    public function getImage($filename){
        $file = Storage::disk('users')->get($filename);
        return new Response($file, 200);
    }


    
    //update password
    public function updatepass(Request $request){
        $user = \Auth::user();
        $id = $user->id;
        $validate = $this->validate($request, [
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string'
        ]);
        
        $id = \Auth::user()->id;
        $password = $request->input('password');
        $passconf = $request->input('password_confirmation');
        
        
        $user->password = Hash::make($password); 
         
        $user->update();
        return redirect()->route('config')->with(['message' => 'Password actualizada correctamente!']);
        
    }
    
    public function profile($id){
        $user = User::find($id);
        
        
        return view('user.profile', [
            'user' => $user
        ]);
    }
    
   
}
