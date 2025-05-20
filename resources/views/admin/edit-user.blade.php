@extends('layout.layout')
@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Modifier l'utilisateur</h2>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-5">
            <label class="block text-gray-700 text-sm font-medium mb-2" for="name">Nom</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 @error('name') border-red-500 @enderror" required>
            @error('name')
                <span class="text-red-500 text-xs italic mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5">
            <label class="block text-gray-700 text-sm font-medium mb-2" for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 @error('email') border-red-500 @enderror" required>
            @error('email')
                <span class="text-red-500 text-xs italic mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2" for="role">Rôle</label>
            <select name="role" id="role"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 @error('role') border-red-500 @enderror" required>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pharmacist" {{ old('role', $user->role) == 'pharmacist' ? 'selected' : '' }}>Pharmacien</option>
                <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Caissier</option>
            </select>
            @error('role')
                <span class="text-red-500 text-xs italic mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Retour</a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection