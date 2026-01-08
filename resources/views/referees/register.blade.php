@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Scheidsrechter Aanmelden</h1>
            <p class="text-gray-600">Meld u aan als scheidsrechter voor het schoolvoetbal toernooi</p>
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
        <form method="POST" action="{{ route('referees.register') }}" class="bg-white rounded-lg shadow-lg p-8">
            @csrf

            <!-- Naam -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Volledige Naam *
                </label>
                <input 
                    type="text" 
                    id="name"
                    name="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Voer uw volledige naam in"
                    required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    E-mailadres *
                </label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="voer uw e-mailadres in"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type Scheidsrechter -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                    Type Scheidsrechter *
                </label>
                <select 
                    id="type"
                    name="type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                    <option value="">-- Selecteer een type --</option>
                    <option value="junior" @selected(old('type') === 'junior')>Junior (jong, startend)</option>
                    <option value="senior" @selected(old('type') === 'senior')>Senior (ervaren)</option>
                    <option value="professional" @selected(old('type') === 'professional')>Professional (officieel)</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telefoonnummer -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                    Telefoonnummer
                </label>
                <input 
                    type="tel" 
                    id="phone"
                    name="phone" 
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="06-12345678"
                    maxlength="20">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ervaring in jaren -->
            <div class="mb-6">
                <label for="experience" class="block text-sm font-semibold text-gray-700 mb-2">
                    Jaren Ervaring als Scheidsrechter
                </label>
                <input 
                    type="number" 
                    id="experience"
                    name="experience" 
                    value="{{ old('experience') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0"
                    min="0"
                    max="100">
                <p class="text-gray-500 text-sm mt-1">Optioneel: Geef aan hoeveel jaar ervaring u hebt</p>
                @error('experience')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-gray-700">
                    <strong>Let op:</strong> Na uw aanmelding wordt u direct actief in het systeem en kunnen de beheerders u aanwijzen bij wedstrijden.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button 
                    type="submit"
                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold transition">
                    Aanmelden als Scheidsrechter
                </button>
                <a 
                    href="{{ route('home') }}"
                    class="flex-1 bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 font-semibold transition text-center">
                    Annuleren
                </a>
            </div>
        </form>

        <!-- Info Section -->
        <div class="mt-12 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Informatie voor Scheidsrechters</h3>
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-800">Wat zijn uw taken?</h4>
                    <p class="text-gray-600 text-sm">Als scheidsrechter zult u toezicht houden op wedstrijden, regels handhaven en scores bijhouden.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Wanneer bent u beschikbaar?</h4>
                    <p class="text-gray-600 text-sm">Na aanmelding zal de beheerder u inplannen voor wedstrijden op basis van uw beschikbaarheid.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Vragen?</h4>
                    <p class="text-gray-600 text-sm">Neem contact op met de toernooi beheerder via het contact formulier op onze website.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
