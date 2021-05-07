<?php

namespace Webkul\Cashfree\Helpers;

use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Cashfree\Payment\Standard;
use Illuminate\Support\Facades\Log;

class Ipn
{
    /**
     * Ipn post data
     *
     * @var array
     */
    protected $post;

    /**
     * Order object
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    protected $order;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * InvoiceRepository object
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * This function process the ipn sent from cashfree end
     *
     * @param  array  $post
     * @return null|void|\Exception
     */
    public function processIpn($post)
    {           
        $post['signature'] = str_replace(" ","+",$post['signature']);

        Log::debug("got data from notify : " , $post);
        $this->post = $post;
        // dd($this->post);
        if (! $this->postBack()) {
            Log::debug("signature invalid");
            return;
        }

        try {
            $this->getOrder();
            $this->processOrder();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Load order via ipn invoice id
     *
     * @return void
     */
    protected function getOrder()
    {
        if (empty($this->order)) {
            $this->order = $this->orderRepository->findOneByField(['cart_id' => $this->post['orderId']]);
            // dd($this->order);
        }
    }

    /**
     * Process order and create invoice
     *
     * @return void
     */
    protected function processOrder()
    {
        if ($this->post['txStatus'] == 'SUCCESS') {
            if ($this->post['orderAmount'] != $this->order->sub_total) {
            } else {
                $this->orderRepository->update(['status' => 'processing'], $this->order->id);
                if ($this->order->canInvoice()) {
                    $this->invoiceRepository->create($this->prepareInvoiceData());
                }
            }
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = [
            "order_id" => $this->order->id,
        ];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Post back to cashfree to check whether this request is a valid one
     *
     * @return bool
     */
    protected function postBack()
    {
        $standard = new Standard();
        return $standard->verifySignature($this->post);
    }
}