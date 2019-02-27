<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use App\Persona;
use App\Garantia;
use App\Entrada;
use App\Salida;
use App\Abono;
use App\Factura;
use App\Compromiso;
use App\CompromisoProducto;
use App\CompromisoProducto2;
use App\ConsecutivoFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DateTime;

class CompromisoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $compromisos = Compromiso::take('100')->orderBy('id_compromiso', 'desc')->get();
        return view('compromiso.index', compact('compromisos'));
    }

    public function postFiltrar(Request $data) {
        $fechaInicial = $data->input('fechaInicio');
        $fechaFin = $data->input('fechaFin');
        $compromisos = Compromiso::where('fecha_compromiso', '>=', $fechaInicial)->where('fecha_compromiso', '<=', $fechaFin)->get();
        return view('compromiso.index', compact('compromisos'));
    }

    public function postFiltrar2(Request $data) {
        if (!is_null($data->input('cedula'))) {
            $compromisos = Compromiso::where('cedula', $data->input('cedula'))->get();
        }

        if (!is_null($data->input('factura'))) {
            $facturasid = Factura::where('numero_factura', $data->input('factura'))->get()->pluck('id_factura');

            $compromisos = Compromiso::whereIn('id_factura', $facturasid)->get();
        }



        return view('compromiso.index', compact('compromisos'));
    }

    public function create() {
        return view('compromiso.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $data) {
        $fecha_compromiso = $data->input('fecha_compromiso');
        $fecha_devolucion = $data->input('fecha_devolucion');
        $abono = $data->input('abono');
        $tipo_pago = $data->input('tipo_pago');
        $saldo = $data->input('saldo');
        $id_cliente = $data->input('id_cliente');
        $productos = $data->input('productos');
        $descuento = $data->input('descuento');

        if ($descuento == 0) {
            //modificar el usuario y poner el descuento en 0
            $userModificarDescuento = Persona::where('cedula', $id_cliente)->first();
            $userModificarDescuento->descuento = 0;
            $userModificarDescuento->save();
        }


        $banderaCompromiso = false;
        $cliente2 = Persona::where('cedula', $id_cliente)->first();

        //verificar las fechas de cada producto si es que estan comprometidos y ver si se puede 

        foreach ($productos as $producto) {
            $id = $producto[0];

            $tieneCompromisos = CompromisoProducto::where('id_producto', $id)->get();
            if (count($tieneCompromisos)) {

                foreach ($tieneCompromisos as $compro) {
                    $compromiso = Compromiso::find($compro->id_compromiso);
                    $fechaDevol = $compromiso->fecha_devolucion;
                    //comparar cada fecha de devolución con la fecha nueva de compromiso tiene que estar con mas de 4 dias
                    if ($fechaDevol >= $fecha_compromiso) {
                        return 500;
                    } else if ($fechaDevol < $fecha_compromiso) {

                        $date1 = new DateTime($fechaDevol);
                        $date2 = new DateTime($fecha_compromiso);
                        $interval = $date1->diff($date2);
                        $interval = intval($interval->d);
                        if ($interval <= 3) {

                            return 500;
                        }
                    }
                }
            }
        }



        $compromiso = new Compromiso;
        $compromiso->fecha_compromiso = $fecha_compromiso;
        $compromiso->fecha_devolucion = $fecha_devolucion;
        $compromiso->estado = 'Creado';
        $compromiso->cedula = $id_cliente;

        if ($compromiso->save()) {
            $banderaCompromiso = true;
        }

        if ($banderaCompromiso) {
            $id_compromiso = $compromiso->id_compromiso;

            //crear los compromisos_productos
            $concepto = 'Compromiso de productos: ';
            

            foreach ($productos as $producto) {
                $compromisoProducto = new CompromisoProducto;
                $compromisoProducto->id_producto = $producto[0];
                $compromisoProducto->ajustes = $producto[1];
                $compromisoProducto->id_compromiso = $id_compromiso;
                $compromisoProducto->save();
                $compromisoProducto2 = new CompromisoProducto2;
                $compromisoProducto2->id_producto = $producto[0];
                $compromisoProducto2->ajustes = $producto[1];
                $compromisoProducto2->id_compromiso = $id_compromiso;
                $compromisoProducto2->save();
                //cambiar estado al producto
                $producto = Producto::find($producto[0]);
                $concepto = $concepto . $producto->nombre . ', ';
                $producto->estado = 'Comprometido';
                $producto->save();
                
               
            }

            //crar la factura
            if ($banderaCompromiso) {
                $factura = new Factura;
                $factura->cedula = $id_cliente;
                $factura->valor = intval($abono) + intval($saldo);
                $factura->saldo = $saldo;
                $factura->abono = $abono;
                $factura->estado = 'Pendiente';
                $factura->metodo_pago = $tipo_pago;
                //obtener el consecutivo y aumentarlo mas 1 cuando se genere una factura nueva
                $consecutivoFactura = ConsecutivoFactura::find(1);
                if ($factura->save()) {
                    $facturamodificar = Factura::find($factura->id_factura);
                    $facturamodificar->numero_factura = $consecutivoFactura->numero;
                    $numeroFactura = $consecutivoFactura->numero;
                    $consecutivoFactura->numero = $consecutivoFactura->numero + 1;
                    $consecutivoFactura->save();
                    $facturamodificar->save();
                    $compromisoModificar = Compromiso::find($id_compromiso);
                    $compromisoModificar->id_factura = $factura->id_factura;
                    if ($compromisoModificar->save()) {

                        $url = url('/compromiso');
                        $factura = $numeroFactura;
                        $cliente = $id_cliente;
                        $valorTotal = intval($abono) + intval($saldo);
                        $valorAbono = intval($abono);
                        $valorSaldo = intval($saldo);
                        $fecha = date("Y-m-d");
                        //datos para word
                        $nombreCliente = '';
                        $direccionCliente = '';
                        $referenciaCliente = '';
                        $cedulaCliente = '';
                        $fechaHoy = date('Y-m-d');
                        $fechaComp = '';
                        $abono2 = '';
                        $saldo2 = '';
                        $total2 = '';
                        $fechaDev = '';
                        $facturaNumero2 = '';
                        $celularCliente = '';
                        $telefonoCliente = '';

                        $productosRecibo = array();

                        if (isset($productos)) {
                            $productosRecibo = $productos;
                        }
                        if (isset($cliente2->name)) {
                            $nombreCliente = $cliente2->name;
                        }
                        if (isset($cliente2->celular)) {
                            $celularCliente = $cliente2->celular;
                        }
                        if (isset($cliente2->telefono)) {
                            $telefonoCliente = $cliente2->telefono;
                        }
                        if (isset($cliente2->direccion)) {
                            $direccionCliente = $cliente2->direccion;
                        }
                        if (isset($cliente2->direccion)) {
                            $direccionCliente = $cliente2->direccion;
                        }
                        if (isset($cliente2->referencia_nombre)) {
                            $referenciaCliente = $cliente2->referencia_nombre;
                        }
                        if (isset($cliente2->cedula)) {
                            $cedulaCliente = $cliente2->cedula;
                        }

                        if (isset($fecha_compromiso)) {
                            $fechaComp = $fecha_compromiso;
                        }
                        if (isset($fecha_devolucion)) {
                            $fechaDev = $fecha_devolucion;
                        }
                        if (isset($abono)) {
                            $abono2 = $abono;
                        }
                        if (isset($saldo)) {
                            $saldo2 = $saldo;
                        }
                        if (isset($numeroFactura)) {
                            $facturaNumero2 = $numeroFactura;
                        }
                        if (isset($saldo) && isset($abono)) {
                            $total2 = intval($abono) + intval($saldo);
                        }




                        $this->crearRecibo2($nombreCliente, $direccionCliente, $referenciaCliente, $cedulaCliente, $fechaHoy, $fechaComp, $abono2, $saldo2, $total2, $fechaDev, $facturaNumero2, $telefonoCliente, $celularCliente, $productosRecibo);
//                        $this->crearRecibo($url, $factura, $cliente, $valorTotal, $valorAbono, $valorSaldo, $fecha, $concepto);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function crearRecibo($url, $factura, $cliente, $valorTotal, $valorAbono, $valorSaldo, $fecha, $concepto) {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('Factura No: ' . $factura);
        $text = $section->addText('Valor factura: ' . $valorTotal);
        $text = $section->addText('Valor abono: ' . $valorAbono);
        $text = $section->addText('Valor saldo: ' . $valorSaldo);
        $text = $section->addText('Concepto: ' . $concepto);
        $text = $section->addText('Cliente: ' . $cliente);
        $text = $section->addText('Fecha: ' . $fecha);
//        $section->addImage("./images/Krunal.jpg");
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        if (file_exists(public_path('Recibo.docx'))) {
            unlink(public_path('Recibo.docx'));
        }

        $objWriter->save('Recibo.docx');
        echo 200;
        return 200;
    }

    public function crearRecibo2($nombreCliente = '', $direccionCliente = '', $referenciaCliente = '', $cedulaCliente = '', $fechaHoy = '', $fechaComp = '', $abono = '', $saldo = '', $total = '', $fechaDev = '', $facturaNumero = '', $telefonoCliente = '', $celularCliente = '', $productosRecibo = NULL) {


        $conteoProductos = count($productosRecibo);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        if ($conteoProductos <= 15) {
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('factura1.docx'));
        } else if ($conteoProductos <= 30) {
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('factura1b.docx'));
        }


        $my_template->setValue('nombreCliente', $nombreCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('referenciaCliente', $referenciaCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('cedulaCliente', $cedulaCliente);
        $my_template->setValue('fechaHoy', $fechaHoy);
        $my_template->setValue('fechaComp', $fechaComp);
        $my_template->setValue('abono', $abono);
        $my_template->setValue('saldo', $saldo);
        $my_template->setValue('total', $total);
        $my_template->setValue('fechaDev', $fechaDev);
        $my_template->setValue('facturaNumero', $facturaNumero);
        $my_template->setValue('telefonoCliente', $telefonoCliente);
        $my_template->setValue('celularCliente', $celularCliente);


        if ($conteoProductos <= 15) {
            $j = 1;
            foreach ($productosRecibo as $prod) {
                $id = $prod[0];
                $buscadoProducto = Producto::find($id);
                $my_template->setValue('p' . $j, $buscadoProducto->nombre . ' - (' . $buscadoProducto->referencia . ')');
                $my_template->setValue('v' . $j, $buscadoProducto->valor);
                $j++;
            }

            if ($j < 15) {
                for ($i = $j; $i <= 15; $i++) {
                    $my_template->setValue('p' . $i, '');
                    $my_template->setValue('v' . $i, '');
                }
            }
        } else if ($conteoProductos <= 30) {
            $j = 1;
            foreach ($productosRecibo as $prod) {
                $id = $prod[0];
                $buscadoProducto = Producto::find($id);
                $my_template->setValue('p' . $j, $buscadoProducto->nombre . ' - (' . $buscadoProducto->referencia . ')');
                $my_template->setValue('v' . $j, $buscadoProducto->valor);
                $j++;
            }

            if ($j < 30) {
                for ($i = $j; $i <= 30; $i++) {
                    $my_template->setValue('p' . $i, '');
                    $my_template->setValue('v' . $i, '');
                }
            }
        }

        try {
            $my_template->saveAs(public_path('ReciboGenerado.docx'));

            echo 200;
        } catch (Exception $e) {
            //handle exception
        }
    }

    public function crearRecibo3($nombreCliente = '', $direccionCliente = '', $referenciaCliente = '', $cedulaCliente = '', $fechaHoy = '', $fechaComp = '', $abono = '', $saldo = '', $total = '', $fechaDev = '', $facturaNumero = '', $telefonoCliente = '', $celularCliente = '', $productosRecibo = NULL, $garantíaTotal = '') {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $conteoProductos = count($productosRecibo);

        if ($conteoProductos <= 15) {
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('factura2.docx'));
        } elseif ($conteoProductos <= 30) {
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('factura2b.docx'));
        }
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('factura2.docx'));
        $my_template->setValue('nombreCliente', $nombreCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('referenciaCliente', $referenciaCliente);
        $my_template->setValue('direccionCliente', $direccionCliente);
        $my_template->setValue('cedulaCliente', $cedulaCliente);
        $my_template->setValue('fechaHoy', $fechaHoy);
        $my_template->setValue('fechaComp', $fechaComp);
        $my_template->setValue('abono', $abono);
        $my_template->setValue('saldo', $saldo);
        $my_template->setValue('total', $total);
        $my_template->setValue('fechaDev', $fechaDev);
        $my_template->setValue('facturaNumero', $facturaNumero);
        $my_template->setValue('telefonoCliente', $telefonoCliente);
        $my_template->setValue('celularCliente', $celularCliente);
        $my_template->setValue('garantias', $garantíaTotal);


        if ($conteoProductos <= 15) {
            $j = 1;
            foreach ($productosRecibo as $prod) {
                $id = $prod;
                $buscadoProducto = Producto::find($id);
                $my_template->setValue('p' . $j, $buscadoProducto->nombre . '- (' . $buscadoProducto->referencia . ')');
                $my_template->setValue('v' . $j, $buscadoProducto->valor);
                $j++;
            }

            if ($j < 15) {
                for ($i = $j; $i <= 15; $i++) {
                    $my_template->setValue('p' . $i, '');
                    $my_template->setValue('v' . $i, '');
                }
            }
        } elseif ($conteoProductos <= 30) {
            $j = 1;
            foreach ($productosRecibo as $prod) {
                $id = $prod;
                $buscadoProducto = Producto::find($id);
                $my_template->setValue('p' . $j, $buscadoProducto->nombre . '- (' . $buscadoProducto->referencia . ')');
                $my_template->setValue('v' . $j, $buscadoProducto->valor);
                $j++;
            }

            if ($j < 30) {
                for ($i = $j; $i <= 30; $i++) {
                    $my_template->setValue('p' . $i, '');
                    $my_template->setValue('v' . $i, '');
                }
            }
        }

        try {
            $my_template->saveAs(public_path('ReciboGeneradoEntrega.docx'));
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="ReciboGeneradoEntrega.docx"');
            readfile(public_path('ReciboGeneradoEntrega.docx'));

            return redirect('compromiso');
        } catch (Exception $e) {
            echo 'Hubo un error, comuníquese con el administrador del sistema';
        }
    }

    public function eliminar($id) {
        $compromiso = Compromiso::find($id);
        $array_ids_productos = CompromisoProducto::where('id_compromiso', $id)->get()->pluck('id_producto');

        $productosCompromiso = CompromisoProducto::where('id_compromiso', $id);
        $productosCompromiso->delete();
        $facturamodificar = Factura::find($compromiso->id_factura);
        $compromiso->estado = 'Anulado';
        if ($compromiso->save()) {
            //buscar factura y borrarla 
            $facturamodificar->estado = 'Anulada';
            $facturamodificar->save();

            //vrificar si existen mas compromisos con cada producto de lo contrario poner ese producto en disponible
            if (count($array_ids_productos)) {
                foreach ($array_ids_productos as $id_prod) {
                    $tieneCompromisoProducto = CompromisoProducto::where('id_producto', $id_prod)->get();
                    if (count($tieneCompromisoProducto) == 0) {
                        //es por que ese producto no tiene compromisos entonces poner en disponible
                        $productoModificarEstado = Producto::find($id_prod);
                        $productoModificarEstado->estado = 'Disponible';
                        $productoModificarEstado->save();
                    }
                }
            }

            //ver si ha tenido abonos para anularlos 



            echo true;
        }
    }

    public function detalle($id_compromiso) {

        $productos = CompromisoProducto2::where('id_compromiso', $id_compromiso)->get();
        return view('compromiso.detalle', compact('productos'));
    }

    public function entregar($id) {

        $productos = CompromisoProducto::where('id_compromiso', $id)->get();
        $compromiso = Compromiso::find($id);
        $factura = Factura::find($compromiso->id_factura);
        return view('compromiso.entrega', compact('productos', 'compromiso', 'factura'));
    }

    public function abonar($id_compromiso) {
        $compromiso = Compromiso::find($id_compromiso);
        return view('compromiso.abonar', compact('compromiso'));
    }

    public function penalizar($id) {

        $productos = CompromisoProducto::where('id_compromiso', $id)->get();
        $compromiso = Compromiso::find($id);
        $factura = Factura::find($compromiso->id_factura);
        return view('compromiso.penalizar', compact('productos', 'compromiso', 'factura'));
    }

    public function devolver($id) {

        $productos = CompromisoProducto::where('id_compromiso', $id)->get();
        $compromiso = Compromiso::find($id);
        $factura = Factura::find($compromiso->id_factura);

        //calcular recargo

        $fecha_devolucionPactada = strtotime($compromiso->fecha_devolucion);

        $fechaHoy = strtotime(date('y-m-d'));
        $recargo = NULL;
        $textrecargo = 'Recargo';

        if ($fechaHoy > $fecha_devolucionPactada) {
            //calcular diferencia de dias 

            $datetime1 = date_create($compromiso->fecha_devolucion);
            $fechaRecorrerBuscarDomingos = $datetime1;
            $datetime2 = date_create(date('y-m-d'));
            $interval = date_diff($datetime1, $datetime2);
            $interval = $interval->format('%a');
            $conteoDomingos = 0;

            for ($i = 1; $i <= intval($interval); $i++) {
                if ($fechaRecorrerBuscarDomingos->format('l') === 'Sunday') {
                    $conteoDomingos = $conteoDomingos + 1;
                }

                $datetime1->modify("+1 days");
            }


            $porcentaje = ( intval($factura->valor) * 5) / 100;
            $interval = $interval - $conteoDomingos;

            $recargo = $porcentaje * $interval;
            $textrecargo = 'Recargo por: ' . $interval . ' días.';
        }


        return view('compromiso.devolucion', compact('productos', 'compromiso', 'factura', 'recargo', 'textrecargo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPenalizar(Request $request) {
        $compromisoModificar = Compromiso::find($request->id_compromiso);
        $compromisoModificar->estado = 'Penalizado';
        $compromisoModificar->save();
        $facturaModificar = Factura::find($request->id_factura);
        $valor_devolucion = intval($request->input('valor_devolucion'));

        if ($valor_devolucion == 0) {
            return redirect('compromiso');
        }
        $persona = Persona::where('cedula', $compromisoModificar->cedula)->first();
        $salida = new Salida;
        $salida->concepto = 'Penalización compromiso con factura no: ' . $facturaModificar->numero_factura;
        $salida->valor = $valor_devolucion;
        $salida->nombre_persona = $persona->name;
        $salida->identificacion = $persona->cedula;

        if ($salida->save()) {
            return redirect('compromiso');
        }
    }

    public function ajustar($id_compromiso = NULL, $option = NULL) {

        $compromisoModificar = Compromiso::find($id_compromiso);
        if ($option == 'a') {
            $compromisoModificar->ajustado = 1;
            $compromisoModificar->save();
            return 200;
        } else if ($option == 'd') {
            $compromisoModificar->ajustado = 0;
            $compromisoModificar->save();
            return 200;
        }
    }

    public function getPendientes() {

        $fechaHoy = date('Y-m-d');
        $compromisos = Compromiso::where('fecha_devolucion', '<', $fechaHoy)->where('estado', 'Entregado')->orWhere('estado', 'Pendiente')->get();
        return view('compromiso.pendientes', compact('compromisos'));
    }

    public function postEntregar(Request $request) {

        $facturaModificar = Factura::find($request->id_factura);
        $facturaModificar->estado = 'Pagada';
        $facturaModificar->fecha_pago = date('y-m-d');
        $facturaModificar->metodo_pago_saldo = $request->tipo_pago;
        $facturaModificar->save();


        $compromisoModificar = Compromiso::find($request->id_compromiso);
        $productosCompromiso = CompromisoProducto::where('id_compromiso', $compromisoModificar->id_compromiso)->get()->pluck('id_producto');



        //cliente del compromiso

        $clienteCompromiso = Persona::where('cedula', $compromisoModificar->cedula)->first();


        if ($request->tipo_garantia != 'No' || $request->tipo_garantia2 != 'No') {
            $garantia = new Garantia;
            if (isset($request->tipo_garantia) && $request->tipo_garantia != 'No') {
                $garantia->tipo_garantia = $request->tipo_garantia;
                if (!is_null($request->valor_garantia || $request->valor_garantia > 0)) {
                    $garantia->valor = $request->valor_garantia;
                    $garantia->fecha_pago = date('y-m-d');
                }
            }

            if (isset($request->tipo_garantia2) && $request->tipo_garantia2 != 'No') {
                $garantia->tipo_garantia2 = $request->tipo_garantia2;
            }
            $garantia->save();
            $compromisoModificar->id_garantia = $garantia->id_garantia;
        }


        $compromisoModificar->estado = 'Entregado';

        //realizar el ultimo abono si es que hizo abonos anteriormente
//        if (count($compromisoModificar->abonos)) {
//            // si ha hecho abonos y hay que crear el ultimo abono con el saldo 
//            $saldo_abonos = $compromisoModificar->factura->saldo_abonos;
//            $nuevoAbono = new Abono();
//            $nuevoAbono->valor = $saldo_abonos;
//            $nuevoAbono->fecha = date('Y-m-d');
//            $nuevoAbono->id_compromiso = $compromisoModificar->id_compromiso;
//            $nuevoAbono->id_factura = $compromisoModificar->factura->id_factura;
//            $nuevoAbono->id_cliente = $compromisoModificar->persona->id;
//            $nuevoAbono->save();
//
//            //calcular nuevo valor del saldo 2
//            $facturaModificar = Factura::find($compromisoModificar->factura->id_factura);
//            $facturaModificar->saldo_abonos = intval($saldo_abonos) - intval($saldo_abonos);
//            $facturaModificar->save();
//        }



        if ($compromisoModificar->save()) {

            //datos para word
            $nombreCliente = '';
            $direccionCliente = '';
            $referenciaCliente = '';
            $cedulaCliente = '';
            $fechaHoy = date('Y-m-d');
            $fechaComp = '';
            $abono2 = '';
            $saldo2 = '';
            $total2 = '';
            $fechaDev = '';
            $facturaNumero2 = '';
            $celularCliente = '';
            $telefonoCliente = '';

            $productosRecibo = array();

            if (isset($productosCompromiso)) {
                $productosRecibo = $productosCompromiso;
            }
            if (isset($clienteCompromiso->name)) {
                $nombreCliente = $clienteCompromiso->name;
            }
            if (isset($clienteCompromiso->celular)) {
                $celularCliente = $clienteCompromiso->celular;
            }
            if (isset($clienteCompromiso->telefono)) {
                $telefonoCliente = $clienteCompromiso->telefono;
            }
            if (isset($clienteCompromiso->direccion)) {
                $direccionCliente = $clienteCompromiso->direccion;
            }
            if (isset($clienteCompromiso->direccion)) {
                $direccionCliente = $clienteCompromiso->direccion;
            }
            if (isset($clienteCompromiso->referencia_nombre)) {
                $referenciaCliente = $clienteCompromiso->referencia_nombre;
            }
            if (isset($clienteCompromiso->cedula)) {
                $cedulaCliente = $clienteCompromiso->cedula;
            }

            if (isset($compromisoModificar->fecha_compromiso)) {
                $fechaComp = $compromisoModificar->fecha_compromiso;
            }
            if (isset($compromisoModificar->fecha_devolucion)) {
                $fechaDev = $compromisoModificar->fecha_devolucion;
            }

            //evaluar si hizo abonos
            if (count($compromisoModificar->abonos)) {
                $valor_abonos = 0;
                foreach ($compromisoModificar->abonos as $abono) {
                    $valor_abonos = $valor_abonos + $abono->valor;
                }
                $valor_abonos = $valor_abonos + $facturaModificar->abono;
                $abono2 = $valor_abonos;
                $saldo2 = $facturaModificar->saldo_abonos;
            } else {
                if (isset($facturaModificar->abono)) {
                    $abono2 = $facturaModificar->abono;
                }
                if (isset($facturaModificar->saldo)) {
                    $saldo2 = $facturaModificar->saldo;
                }
            }


            if (isset($facturaModificar->numero_factura)) {
                $facturaNumero2 = $facturaModificar->numero_factura;
            }
            if (isset($facturaModificar->saldo) && isset($facturaModificar->abono)) {
                $total2 = intval($facturaModificar->abono) + intval($facturaModificar->saldo);
            }

            $garantíaTotal = 'Ninguna';
            $garantia1 = '';
            $garantia2 = '';

            $id_garantia = $compromisoModificar->id_garantia;

            if (isset($id_garantia) && !is_null($id_garantia)) {
                $tieneGarantias = Garantia::find($id_garantia);

                if ($tieneGarantias->tipo_garantia && $tieneGarantias->valor) {
                    $garantia1 = $tieneGarantias->valor;
                }

                if ($tieneGarantias->tipo_garantia2) {
                    $garantia2 = $tieneGarantias->tipo_garantia2;
                }

                $garantíaTotal = $garantia1 . ' - ' . $garantia2;
            }




            $this->crearRecibo3($nombreCliente, $direccionCliente, $referenciaCliente, $cedulaCliente, $fechaHoy, $fechaComp, $abono2, $saldo2, $total2, $fechaDev, $facturaNumero2, $telefonoCliente, $celularCliente, $productosRecibo, $garantíaTotal);
//                      
            return redirect('compromiso');
        }
    }

    public function postAbonar(Request $request) {
        $id_compromiso = $request->id_compromiso;
        $valor_abono = $request->valor_abono;
        $valorTotalAbonosTodos = 0;


        //obtener el compromiso
        $compromiso = Compromiso::find($id_compromiso);
        $clienteRecibo = Persona::find($compromiso->persona->id);

        $valor_saldo_abonos = $compromiso->factura->saldo_abonos;
        $valor_saldo = $compromiso->factura->saldo;

        if (!$valor_saldo_abonos && $valor_saldo != 0) {
            //es primera vez que se abona y si es posible abonar
            if ($valor_abono <= $valor_saldo) {
                //hacer el abono

                $nuevoAbono = new Abono();
                $nuevoAbono->valor = $valor_abono;
                $nuevoAbono->fecha = date('Y-m-d');
                $nuevoAbono->id_compromiso = $compromiso->id_compromiso;
                $nuevoAbono->id_factura = $compromiso->factura->id_factura;
                $nuevoAbono->id_cliente = $compromiso->persona->id;
                $nuevoAbono->save();

                //calcular nuevo valor del saldo 2
                $facturaModificar = Factura::find($compromiso->factura->id_factura);
                $facturaModificar->saldo_abonos = intval($valor_saldo) - intval($valor_abono);
                $valor_nuevo_saldo = intval($valor_saldo) - intval($valor_abono);
                if ($facturaModificar->save()) {
                    //calcular el valor de todos los abonos
                    if (count($compromiso->abonos)) {
                        foreach ($compromiso->abonos as $abono) {
                            $valorTotalAbonosTodos = $valorTotalAbonosTodos + intval($abono->valor);
                        }
                    }

                    $valorTotalAbonosTodos = $valorTotalAbonosTodos + $facturaModificar->abono;
                    $this->crearReciboAbono($compromiso, $clienteRecibo, $valor_abono, $valor_nuevo_saldo, $valorTotalAbonosTodos);
                    return redirect('compromiso/abonar/' . $compromiso->id_compromiso);
                }
            } else {
                echo 'El valor del abono no puede ser mayor al saldo';
            }
        } else if ($valor_saldo_abonos) {
            if ($valor_abono <= $valor_saldo_abonos) {
                $nuevoAbono = new Abono();
                $nuevoAbono->valor = $valor_abono;
                $nuevoAbono->fecha = date('Y-m-d');
                $nuevoAbono->id_compromiso = $compromiso->id_compromiso;
                $nuevoAbono->id_factura = $compromiso->factura->id_factura;
                $nuevoAbono->id_cliente = $compromiso->persona->id;
                $nuevoAbono->save();

                //calcular nuevo valor del saldo 2
                $facturaModificar = Factura::find($compromiso->factura->id_factura);
                $facturaModificar->saldo_abonos = intval($valor_saldo_abonos) - intval($valor_abono);
                $valor_nuevo_saldo = intval($valor_saldo_abonos) - intval($valor_abono);

                if ($facturaModificar->save()) {

                    if (count($compromiso->abonos)) {
                        foreach ($compromiso->abonos as $abono) {
                            $valorTotalAbonosTodos = $valorTotalAbonosTodos + intval($abono->valor);
                        }
                    }
                    $valorTotalAbonosTodos = $valorTotalAbonosTodos + $facturaModificar->abono;
                    $this->crearReciboAbono($compromiso, $clienteRecibo, $valor_abono, $valor_nuevo_saldo, $valorTotalAbonosTodos);
                    return redirect('compromiso/abonar/' . $compromiso->id_compromiso);
                }
            } else {
                echo 'El valor del abono no puede ser mayor al saldo';
            }
        }
    }

    public function crearReciboAbono($compromisoRecibo, $clienteRecibo, $valorAbono, $valor_nuevo_saldo, $valorTotalAbonosTodos) {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('facturaAbono.docx'));
        $my_template->setValue('conceptoAbono', 'Abono para compromiso con factura No: ' . $compromisoRecibo->factura->numero_factura);
        $my_template->setValue('valorAbono', $valorAbono);
        $my_template->setValue('total', $compromisoRecibo->factura->valor);
        $my_template->setValue('saldoAbonos', $valor_nuevo_saldo);
        $my_template->setValue('totalAbonos', $valorTotalAbonosTodos);

        if (isset($clienteRecibo->name)) {
            $my_template->setValue('nombreCliente', $clienteRecibo->name);
        } else {
            $my_template->setValue('nombreCliente', '');
        }

        if (isset($clienteRecibo->direccion)) {
            $my_template->setValue('direccionCliente', $clienteRecibo->direccion);
        } else {
            $my_template->setValue('direccionCliente', '');
        }
        if (isset($clienteRecibo->referencia_nombre)) {
            $my_template->setValue('referenciaCliente', $clienteRecibo->referencia_nombre);
        } else {
            $my_template->setValue('referenciaCliente', '');
        }
        if (isset($clienteRecibo->cedula)) {
            $my_template->setValue('cedulaCliente', $clienteRecibo->cedula);
        } else {
            $my_template->setValue('cedulaCliente', '');
        }
        $my_template->setValue('fechaHoy', date('Y-m-d'));

        if (isset($compromisoRecibo->fecha_compromiso)) {
            $my_template->setValue('fechaComp', $compromisoRecibo->fecha_compromiso);
        } else {
            $my_template->setValue('fechaComp', '');
        }



        if (isset($compromisoRecibo->fecha_devolucion)) {
            $my_template->setValue('fechaDev', $compromisoRecibo->fecha_devolucion);
        } else {
            $my_template->setValue('fechaDev', '');
        }
        if (isset($compromisoRecibo->factura->numero_factura)) {
            $my_template->setValue('facturaNumero', $compromisoRecibo->factura->numero_factura);
        } else {
            $my_template->setValue('facturaNumero', '');
        }

        if (isset($clienteRecibo->celular)) {
            $my_template->setValue('celularCliente', $clienteRecibo->celular);
        } else {
            $my_template->setValue('celularCliente', '');
        }
        if (isset($clienteRecibo->telefono)) {
            $my_template->setValue('telefonoCliente', $clienteRecibo->telefono);
        } else {
            $my_template->setValue('telefonoCliente', '');
        }



        try {
            $my_template->saveAs(public_path('ReciboGeneradoAbono.docx'));
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="ReciboGeneradoAbono.docx"');
            readfile(public_path('ReciboGeneradoAbono.docx'));
            return redirect('compromiso');
        } catch (Exception $e) {
            echo 'Hubo un error, comuníquese con el administrador del sistema';
        }
    }

    public function postDevolucion(Request $request) {

        $compromisoModificar = Compromiso::find($request->id_compromiso);
        $compromisoRecibo = $compromisoModificar;
        $clienteRecibo = Persona::where('cedula', $compromisoModificar->cedula)->first();
        $compromisoModificar->estado = 'Devuelto';
        $compromisoModificar->buen_estado = $request->malas_condiciones;


        $recibo = false;
        $valorDanio = 0;
        $valorRecargo = 0;
        $conceptoDanio = '';
        $conceptoRecargo = '';
        if (isset($request->condiciones) && !is_null($request->condiciones)) {
            $compromisoModificar->condiciones_entrega = $request->condiciones;
        }
        if (isset($request->valor_danio) && !is_null($request->valor_danio)) {
            $nuevaentrada = new Entrada;
            $nuevaentrada->concepto = 'Pago por daños de compromiso con factura No ' . $compromisoModificar->factura->numero_factura;
            $nuevaentrada->valor = $request->valor_danio;
            $nuevaentrada->tipo = 'Daños';
            $nuevaentrada->metodo_pago = $request->metodo_pago;
            $nuevaentrada->save();

            //hay que entregar recibo
            $recibo = true;
            $valorDanio = $request->valor_danio;
            $conceptoDanio = 'Pago por daños de compromiso con factura No ' . $compromisoModificar->factura->numero_factura;
            $conceptoDanio = $conceptoDanio . ': ' . $request->condiciones;
        }

        if (!isset($request->anular) && is_null($request->anular)) {
            if (!is_null($request->recargo)) {
                $nuevaentrada = new Entrada;
                $nuevaentrada->concepto = 'Recargo por demora de entrega de compromiso con factura No  ' . $compromisoModificar->factura->numero_factura;
                $nuevaentrada->valor = $request->recargo;
                $nuevaentrada->tipo = 'Recargo';
                $nuevaentrada->save();

                //hay que entregar recibo
                $recibo = true;
                $valorRecargo = $request->recargo;
                $conceptoRecargo = 'Recargo por demora de entrega de compromiso con factura No  ' . $compromisoModificar->factura->numero_factura;
            }
        }

        //ver si tiene una garantía
        $valorGarantiaSalida = 0;
        if (isset($compromisoModificar->id_garantia)) {
            $garantiaQuitar = Garantia::find($compromisoModificar->id_garantia);
            if ($garantiaQuitar->tipo_garantia == 'dinero') {
                if (isset($garantiaQuitar->valor)) {
                    $valorGarantiaSalida = $garantiaQuitar->valor;
                }
            }

            // poner la garantia en estado 0
            $garantiaQuitar->estado = 0;
            $garantiaQuitar->save();
        }

        if ($valorGarantiaSalida != 0) {

            $cliente = Persona::where('cedula', $compromisoModificar->cedula)->first();

            $salida = new Salida;
            $salida->concepto = 'Reintegro de garantía al cliente';
            $salida->valor = $valorGarantiaSalida;
            $salida->nombre_persona = $cliente->name;
            $salida->identificacion = $cliente->cedula;
            $salida->save();
        }


        if ($compromisoModificar->save()) {
            $array_ids_productos = CompromisoProducto::where('id_compromiso', $compromisoModificar->id_compromiso)->get()->pluck('id_producto');
            $productosCompromiso = CompromisoProducto::where('id_compromiso', $compromisoModificar->id_compromiso)->delete();
            

            //vrificar si existen mas compromisos con cada producto de lo contrario poner ese producto en disponible
            if (count($array_ids_productos)) {
                foreach ($array_ids_productos as $id_prod) {
                    $tieneCompromisoProducto = CompromisoProducto::where('id_producto', $id_prod)->get();
                    if (count($tieneCompromisoProducto) == 0) {
                        //es por que ese producto no tiene compromisos entonces poner en disponible
                        $productoModificarEstado = Producto::find($id_prod);
                        $productoModificarEstado->estado = 'Disponible';
                        $productoModificarEstado->save();
                    }
                }
            }

            //si recibo es true hay que entregar recibo sino haga el redirect

            if ($recibo) {
                $this->crearReciboRecargoDanios($compromisoRecibo, $clienteRecibo, $valorDanio, $conceptoDanio, $valorRecargo, $conceptoRecargo);
            } else {
                return redirect('compromiso');
            }
        }
    }

    public function crearReciboRecargoDanios($compromisoRecibo, $clienteRecibo, $valorDanio, $conceptoDanio, $valorRecargo, $conceptoRecargo) {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('facturaRecargosDanios.docx'));

        $my_template->setValue('conceptoDanio', $conceptoDanio);
        $my_template->setValue('valorDanio', $valorDanio);
        $my_template->setValue('conceptoRecargo', $conceptoRecargo);
        $my_template->setValue('valorRecargo', $valorRecargo);

        $total = 0;

        $total = intval($valorDanio) + intval($valorRecargo);

        $my_template->setValue('total', $total);

        if (isset($clienteRecibo->name)) {
            $my_template->setValue('nombreCliente', $clienteRecibo->name);
        } else {
            $my_template->setValue('nombreCliente', '');
        }

        if (isset($clienteRecibo->direccion)) {
            $my_template->setValue('direccionCliente', $clienteRecibo->direccion);
        } else {
            $my_template->setValue('direccionCliente', '');
        }
        if (isset($clienteRecibo->referencia_nombre)) {
            $my_template->setValue('referenciaCliente', $clienteRecibo->referencia_nombre);
        } else {
            $my_template->setValue('referenciaCliente', '');
        }
        if (isset($clienteRecibo->cedula)) {
            $my_template->setValue('cedulaCliente', $clienteRecibo->cedula);
        } else {
            $my_template->setValue('cedulaCliente', '');
        }
        $my_template->setValue('fechaHoy', date('Y-m-d'));

        if (isset($compromisoRecibo->fecha_compromiso)) {
            $my_template->setValue('fechaComp', $compromisoRecibo->fecha_compromiso);
        } else {
            $my_template->setValue('fechaComp', '');
        }



        $my_template->setValue('total', $total);

        if (isset($compromisoRecibo->fecha_devolucion)) {
            $my_template->setValue('fechaDev', $compromisoRecibo->fecha_devolucion);
        } else {
            $my_template->setValue('fechaDev', '');
        }
        if (isset($compromisoRecibo->factura->numero_factura)) {
            $my_template->setValue('facturaNumero', $compromisoRecibo->factura->numero_factura);
        } else {
            $my_template->setValue('facturaNumero', '');
        }

        if (isset($clienteRecibo->celular)) {
            $my_template->setValue('celularCliente', $clienteRecibo->celular);
        } else {
            $my_template->setValue('celularCliente', '');
        }
        if (isset($clienteRecibo->telefono)) {
            $my_template->setValue('telefonoCliente', $clienteRecibo->telefono);
        } else {
            $my_template->setValue('telefonoCliente', '');
        }



        try {
            $my_template->saveAs(public_path('ReciboGeneradoRecargosDanios.docx'));
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="ReciboGeneradoRecargosDanios.docx"');
            readfile(public_path('ReciboGeneradoRecargosDanios.docx'));
            return redirect('compromiso');
        } catch (Exception $e) {
            echo 'Hubo un error, comuníquese con el administrador del sistema';
        }
    }

}
