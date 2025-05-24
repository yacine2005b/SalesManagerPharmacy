<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Prescription;
use App\Models\PrescriptionMedication;
use App\Models\Patient;
use App\Models\Doctor;
class PerscriptionController extends Controller
{
    public function index()
    {
       $products = Product::all();
       $prescriptions = Prescription::all();
       $patients = Patient::all();
       $doctors = Doctor::all();

       return view('perscription', compact('products', 'prescriptions', 'patients', 'doctors'));
    }
    public function store(Request $request)
    {
        // Validate common fields
        $request->validate([
            'duration' => 'nullable|integer|min:1',
            'prescription_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'medications' => 'required|array',
            'medications.*.product_id' => 'required|exists:products,id',
            'medications.*.quantity' => 'required|integer|min:1',
        ]);

        // Handle patient
        if ($request->patient_id === 'new') {
            $request->validate([
                'new_patient_name' => 'required|string|max:50',
                'new_patient_phone' => 'nullable|string|max:15',
            ]);
            $patient =Patient::create([
                'name' => $request->new_patient_name,
                'phone' => $request->new_patient_phone,
            ]);
            $patient_id = $patient->id;
        } elseif ($request->patient_id) {
            // Existing patient selected
            $patient_id = $request->patient_id;
        } else {
            // No patient selected
            return back()->withErrors(['patient_id' => 'Please select or enter a patient.'])->withInput();
        }

        // Handle doctor
        if ($request->doctor_id === 'new') {
            $request->validate([
                'new_doctor_name' => 'required|string|max:50',
                'new_doctor_phone' => 'nullable|string|max:15',
            ]);
            $doctor =Doctor::create([
                'name' => $request->new_doctor_name,
                'phone' => $request->new_doctor_phone,
            ]);
            $doctor_id = $doctor->id;
        } elseif ($request->doctor_id) {
            // Existing doctor selected
            $doctor_id = $request->doctor_id;
        } else {
            // No doctor selected
            return back()->withErrors(['doctor_id' => 'Please select or enter a doctor.'])->withInput();
        }

        // Create the prescription
        $prescription = Prescription::create([
            'doctor_id' => $doctor_id,
            'patient_id' => $patient_id,
            'duration' => $request->duration,
            'prescription_date' => $request->prescription_date,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Save medications
        foreach ($request->medications as $med) {
            $prescription->medications()->create([
                'product_id' => $med['product_id'],
                'quantity' => $med['quantity'],
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
