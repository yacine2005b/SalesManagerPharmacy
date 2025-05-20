@extends('layout.layout')

@section('content')
<div class="flex justify-center bg-gray-50 p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Add New User</h1>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                    <input id="name" name="name" type="text" required
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="John Doe">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="your@email.com">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">User Role</label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Select a role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="pharmacist" {{ old('role') == 'pharmacist' ? 'selected' : '' }}>Pharmacist</option>
                        <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    </select>
                    @error('role')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••">
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition duration-150">
                        Register Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
