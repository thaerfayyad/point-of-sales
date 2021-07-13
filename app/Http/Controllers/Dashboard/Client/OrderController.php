<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class OrderController extends Controller
{
    public function create(Client $client)
    {
        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);

        return view('dashboard.clients.orders.create', compact( 'client', 'categories', 'orders'));

    }
     public function store(Request $request ,Client $client)
     {
        $request->validate([
            'products' => 'required|array',
        ]);

        $this->attach_order($request, $client);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
     }
     private function attach_order($request, $client)
     {
         $order = $client->orders()->create([]);

         $order->products()->attach($request->products);

         $total_price = 0;

         foreach ($request->products as $id => $quantity) {

             $product = Product::FindOrFail($id);
             $total_price += $product->sale_price * $quantity['quantity'];

             $product->update([
                 'stock' => $product->stock - $quantity['quantity']
             ]);

         }//end of foreach

         $order->update([
             'total_price' => $total_price
         ]);

     }
   public function edit(Client $client)
     {
     //
     }

     public function update(Request $request,Client $client , Order $order)
     {
     return $request->all();
     }
     public function destroy(Client $client)
     {
     //
     }
}
