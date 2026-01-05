@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">School Aanmelden</h1>
            <p class="text-gray-600">Registreer uw school voor deelname aan het toernooi</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('schools.register') }}" class="bg-white rounded-lg shadow-lg p-8">
            @csrf

            <!-- Schoolnaam -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Schoolnaam
                </label>
                <input 
                    type="text" 
                    id="name"
                    name="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Voer de naam van uw school in"
                    required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Persoon -->
            <div class="mb-6">
                <label for="contact_person" class="block text-sm font-semibold text-gray-700 mb-2">
                    Contact Persoon
                </label>
                <input 
                    type="text" 
                    id="contact_person"
                    name="contact_person" 
                    value="{{ old('contact_person') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Voer de naam van de contactpersoon in"
                    required>
                @error('contact_person')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- E-mailadres -->
            <div class="mb-8">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    E-mailadres
                </label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Voer een geldig e-mailadres in"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-black font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Aanmelden
                </button>
                <a 
                    href="{{ route('home') }}" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200 text-center">
                    Annuleren
                </a>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">ℹ️ Informatie</h3>
            <p class="text-sm text-blue-700">
                Nadat u uw school heeft aangemeld, zal de beheerder deze beoordelen en goedkeuren. 
                U ontvangt een bevestigingsmail zodra uw school is geaccepteerd.
            </p>
        </div>
    </div>
</div>
@endsection
