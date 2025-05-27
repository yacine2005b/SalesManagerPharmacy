<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
    <h3 class="text-lg font-medium text-gray-800 mb-4">Insurance Details</h3>
    
    <form action="{{ route('cart.saveInsurance') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Coverage Type -->
        <div class="space-y-1">
            <label for="coverage_type" class="block text-sm font-medium text-gray-700">Coverage Type *</label>
            <select name="coverage_type" id="coverage_type"
                class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm"
                {{ isset($shifaCard) ? 'disabled' : '' }}>
                <option value="">Select coverage type</option>
                <option value="full"
                    {{ (isset($shifaCard) && $shifaCard->coverage_type === 'full') || (session('coverage_type') === 'full' && !isset($shifaCard)) ? 'selected' : '' }}>
                    Full Coverage
                </option>
                <option value="partial"
                    {{ (isset($shifaCard) && $shifaCard->coverage_type === 'partial') || (session('coverage_type') === 'partial' && !isset($shifaCard)) ? 'selected' : '' }}>
                    Partial Coverage
                </option>
            </select>
            @if(isset($shifaCard))
                <input type="hidden" name="coverage_type" value="{{ $shifaCard->coverage_type }}">
            @endif
        </div>

        <!-- Shifa Card Number -->
        <div>
            <label for="shifa_card_number" class="block text-sm font-medium text-gray-700">Shifa Card Number</label>
            <input type="text" name="shifa_card_number" id="shifa_card_number"
                   value="{{ isset($shifaCard) ? $shifaCard->card_number : old('shifa_card_number', session('shifa_card_number')) }}"
                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                   {{ isset($shifaCard) ? 'readonly' : '' }}>
        </div>

        <!-- Issue Date -->
        <div>
            <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
            <input type="date" name="issue_date" id="issue_date"
                   value="{{ isset($shifaCard) ? $shifaCard->issue_date : old('issue_date', session('issue_date')) }}"
                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                   {{ isset($shifaCard) ? 'readonly' : '' }}>
        </div>

        <!-- Expiry Date -->
        <div>
            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date"
                   value="{{ isset($shifaCard) ? $shifaCard->expiry_date : old('expiry_date', session('expiry_date')) }}"
                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                   {{ isset($shifaCard) ? 'readonly' : '' }}>
        </div>

        <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
            Save Details
        </button>
    </form>
</div>
