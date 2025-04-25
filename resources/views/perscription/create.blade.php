<div class="flex flex-col lg:flex-row gap-6">
    <!-- Left Side - Form -->
    <div class="lg:w-1/2 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">
            {{ request()->has('edit') ? 'Edit Prescription' : 'Create New Prescription' }}
        </h2>
        
        <form action="{{ route('prescription.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Doctor Name -->
            <div class="space-y-1">
                <label for="doctor_name" class="block text-sm font-medium text-gray-700">Doctor Name</label>
                <input type="text" name="doctor_name" id="doctor_name" value="{{ old('doctor_name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Patient Name -->
            <div class="space-y-1">
                <label for="patient_name" class="block text-sm font-medium text-gray-700">Patient Name</label>
                <input type="text" name="patient_name" id="patient_name" value="{{ old('patient_name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Patient Phone -->
            <div class="space-y-1">
                <label for="patient_phone" class="block text-sm font-medium text-gray-700">Patient Phone</label>
                <input type="text" name="patient_phone" id="patient_phone"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Prescription Date -->
            <div class="space-y-1">
                <label for="prescription_date" class="block text-sm font-medium text-gray-700">Prescription Date</label>
                <input type="date" name="prescription_date" id="prescription_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Duration -->
            <div class="space-y-1">
                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (in days)</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Notes -->
            <div class="space-y-1">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
            </div>

            <!-- Medications -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Medications</label>
                
                <!-- Product Search -->
                <div class="relative mb-3">
                    <input type="text" id="product_search" placeholder="Search products..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <div id="product_results" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"></div>
                </div>
                
                <!-- Selected Medications -->
                <div id="medications" class="space-y-2">
                    <div class="flex items-center gap-2">
                        <select name="medications[0][product_id]" required
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="medications[0][quantity]" placeholder="Qty" min="1" value="1" required
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

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ request()->has('edit') ? 'Update Prescription' : 'Create Prescription' }}
                </button>
            </div>
        </form>
    </div>
