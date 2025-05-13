<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Prescription;
use App\Models\PrescriptionMedication;
class PerscriptionController extends Controller
{
    public function index()
    {
       $products = Product::all();
       $prescriptions = Prescription::all();

       return view('perscription', compact('products', 'prescriptions'));
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'doctor_name' => 'required|string|max:50',
            'patient_name' => 'required|string|max:50',
            'patient_phone' => 'nullable|string|max:15',
            'notes' => 'nullable|string',
            'duration' => 'nullable|integer|min:1', // Validate duration
            'prescription_date' => 'nullable|date', // Validate prescription date
            'medications' => 'required|array',
            'medications.*.product_id' => 'required|exists:products,id',
            'medications.*.quantity' => 'required|integer|min:1',
        ]);

        //dd($request->all());

        // Create a new prescription with default status as 'pending'
        $prescription = Prescription::create([
            'doctor_name' => $request->doctor_name,
            'patient_name' => $request->patient_name,
            'patient_phone' => $request->patient_phone,
            'notes' => $request->notes,
            'duration' => $request->duration, // Save duration
            'prescription_date' => $request->prescription_date , // Save prescription date or default to today
            'status' => 'pending', // Default status
        ]);
       


        // Attach medications to the prescription
        foreach ($request->medications as $medication) {
           PrescriptionMedication::create([
                'prescription_id' => $prescription->id,
                'product_id' => $medication['product_id'],
                'quantity' => $medication['quantity'],
            ]);
        }

        return redirect()->route('prescription.index')->with('success', 'Prescription created successfully.');
    }

    public function show($id)
    {
        $prescription = Prescription::with('medications.product')->findOrFail($id);
        return view('perscription.show', compact('prescription'));
    }
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete();

        return redirect()->route('prescription.index')->with('success', 'Prescription deleted successfully.');
    }
}
