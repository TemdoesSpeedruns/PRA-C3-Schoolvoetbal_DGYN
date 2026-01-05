<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center text-black">
            <!-- Heading -->
            <img style="width: 200px;" src="{{ @asset('img/logo.png') }}" alt="Schoolvoetbal Toernooi Logo" class="mx-auto mb-6 w-32 h-auto" />

            <h1 class="text-4xl font-bold mb-6">Welkom bij het Schoolvoetbal Toernooi!</h1>

            <!-- Description -->
            <p class="mb-8">
                Het officiële platform voor het schoolvoetbaltoernooi.
                Blijf op de hoogte van uitslagen, schrijf je team in,
                en ontdek de winnaars van vorige edities.
            </p>

            <!-- Navigation Section -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <!-- Wedstrijdresultaten Card -->
                <a href="{{ route('public.scores') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border-2 border-gray-200 hover:border-blue-500">
                    <div class="text-3xl mb-2">⚽</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Wedstrijdresultaten</h3>
                    <p class="text-gray-600">Bekijk de resultaten van de huidige wedstrijddag</p>
                </a>

                <!-- Vorige Winnaars Card -->
                <a href="{{ route('historie') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border-2 border-gray-200 hover:border-blue-500">
                    <div class="text-3xl mb-2">🏆</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Vorige Winnaars</h3>
                    <p class="text-gray-600">Zie de winnaars van vorige toernooieditie</p>
                </a>

                <!-- Registreer School Card -->
                <a href="{{ route('schools.register.form') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border-2 border-gray-200 hover:border-blue-500">
                    <div class="text-3xl mb-2">📝</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Registreer School</h3>
                    <p class="text-gray-600">Schrijf je school in voor het toernooi</p>
                </a>
            </div>

            <!-- Rules Section -->
            <div class="mt-8 max-w-2xl mx-auto">
                <!-- Spelregels Card -->
                <a href="{{ route('information.rules') }}" class="block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-md hover:shadow-lg transition-shadow border-2 border-blue-300 hover:border-blue-600">
                    <div class="text-3xl mb-2">📋</div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-800">Spelregels</h3>
                    <p class="text-gray-700 text-sm">Lees de officiële voetbalregels en bepalingen voor het toernooi</p>
                </a>
            </div>

            <!-- Info Section -->
            <div class="mt-12 max-w-2xl mx-auto text-black">
                <h2 class="text-2xl font-semibold mb-4">Over het Toernooi</h2>
                <p>
                    Het schoolvoetbaltoernooi brengt alle scholen samen voor spannende wedstrijden, plezier en sportiviteit.
                    Teams kunnen zich inschrijven en direct hun resultaten volgen zodra de wedstrijden gespeeld zijn.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white mt-16 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Bestuur Section -->
                <div class="text-left">
                    <h3 class="text-lg font-bold text-white mb-4 border-b-2 border-blue-500 pb-2">Bestuur Stichting</h3>
                    <p class="text-sm font-semibold text-gray-200 mb-3">Paastoernooien Bergen op Zoom</p>
                    <ul class="space-y-3 text-sm text-gray-100">
                        <li><span class="font-semibold text-blue-400">Voorzitter:</span> Carin Veringa</li>
                        <li><span class="font-semibold text-blue-400">Secretaris:</span> Lieke Graumans</li>
                        <li><span class="font-semibold text-blue-400">Penningmeester:</span> Sander van Kaam</li>
                    </ul>
                    <h4 class="text-white font-bold mt-4 mb-2 text-sm">Leden:</h4>
                    <p class="text-sm text-gray-100">Stefan Joosen, Nienke Raatgeep, Corné van Tilburg, Kevin Bogers, Orrin van Oosterhout</p>
                </div>

                <!-- Contact Section -->
                <div class="text-left">
                    <h3 class="text-lg font-bold text-white mb-4 border-b-2 border-blue-500 pb-2">Contact</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="font-semibold text-blue-400 mb-1">Email</p>
                            <a href="mailto:paastoernooienboz@outlook.com" class="text-gray-100 hover:text-blue-300 underline break-all">paastoernooienboz@outlook.com</a>
                        </div>
                        <div>
                            <p class="font-semibold text-blue-400 mb-1">Secretaris bellen</p>
                            <a href="tel:+31614605997" class="text-gray-100 hover:text-blue-300 underline text-lg font-semibold">06-14605997</a>
                        </div>
                    </div>
                </div>

                <!-- Organization Info Section -->
                <div class="text-left">
                    <h3 class="text-lg font-bold text-white mb-4 border-b-2 border-blue-500 pb-2">Organisatie</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="font-semibold text-blue-400 mb-1">KvK Nummer</p>
                            <p class="text-gray-100 text-lg font-semibold">41106903</p>
                        </div>
                        <div>
                            <p class="font-semibold text-blue-400 mb-1">Rekeningnummer</p>
                            <p class="text-gray-100 text-lg font-semibold">NL37 ABNA 0498 5999 73</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-600 mt-8 pt-6 text-center text-sm text-gray-200">
                <p>&copy; {{ date('Y') }} Stichting Paastoernooien Bergen op Zoom. Alle rechten voorbehouden.</p>
            </div>
        </div>
    </footer>
</x-app-layout>
