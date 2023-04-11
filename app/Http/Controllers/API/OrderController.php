<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use Validator;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
   
class OrderController extends BaseController
{
    public function payments(Request $request): JsonResponse
    {
        return $this->sendResponse([], 'Payment successfully.');
    }

    public function orders(Request $request): JsonResponse
    {
        $all = $request->all();
        $dataOrder = [
            'subtotal' => $all['subtotal'],
            'deliveryFee' => $all['deliveryFee'],
            'total' => $all['total'],
            'ref' => rand(10000000,90000000)
        ];

        $orderDetail = [];
        foreach ($all['items'] as $item) {
            $dataItem['product_id'] = $item['product']['id'];
            $dataItem['name'] = $item['product']['name'];
            $dataItem['image'] = $item['product']['image'];
            $dataItem['price'] = $item['product']['price'];
            $dataItem['quantity'] = $item['quantity'];

            $orderDetail[] = $dataItem;
        }

        $dataCustomer = [
            'email' => $all['customer']['email'],
            'name' => $all['customer']['name'],
            'address' => $all['customer']['address'],
        ];

        $customer = Customer::firstOrCreate($dataCustomer);
        $dataOrder['customer_id'] = $customer->id;
        $order = Order::create($dataOrder);
        $order->orderDetails()->createMany($orderDetail);

        return $this->sendResponse(new OrderResource($order), 'Orders successfully.');
    }

    public function orderDetail($id = null)
    {
        $order = Order::find($id);
        if ($order) {
            return $this->sendResponse(new OrderResource($order), 'Order Detail');
        }

        return $this->sendError('Order not found', [], 200);
    }
}
