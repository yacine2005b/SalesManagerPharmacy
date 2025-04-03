@if ($isInsuranceSale ?? false)
<form action="{{ route('cart.saveInsurance') }}" method="POST" class="mb-4 space-y-3">
    @csrf
    <div>
        <label for="coverage_type" class="block text-xs font-medium text-gray-700 mb-1">Coverage Type</label>
        <select name="coverage_type" id="coverage_type" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select coverage</option>
            <option value="full" {{ session('coverage_type') === 'full' ? 'selected' : '' }}>Full Coverage</option>
            <option value="partial" {{ session('coverage_type') === 'partial' ? 'selected' : '' }}>Partial Coverage</option>
        </select>
    </div>

    <div>
        <label for="shifa_card_number" class="block text-xs font-medium text-gray-700 mb-1">Shifa Card</label>
        <input type="text" name="shifa_card_number" id="shifa_card_number" 
               value="{{ session('shifa_card_number') }}" 
               class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
               placeholder="Card number">
    </div>

    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
        Save Details
    </button>
</form>
@endif
