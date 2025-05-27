@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
 

    <div class="grid grid-cols-3 gap-6">
      <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Point of Sale</h1>
    
        <!-- Start Sale Session Button -->
        
        @if(!$activeSession)
            <form action="{{ route('sales.session.start') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Start Sale Session
                </button>
            </form>
        @else
            <!-- End Sale Session Button -->
            <form action="{{ route('sales.session.end', $activeSession->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    End Sale Session
                </button>
            </form>
        @endif
    </div>
    </div>
</div>
@endsection
