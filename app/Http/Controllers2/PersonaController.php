<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use App\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($tipo)
    {
       

        if($tipo == 'c'){
            $personas = Persona::where('tipo', 'c')->get();
            return view('persona.cliente.index', compact('personas'));
        }
        else if ($tipo == 'u'){
            $personas = Persona::where('tipo', 'u')->get();
            return view('persona.usuario.index', compact('personas'));
        }
        
       
    }

    public function buscarcliente($cedula){

        $persona = Persona::where('tipo', 'c')->where('cedula', $cedula)->first();

        return $persona;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $data)  
    {      $password = $data->input('password');
        
        if(isset($password))
        {   
            $datos = $data->all();
            $password = $datos['password'];
            $datos['password'] = Hash::make($password);
            $datos['pass'] = $password;
                      
            
            if(Persona::create($datos))
            {
               
               
                echo true;
            }
        }else{
            if(Persona::create($data->all()))
                {
                    echo true;
                }
        }
       

        
    }

    public function postEditar(Request $data){
        if( Persona::where('id', $data->id)             
        ->update($data->except(['_token']))){
            echo true;
        }
       
    }

    public function editar($id, $tipo){

        $persona = Persona::find($id);     
        if($tipo == 'c'){
            return view ('persona.cliente.edit', compact('persona'));
        }
        elseif($tipo == 'u'){
            return view ('persona.usuario.edit', compact('persona'));
        }

        
        
    }

    public function eliminar($id){

        $persona = Persona::find($id);

        if($persona->delete()){

            echo true;
        }
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}