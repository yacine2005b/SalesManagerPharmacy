<?php

namespace App\Http\Controllers;

use App\Services\BatchNumberService;
use Illuminate\Http\Request;
use App\Models\Lot;
use App\Models\Product;
use App\Models\ActivityLog;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        // Get the product
        $product = Product::findOrFail($request->product_id);

        // Generate batch number using product name
        $batchNumber = $this->batchNumberService->generateBatchNumber($product->name);

        // Generate the barcode image using the API
        $barcodeUrl = "https://barcode.tec-it.com/barcode.ashx?data={$batchNumber}&code=Code128&dpi=96";
        $barcodeImage = file_get_contents($barcodeUrl);

        // Save the barcode image to storage/app/public/barcodes
        $barcodeFileName = 'barcodes/' . Str::random(10) . '_' . $batchNumber . '.png';
        Storage::disk('public')->put($barcodeFileName, $barcodeImage);

        // Create the lot and save the barcode file path
        $lot = Lot::create([
            'product_id' => $request->product_id,
            'batch_number' => $batchNumber,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'expiration_date' => $request->expiration_date,
            'barcode' => $barcodeFileName, // Save the file path
        ]);

        // Update the product's total quantity
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

        // Calculate the difference in quantity
        $oldQuantity = $lot->quantity;
        $newQuantity = $validated['quantity'];
        $quantityDiff = $newQuantity - $oldQuantity;

        // Update the lot
        $lot->update($validated);

        // Update the product's total quantity
        $product = $lot->product;
        $product->total_quantity += $quantityDiff;
        $product->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' edited a lot for: ' . $product->name,
        ]);

        return redirect()->route('product.show', $lot->product_id)->with('success', 'Lot updated successfully.');
    }

    /**
     * Delete a lot.
     */
    public function destroy(Lot $lot)
    {
        $productId = $lot->product_id; 
        
        // Update product total quantity and save
        $product = $lot->product;
        $product->total_quantity -= $lot->quantity;
        $product->save();

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
