<?php

namespace App\Http\Controllers;

use App\Services\BatchNumberService;
use Illuminate\Http\Request;
use App\Models\Lot;
use App\Models\Product;

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
        $validatedData['batch_number'] = $this->batchNumberService->generateBatchNumber();

        // Create the lot
        $lot = Lot::create([
            'product_id' => $request->product_id,
            'batch_number' => $validatedData['batch_number'],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'expiration_date' => $request->expiration_date,
        ]);

        // Update the product's total quantity
        $product = Product::findOrFail($request->product_id);
        $product->total_quantity += $request->quantity;
        $product->save();

        return redirect()->route("lot.index", $request->product_id)->with('success', 'Lot added successfully.');
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

        $lot->update($validated);

        return redirect()->route('product.show', $lot->product_id)->with('success', 'Lot updated successfully.');
    }

    /**
     * Delete a lot.
     */
    public function destroy(Lot $lot)
    {
        $productId = $lot->product_id; // Save the product ID for redirection
        $lot->delete(); // Delete the lot

        return redirect()->route('product.show', $productId)->with('success', 'Lot deleted successfully.');
    }
}
