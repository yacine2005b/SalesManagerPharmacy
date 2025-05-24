<div class="container mx-auto p-6">
    <!-- Sale Type Navigation -->
   <div class="bg-white shadow-sm mb-6 rounded-lg overflow-hidden">
    <div class="flex border-b border-gray-200">
        <a href="{{route('pos.normal')}}" 
           class="px-6 py-3 text-sm font-medium {{ request()->routeIs('pos.normal') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-colors duration-200">
            Normal Sale
        </a>
        <a href="{{route('pos.insurance')}}" 
           class="px-6 py-3 text-sm font-medium {{ request()->routeIs('pos.insurance') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-colors duration-200">
            Insurance Sale
        </a>
        <a href="{{route('pos.prescription')}}" 
           class="px-6 py-3 text-sm font-medium {{ request()->routeIs('pos.prescription') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-colors duration-200">
            Prescription Sale
        </a>
    </div>
</div>