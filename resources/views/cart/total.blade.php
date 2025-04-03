<div class="flex justify-between mb-3">
    <span class="text-sm font-medium text-gray-600">Subtotal</span>
    <span class="text-base font-medium text-gray-900">
        {{ number_format(collect($cart)->sum(fn($item) => ($item['price'] * $item['quantity']) - ($isInsuranceSale ? ($item['discount'] ?? 0) : 0)), 2) }}DA
    </span>
</div>