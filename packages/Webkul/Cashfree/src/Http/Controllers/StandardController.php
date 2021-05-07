<?php

namespace Webkul\Cashfree\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Cashfree\Helpers\Ipn;
use Illuminate\Http\Request;
use Webkul\Cashfree\Payment\Standard;
use Illuminate\Support\Facades\Log;
class StandardController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Ipn object
     *
     * @var \Webkul\Cashfree\Helpers\Ipn
     */
    protected $ipnHelper;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Cashfree\Helpers\Ipn  $ipnHelper
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        Ipn $ipnHelper
    )
    {

        $this->orderRepository = $orderRepository;

        $this->ipnHelper = $ipnHelper;
    }

    /**
     * Redirects to the Cashfree.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());  
        return view('cashfree::standard-redirect');
    }

    /**
     * Cancel payment from Cashfree.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel($status)
    {
        $order = $this->orderRepository->findOneByField(['cart_id' => $status['orderId']]);
        $this->orderRepository->cancel($order->id);
        Cart::deActivateCart();
        session()->flash('error', 'Something Went Wrong , '.$status['txMsg']);

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Success payment
     *
     * @return \Illuminate\Http\Response
     */
    public function success($callback)
    {      
        $order = $this->orderRepository->findOneByField(['cart_id' => $callback['orderId']]);
        session()->flash('order', $order);      
        Cart::deActivateCart();

        return redirect()->route('shop.checkout.success');
    }

    /**
     * Success payment
     *
     * @return \Illuminate\Http\Response
     */
    public function returnFromCashfree(Request $request)
    {

        Log::info("got data from cash free : ",$request->all());
        // dd($request->all());
        $callback = $request->all();
        if($this->verifyData($callback)){
            if($callback['txStatus'] === "SUCCESS"){
                return $this->success($callback);
            }else{
                return $this->cancel($callback);
            }
        }else{
            return $this->cancel();
        }
   
    }

    /**
     * cashfree Ipn listener
     *
     * @return \Illuminate\Http\Response
     */
    public function notify()
    {
        $this->ipnHelper->processIpn(request()->all());
    }

    public function verifyData($data){
        $standard = new Standard();
        return $standard->verifySignature($data);
    }
}