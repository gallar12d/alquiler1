<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use App\CompromisoProducto;
use App\Compromiso;
use Illuminate\Http\Request;


class ProductoController extends Controller
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
    public function index()
    {
        $proveedores = Proveedor::all();
        $productos = Producto::all();
        return view('inventario.index', compact('productos', 'proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $data)

   
    {
        
        $id = Producto::create($data->all())->id;

        if($data->hasFile('foto')){
            $image = $data->file('foto');
            $destinationPath = public_path(). '/fotosProductos/';
            $ext = $image->getClientOriginalExtension();  
            
            $filename = $id.'.'.$ext;
            $productoNuevo = Producto::find($id);
            $productoNuevo->foto = $filename;
            $productoNuevo->save();
            
            $image->move($destinationPath, $filename);
            
        }

       

        if($id)
        {
            echo true;
        }
    }

    public function postEditar(Request $data){
        if( Producto::where('id', $data->id)             
        ->update($data->except(['_token']))){

            

            if($data->hasFile('foto')){
                $image = $data->file('foto');
                $destinationPath = public_path(). '/fotosProductos/';
                $ext = $image->getClientOriginalExtension();  
                
                $filename = $data->id.'.'.$ext;
                $productoNuevo = Producto::find($data->id);
                $productoNuevo->foto = $filename;
                $productoNuevo->save();
                
                $image->move($destinationPath, $filename);
                
            }

            echo true;
        }
       
    }

    public function buscarproducto($id){
        $productos = Producto::leftJoin('compromiso_producto', 'compromiso_producto.id_producto', '=', 'producto.id')
        ->leftJoin('compromiso', 'compromiso.id_compromiso', '=', 'compromiso_producto.id_compromiso')->           
            where('producto.id', $id)       
            ->orWhere('producto.nombre', 'like',   $id.'%')
            ->orWhere('producto.referencia', 'like',  $id. '%')
                ->select('*', 'producto.estado as estado_producto', 'producto.id as idpro')                           
                ->get()->toArray();  
        
       
        
          $productosId = Producto::where('id', $id)       
            ->orWhere('nombre', 'like',   $id.'%')
            ->orWhere('referencia', 'like',  $id. '%')->get()->pluck('id');
        
        //sacar los compromisos con esos productos
        $compromisoProducto = CompromisoProducto::whereIn('id_producto', $productosId )->get()->pluck('id_compromiso');
        
        $compromisos = Compromiso::whereIn('id_compromiso', $compromisoProducto)->get();
        return $productos;
         

    }

    public function editar($id){

        $producto = Producto::find($id);
        $proveedores = Proveedor::all();

        return view ('inventario.edit', compact('producto', 'proveedores'));
        
    }

    public function eliminar($id){

        $producto = Producto::find($id);

        if($producto->delete()){

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
