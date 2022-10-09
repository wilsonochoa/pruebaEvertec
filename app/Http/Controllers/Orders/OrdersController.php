<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Controllers\Gst\GstOrders;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{

    public function __construct(Utils $Utils, GstOrders $GstOrders)
    {
        $this->utils = $Utils;
        $this->gstOrders = $GstOrders;
        $this->states = [
            'CREATED' => 1,
            'REJECTED' => 2,
            'APPROVED' => 3,
            'PENDING' => 4
        ];
    }

    /**
     * Vista de productos a comprar
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('orders.index');
    }

    /**
     * Listado de ordenes generadas
     *
     * @return \Illuminate\Http\Response
     */
    public function lstOrder()
    {
        $orders = Orders::orderBy('id', 'desc')->paginate(5);
        $state = Orders::statesTra;
        return view('orders.lstOrder', compact('orders', 'state'));
    }

    /**
     * Vista donde se encuentra el formulario de creacion de la orden
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createOrder($id = null)
    {
        $order = false;
        if($id !== null){
            $order = Orders::find($id);
        }

        return view('orders.create', compact('order'));
    }

    /**
     * @param Request $request
     * Se procesa el formulario del create con sus respectivas validaciones si viene con id se actualizara el registro
     * de la tabla users sino viene con el id se procede a crear los registros del usuario y de la orden
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createProcess(Request $request)
    {
        $messages = [
            'name.required' => 'El campo nombre es requerido.',
            'mobile.required' => 'El campo celular es requerido',
            'email.required' => 'El campo email es requerido.',
            'name.max' => 'El campo nombre supera el número de caracteres permitido.',
            'mobile.max' => 'El campo celular supera el número de caracteres permitido.',
            'email.max' => 'El campo email supera el número de caracteres permitido..'
        ];
        $data = $request->validate([
            'name' => 'required|max:80',
            'mobile' => 'required|max:40',
            'email' => 'required|max:120',
        ], $messages);

        if(!empty($request->id_order) && $request->id_order != ''){
            $order = Orders::find($request->id_order);
            if (!$this->gstOrders->updateUser($data, $order->id)) {
                return redirect()->route('order.create')->withErrors(['errors' => 'Error al crear al usuario']);
            }
        }else{
            DB::beginTransaction();
            if (!$insertUser = $this->gstOrders->insertUser($data)) {
                DB::rollBack();
                return redirect()->route('order.create')->withErrors(['errors' => 'Error al crear al usuario']);
            }

            if (!$order = $this->gstOrders->insertOrder($insertUser->id)) {
                DB::rollBack();
                return redirect()->route('order.create')->withErrors(['errors' => 'Error al crear la orden']);
            }
            DB::commit();
        }
        return redirect()->route('order.preview',['order' => $order->id ] );
    }

    /**
     * Vista donde se visualiza un resumen de la orden
     *
     * @return \Illuminate\Http\Response
     */
    public function previewOrder($id_order)
    {
        $order = Orders::find($id_order);
        return view('orders.preview', compact('order'));
    }

    /**
     * @param Request $request
     * Se encarga de crear la sesion de place to pay
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function previewProcess(Request $request)
    {
        $messages = [
            'id_orden.required' => 'El id es obligatorio.'
        ];
        $data = $request->validate([
            'id_orden' => 'required'
        ], $messages);

        $order = Orders::find($data['id_orden']);

        $responseWs = $this->utils->createSessionPlacetoPay($order->id);

        if ($responseWs['status']['status'] === 'OK' && $responseWs['status']['reason'] === 'PC') {
            $this->gstOrders->updateOrder($order->id, 1, $responseWs['requestId'], $responseWs['processUrl']);
            return redirect($responseWs['processUrl']);
        } else {
            return redirect()->route('preview')->withErrors(['errors' => 'Ocurrio un error al consumir el servicio, por favor intentar mas tarde']);
        }
    }

    /**
     * @param $id
     * Vista donde se visualiza el estado y el detalle de la orden
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewStateOrden($id)
    {
        $statesTra = [
            'CREATED' => 'Creada',
            'REJECTED' => 'Rechazada',
            'APPROVED' => 'Pagada',
            'PENDING' => 'Pendiente'
        ];
        $order = Orders::find($id);
        if($order->id_request !== null){
            $response = $this->utils->getRequestInformation($order->id_request);
            $this->gstOrders->updateOrder($order->id, $this->states[$response['status']['status']]);
            return view('orders.stateOrden', compact('response', 'statesTra', 'order'));
        }else{

            return view('orders.preview', compact('order'));
        }
    }

    /**
     * @param $id
     * Se reintenta el pago en caso tal de que este rechazado
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function retryPayOrder($id){
        $order = Orders::find($id);
        $responseWs = $this->utils->createSessionPlacetoPay($order->id);

        if ($responseWs['status']['status'] === 'OK' && $responseWs['status']['reason'] === 'PC') {
            $this->gstOrders->updateOrder($order->id, 1, $responseWs['requestId'], $responseWs['processUrl']);
            return redirect($responseWs['processUrl']);
        } else {
            return redirect()->route('preview')->withErrors(['errors' => 'Ocurrio un error al consumir el servicio, por favor intentar mas tarde']);
        }

    }


}
