<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LotController extends Controller
{
    public function index()
    {
        $lots = Lot::all();
        return view('lots.create', compact('lots'))->with("success", "Lot added successfully");
    }
    public function store(Request $request)
    {
        $request->validate([
    'batch_number' => 'required|string|unique:lots,batch_number', 
    'product_id' => 'required|exists:products,id',
    'expiration_date' => 'required|date',
    'quantity' => 'required|integer|min:0',
        ]);
        
        $lot = new Lot();
        $lot->product_id = $request->product_id;
        $lot->batch_number = $request->batch_number;      
        $lot->quantity = $request->quantity;
        $lot->expiration_date = $request->expiration_date;
        $lot->save();
        return redirect()->route('lot.index');
    }
}
