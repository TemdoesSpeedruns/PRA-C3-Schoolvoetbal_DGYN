@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">School Bewerken</h1>
            <p class="text-gray-600">Pas de schoolgegevens aan</p>
        </div>

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
        <form method="POST" action="{{ route('admin.schools.update', $school) }}" class="bg-white rounded-lg shadow-lg p-8">
            @csrf
            @method('PATCH')

            <!-- Schoolnaam -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Schoolnaam <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name"
                    name="name" 
                    value="{{ old('name', $school->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Voer de naam van de school in"
                    required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Persoon -->
            <div class="mb-6">
                <label for="contact_person" class="block text-sm font-semibold text-gray-700 mb-2">
                    Contact Persoon <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="contact_person"
                    name="contact_person" 
                    value="{{ old('contact_person', $school->contact_person) }}"
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
                    E-mailadres <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    value="{{ old('email', $school->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Voer een geldig e-mailadres in"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Info -->
            <div class="mb-8">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    required>
                    <option value="pending" @selected($school->status === 'pending')>
                        In behandeling
                    </option>
                    <option value="approved" @selected($school->status === 'approved')>
                        Goedgekeurd
                    </option>
                    <option value="rejected" @selected($school->status === 'rejected')>
                        Afgewezen
                    </option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Leeftijdscategorie -->
            <div class="mb-8">
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                    Leeftijdscategorie
                </label>
                <select id="category" name="category"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">-- Selecteer categorie --</option>
                    <option value="3/4" @selected($school->category === '3/4')>Groep 3/4 (7-8 jaar)</option>
                    <option value="5/6" @selected($school->category === '5/6')>Groep 5/6 (9-10 jaar)</option>
                    <option value="7/8" @selected($school->category === '7/8')>Groep 7/8 (11-12 jaar)</option>
                    <option value="brugklas" @selected($school->category === 'brugklas')>Brugklas (12-13 jaar)</option>
                </select>
                <p class="text-gray-500 text-sm mt-1">Selecteer de leeftijdscategorie waar deze school aan deelneemt</p>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-black font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Bijwerken
                </button>
                <a 
                    href="{{ route('admin.schools.index') }}" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200 text-center">
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
