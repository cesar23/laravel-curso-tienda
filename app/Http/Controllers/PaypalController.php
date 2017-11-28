<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use App\Order;
use App\OrderItem;

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function postPayment(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 3000);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = array();
        $subtotal = 0;
        $cart = \Session::get('cart');//obtenemos todos los productos del carrito
        $currency = 'USD'; //

        foreach($cart as $producto){
            //por cada producto creamos un Item en paypal
            $item = new Item();
            $item->setName($producto->name)
                ->setCurrency($currency)
                ->setDescription($producto->extract)
                ->setQuantity($producto->quantity)
                ->setPrice($producto->price);

            $items[] = $item;
            $subtotal += $producto->quantity * $producto->price;
        }
        // Seteamos los items
        $item_list = new ItemList();
        $item_list->setItems($items);

        //--Esto nos va  servir el costo para el envio
        $details = new Details();
        $details->setSubtotal($subtotal)
            ->setShipping(100);

        //--Calculo el Total  a Pagar
        $total = $subtotal + 100;

        //--Configuramos el total a pagar
        $amount = new Amount();
        $amount->setCurrency($currency) //la  moneda
            ->setTotal($total)  //totala  pagar
            ->setDetails($details);

        //-- Comfiguramos la transaccion
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Pedido de prueba en mi Laravel App Store');

        //--rutas a redirir  a<l usurio si se  realiza  el pago
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(\URL::route('payment.status'))
            ->setCancelUrl(\URL::route('payment.status'));

        //-- Aqui se realizara el pago a paypal
        $payment = new Payment();
        $payment->setIntent('Sale') //venta  directa
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            //-- aqui es dodne se  hace la coneccion el la api con rest
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Ups! Algo salió mal');
            }
        }

        //--  Cogemos la  informacion que  paypal nos trae
        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        \Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
            // redirect to paypal
            return \Redirect::away($redirect_url); //permite redireccionar a una  Url externa
        }

        return \Redirect::route('cart-show')
            ->with('error', 'Ups! Error desconocido.');

    }


    /*
     * Metodo del cual paypal nos da  respuesta
     */
    public function getPaymentStatus(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 3000);



        // Get the payment ID before session clear (Obtenga la identificación de pago antes de la sesión clara)
        $payment_id = \Session::get('paypal_payment_id');

        // clear the session payment ID
        \Session::forget('paypal_payment_id');

        //Parametros que nos trae paypla
        $payerId =$request->input('PayerID');
        $token = $request->input('token');

        //if (empty(\Input::get('PayerID')) || empty(\Input::get('token'))) {
        if (empty($payerId) || empty($token)) {
            return \Redirect::route('home')
                ->with('message', 'Hubo un problema al intentar pagar con Paypal');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        // El objeto PaymentExecution incluye información necesaria
        // para ejecutar un pago de cuenta de PayPal.
        // El payer_id se agrega a los parámetros de consulta de solicitud
        // cuando el usuario es redirigido desde paypal a su sitio

        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        //Execute the payment (AQUI REALEMTENE SE REALIZA EL PAGO)
        $result = $payment->execute($execution, $this->_api_context);

        //echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later

        if ($result->getState() == 'approved') { // payment made
            // Registrar el pedido --- ok
            // Registrar el Detalle del pedido  --- ok
            // Eliminar carrito
            // Enviar correo a user
            // Enviar correo a admin
            // Redireccionar

            //Guardamos la  Informacion del Pedido
            $this->saveOrder(\Session::get('cart'));

            \Session::forget('cart');//eliminamos el carrito

            return \Redirect::route('home')
                ->with('message', 'Compra realizada de forma correcta');
        }
        return \Redirect::route('home')
            ->with('message', 'La compra fue cancelada');
    }


    private function saveOrder($cart)
    {
        $subtotal = 0;
        foreach($cart as $item){
            $subtotal += $item->price * $item->quantity;
        }

        $order = Order::create([
            'subtotal' => $subtotal,
            'shipping' => 100,
            'user_id' => \Auth::user()->id
        ]);

        foreach($cart as $item){
            $this->saveOrderItem($item, $order->id);
        }
    }

    private function saveOrderItem($item, $order_id)
    {
        OrderItem::create([
            'quantity' => $item->quantity,
            'price' => $item->price,
            'product_id' => $item->id,
            'order_id' => $order_id
        ]);
    }
}
