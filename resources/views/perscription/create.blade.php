<form action="{{route('prescription.store')}}" method="POST" class="flex flex-col items-center justify-center h-full" id="prescriptionForm">
    @csrf
    <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-4xl overflow-y-auto">
        <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2 sticky top-0 bg-white z-10">
            {{ request()->has('edit') ? 'Edit Prescription' : 'Create New Prescription' }}
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Patient Selection -->
            <div class="space-y-1">
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient *</label>
                <select name="patient_id" id="patient_id" onchange="togglePatientFields()" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select existing patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                    <option value="new">+ New patient</option>
                </select>
                <div id="new_patient_fields" class="mt-2 space-y-2 hidden">
                    <input type="text" name="new_patient_name" placeholder="Patient name (required)" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           id="new_patient_name">
                    <input type="text" name="new_patient_phone" placeholder="Patient phone (optional)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Doctor Selection -->
            <div class="space-y-1">
                <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor *</label>
                <select name="doctor_id" id="doctor_id" onchange="toggleDoctorFields()" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select existing doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                    <option value="new">+ New doctor</option>
                </select>
                <div id="new_doctor_fields" class="mt-2 space-y-2 hidden">
                    <input type="text" name="new_doctor_name" placeholder="Doctor name (required)" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           id="new_doctor_name">
                    <input type="text" name="new_doctor_phone" placeholder="Doctor phone (optional)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Prescription Date -->
         <div class="space-y-1">
    <label for="prescription_date" class="block text-sm font-medium text-gray-700">Prescription Date *</label>
    <input type="date" name="prescription_date" id="prescription_date" required
           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
           max="{{ \Carbon\Carbon::today()->toDateString() }}">
</div>

            <!-- Duration -->
            <div class="space-y-1">
                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (days) *</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <!-- Notes -->
        <div class="mt-4 space-y-1">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (optional)</label>
            <textarea name="notes" id="notes" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
        </div>

        <!-- Medications -->
        <div class="mt-6 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Medications *</label>
            
            <!-- Product Search -->
            <div class="relative mb-3">
                <input type="text" id="product_search" placeholder="Search products..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <div id="product_results" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"></div>
            </div>
            
            <!-- Selected Medications -->
            <div id="medications" class="space-y-3">
                <div class="flex items-center gap-2">
                    <select name="medications[0][product_id]" required
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Product *</option>
                        @foreach($products as $product)
                            @if($product->total_quantity > 0)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input type="number" name="medications[0][quantity]" placeholder="Qty *" min="1" value="1" required
                           class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" onclick="removeMedication(this)" class="text-red-500 hover:text-red-700 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <button type="button" onclick="addMedication()" class="mt-2 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Medication
            </button>
        </div>

        <div class="mt-6">
            <button type="submit" id="submitBtn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ request()->has('edit') ? 'Update Prescription' : 'Create Prescription' }}
            </button>
        </div>
    </div>
</form>

<script>
function togglePatientFields() {
    const patientFields = document.getElementById('new_patient_fields');
    const patientSelect = document.getElementById('patient_id');
    const newPatientName = document.getElementById('new_patient_name');
    
    if(patientSelect.value === 'new') {
        patientFields.classList.remove('hidden');
        newPatientName.required = true;
    } else {
        patientFields.classList.add('hidden');
        newPatientName.required = false;
    }
}

function toggleDoctorFields() {
    const doctorFields = document.getElementById('new_doctor_fields');
    const doctorSelect = document.getElementById('doctor_id');
    const newDoctorName = document.getElementById('new_doctor_name');
    
    if(doctorSelect.value === 'new') {
        doctorFields.classList.remove('hidden');
        newDoctorName.required = true;
    } else {
        doctorFields.classList.add('hidden');
        newDoctorName.required = false;
    }
}

// Form validation before submission
document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
    const patientId = document.getElementById('patient_id').value;
    const doctorId = document.getElementById('doctor_id').value;
    
    if(patientId === 'new' && !document.getElementById('new_patient_name').value) {
        e.preventDefault();
        alert('Please enter patient name');
        return false;
    }
    
    if(doctorId === 'new' && !document.getElementById('new_doctor_name').value) {
        e.preventDefault();
        alert('Please enter doctor name');
        return false;
    }
    
    return true;
});
</script>