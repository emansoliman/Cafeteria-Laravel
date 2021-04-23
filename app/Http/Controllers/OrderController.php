<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderProduct;

class OrderController extends Controller
{
    public function inject_order($id, $products)
    {
        $order_products = array();
        foreach ($products as $product) {
            $product['order_id'] = $id;
            $order_products[] = $product;
        }
        return $order_products;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = $request->input('order');
        $newOrder = Order::create($order);

        $products = $request->input('products');

        $order_products = array();
        foreach ($products as $product) {
            $product['order_id'] = $newOrder->id;
            $order_products[] = $product;
        }
        $newProducts = OrderProduct::insert($order_products);
        return $newProducts;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function orderPrice($id){
        $total_price = Order::find($id)->getTotalOrderPrice();
        return $total_price;
    }

    public function userTotalPrice($userId, $dateFrom, $dateTo){
        $returnedObject=[];
        $selectedUser = User::find($userId);
        $selectedUserOrders = $selectedUser->orders
        ->where('created_at','>',$dateFrom)
        ->where('created_at','<',$dateTo);
        if(count($selectedUserOrders)!==0){
            $totalUserOrdersPrice = 0;
            foreach($selectedUserOrders as $selectedUserOrder){
                $totalUserOrdersPrice += $selectedUserOrder->getTotalOrderPrice();         
            }
            return ['id'=>$selectedUser->id, 'user'=>$selectedUser->name, 'total_orders_price'=>$totalUserOrdersPrice];
        }
        return [];
    }

    public function searchOrderUsersByDate(Request $request){
        $userId = $request->selected_user_id;
        if($userId > 0){
            return $this->userTotalPrice($userId, $request->date_from, $request->date_to);
        }
        else{
            $allUsers = User::all();
            $usersPrices = [];
            foreach($allUsers as $user){
                $oneUserPrice = $this->userTotalPrice($user->id, $request->date_from, $request->date_to);
                array_push($usersPrices, $oneUserPrice);
            }
            return $usersPrices;
        }
    }
}
