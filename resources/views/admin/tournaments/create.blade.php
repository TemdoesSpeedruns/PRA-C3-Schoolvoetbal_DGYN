@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Nieuw Toernooi Aanmaken</h1>
        <p class="text-gray-600 mt-2">Maak een nieuw voetbaltoernooi aan</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.tournaments.store') }}" method="POST">
            @csrf

            <!-- Toernooi Naam -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Toernooi Naam *
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="b.v. Paastoernooi 2026"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                    required
                >
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                    Leeftijdscategorie *
                </label>
                <select 
                    id="type" 
                    name="type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                    required
                >
                    <option value="">-- Selecteer een categorie --</option>
                    <option value="Voetbal 3/4" {{ old('type') === 'Voetbal 3/4' ? 'selected' : '' }}>Voetbal 3/4</option>
                    <option value="Voetbal 5/6" {{ old('type') === 'Voetbal 5/6' ? 'selected' : '' }}>Voetbal 5/6</option>
                    <option value="Voetbal 7/8" {{ old('type') === 'Voetbal 7/8' ? 'selected' : '' }}>Voetbal 7/8</option>
                    <option value="Voetbal VO meisjes" {{ old('type') === 'Voetbal VO meisjes' ? 'selected' : '' }}>Voetbal VO meisjes</option>
                    <option value="Voetbal VO jongens/gemend" {{ old('type') === 'Voetbal VO jongens/gemend' ? 'selected' : '' }}>Voetbal VO jongens/gemend</option>
                    <option value="Lijnbal basisschool" {{ old('type') === 'Lijnbal basisschool' ? 'selected' : '' }}>Lijnbal basisschool</option>
                    <option value="Lijnbal VO" {{ old('type') === 'Lijnbal VO' ? 'selected' : '' }}>Lijnbal VO</option>
                </select>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jaar -->
            <div class="mb-6">
                <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                    Jaar *
                </label>
                <input 
                    type="number" 
                    id="year" 
                    name="year"
                    value="{{ old('year', date('Y')) }}"
                    min="2000"
                    max="{{ date('Y') + 1 }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                    required
                >
                @error('year')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Status *
                </label>
                <select 
                    id="status" 
                    name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                    required
                >
                    <option value="active" {{ old('status') === 'active' || !old('status') ? 'selected' : '' }}>Actief</option>
                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Voltooid</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Knoppen -->
            <div class="flex gap-3">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition"
                >
                    Toernooi Aanmaken
                </button>
                <a 
                    href="{{ route('admin.tournaments.index') }}" 
                    class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg font-semibold hover:bg-gray-400 transition"
                >
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
