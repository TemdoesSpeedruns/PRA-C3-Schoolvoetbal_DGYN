@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Scheidsrechter Bewerken</h1>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.referees.update', $referee) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PATCH')

        <!-- Naam -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold mb-2">Volledige Naam</label>
            <input type="text" id="name" name="name" value="{{ old('name', $referee->name) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-semibold mb-2">E-mailadres</label>
            <input type="email" id="email" name="email" value="{{ old('email', $referee->email) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Type -->
        <div class="mb-6">
            <label for="type" class="block text-sm font-semibold mb-2">Type Scheidsrechter</label>
            <select id="type" name="type"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
                <option value="junior" @selected($referee->type === 'junior')>Junior</option>
                <option value="senior" @selected($referee->type === 'senior')>Senior</option>
                <option value="professional" @selected($referee->type === 'professional')>Professional</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Telefoon -->
        <div class="mb-6">
            <label for="phone" class="block text-sm font-semibold mb-2">Telefoonnummer</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', $referee->phone) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                maxlength="20">
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ervaring -->
        <div class="mb-6">
            <label for="experience" class="block text-sm font-semibold mb-2">Jaren Ervaring</label>
            <input type="number" id="experience" name="experience" value="{{ old('experience', $referee->experience) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                min="0" max="100">
            @error('experience')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" 
                    @checked(old('is_active', $referee->is_active))
                    class="mr-2 rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
                <span class="text-sm font-semibold">Actief</span>
            </label>
            <p class="text-gray-500 text-sm mt-1">Ongeactiveerde scheidsrechters kunnen niet worden aangewezen bij wedstrijden</p>
            @error('is_active')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                Opslaan
            </button>
            <a href="{{ route('admin.referees.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 font-semibold">
                Annuleren
            </a>
        </div>
    </form>
</div>
@endsection
