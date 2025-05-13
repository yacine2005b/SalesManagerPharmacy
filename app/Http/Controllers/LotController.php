<?php

namespace App\Http\Controllers;

use App\Services\BatchNumberService;
use Illuminate\Http\Request;
use App\Models\Lot;
use App\Models\Product;
use App\Models\ActivityLog;
use Picqer\Barcode\BarcodeGeneratorPNG;

class LotController extends Controller
{
    protected $batchNumberService;

    public function __construct(BatchNumberService $batchNumberService)
    {
        $this->batchNumberService = $batchNumberService;
    }

    /**
     * Display the product and its lots.
     */
    public function index($productId)
    {
        $product = Product::with('lots')->findOrFail($productId); // Eager load lots
        return view('lots.create', compact('product'));
    }

    /**
     * Store a new lot for a product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'expiration_date' => 'required|date|after:today',
        ]);

        // Generate batch number
        $batchNumber = $this->batchNumberService->generateBatchNumber();


        // Generate a unique barcode based on the batch number
        $generator = new BarcodeGeneratorPNG();
         $barcodeUrl = "https://barcode.tec-it.com/barcode.ashx?data={$batchNumber}&code=Code128&dpi=96";

        // Create the lot
        $lot = Lot::create([
            'product_id' => $request->product_id,
            'batch_number' => $batchNumber,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'expiration_date' => $request->expiration_date,
            'barcode' =>  $barcodeUrl, // Save the barcode
        ]);

        // Update the product's total quantity
        $product = Product::findOrFail($request->product_id);
        $product->total_quantity += $request->quantity;
        $product->save();

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' created a lot for: ' . $product->name,
        ]);

        return redirect()->route("lot.index", $request->product_id)->with('success', 'Lot added successfully with barcode!');
    }

    /**
     * Edit a lot.
     */
    public function editLot(Lot $lot)
    {
        return view('lots.edit', compact('lot'));
    }

    /**
     * Update a lot.
     */
    public function updateLot(Request $request, Lot $lot)
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' edited a lot for: ' . $product->name,
        ]);
        $lot->update($validated);

        return redirect()->route('product.show', $lot->product_id)->with('success', 'Lot updated successfully.');
    }

    /**
     * Delete a lot.
     */
    public function destroy(Lot $lot)
    {
        $productId = $lot->product_id; // Save the product ID for redirection

        $product = $lot->product; 
        $product->total_quantity -= $lot->quantity; 

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' deleted a lot for: ' . $product->name,
        ]);
        $lot->delete(); // Delete the lot

        return redirect()->route('product.show', $productId)->with('success', 'Lot deleted successfully.');
    }

    /**
     * Print the barcode for a lot.
     */
    public function printBarcode($id)
    {
        $lot = Lot::findOrFail($id);

        return view('lots.printBarcode', compact('lot'));
    }
}
