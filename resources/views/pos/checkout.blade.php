@include('cart.header', ['isInsuranceSale' => $isInsuranceSale ?? false])

    <!-- Cart Items -->
    @include('cart.cartItems', ['isInsuranceSale' => $isInsuranceSale ?? false])
    @if (!empty($cart))
        <!-- Cart Footer -->
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <!-- Subtotal -->
            @include('cart.total', ['isInsuranceSale' => $isInsuranceSale ?? false])

            <!-- Insurance Form (if applicable) -->
           @include('cart.insuranceForm', ['isInsuranceSale' => $isInsuranceSale ?? false])
            <!-- Checkout Button -->
           @include('cart.checkoutBtn', ['isInsuranceSale' => $isInsuranceSale ?? false])

  
        </div>
    @endif
</div>