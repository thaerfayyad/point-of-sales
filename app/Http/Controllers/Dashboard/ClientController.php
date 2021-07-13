<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Client::when($request->search, function($q) use ($request){
            return $q->where('name' , 'like' ,'%' . $request->search . '%')
            ->orwhere('phone' , 'like' ,'%' . $request->search .'%')
            ->orwhere('address' , 'like' ,'%' . $request->search .'%');

        })->latest()->paginate(5);

        return view('dashboard.clients.index' ,compact('clients'));
    }

    public function create()
    {
        return view('dashboard.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|array|min:1',
            'phone.0' => 'required',
            'address' => 'required',
        ]);
        $request_data = $request->all();
        $request_data['phone'] = array_filter($request->phone);
        Client::create($request_data);
        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.clients.index');

    }


    public function show($id)
    {
        //
    }

    public function edit(Client $client)
    {
        return view('dashboard.clients.edit',compact('client'));
    } //


    public function update(Request $request,Client $client)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|array|min:1',
            'phone.0' => 'required',
            'address' => 'required',
        ]);

        $request_data = $request->all();
        $request_data['phone'] = array_filter($request->phone);
        $client->update($request->except('_token'));
        session()->flash('success',__('site.updated_successfully'));
        return redirect()->route('dashboard.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client )
    {
        $client->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.clients.index');
    }
}
