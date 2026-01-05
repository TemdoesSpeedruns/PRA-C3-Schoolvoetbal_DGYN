@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Toernooi Spelregels</h1>
            <p class="text-lg text-gray-600">Selecteer een toernooi om de spelregels en bepalingen in te zien</p>
        </div>

        <!-- Tabs Navigation -->
        <div x-data="{ selectedTab: 'lijnbal' }" class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Tab Buttons -->
            <div class="flex border-b border-gray-200">
                <button 
                    @click="selectedTab = 'lijnbal'"
                    :class="{'border-b-2 border-blue-600 text-blue-600': selectedTab === 'lijnbal', 'text-gray-600': selectedTab !== 'lijnbal'}"
                    class="flex-1 py-4 px-6 font-semibold transition-colors text-center hover:bg-gray-50">
                     Paaslijnbal Basisonderwijs
                </button>
                <button 
                    @click="selectedTab = 'voetbal'"
                    :class="{'border-b-2 border-green-600 text-green-600': selectedTab === 'voetbal', 'text-gray-600': selectedTab !== 'voetbal'}"
                    class="flex-1 py-4 px-6 font-semibold transition-colors text-center hover:bg-gray-50">
                     Paasvoetbal Voortgezet Onderwijs
                </button>
            </div>

            <!-- Lijnbal Tab -->
            <div x-show="selectedTab === 'lijnbal'" class="p-8 space-y-8">
                
                <!-- Algemene Uitgangspunten -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Algemene Uitgangspunten</h2>
                    <div class="space-y-3 text-gray-700">
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>1. Aanmelding:</strong> Bij binnenkomst het team aanmelden bij de wedstrijdleiding (in de zaal, onder het scorebord). Vergeet niet de volledig ingevulde spelerslijst in te leveren.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>2. Begeleiding:</strong> Zorg voor voldoende begeleiding van het team (ieder team dient ook een teller te hebben).</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>3. Op tijd aanwezig:</strong> Kijk tijdig hoe laat en waar het team moet spelen en zorg ervoor tijdig op het betreffende speelveld aanwezig te zijn.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>4. Netheid:</strong> Help de organisatie een handje door erop toe te zien dat er geen rommel wordt gemaakt. Na ons komen andere gebruikers die graag hun sport willen beoefenen in een schone omgeving.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>5. Volg aanwijzingen op:</strong> Let vooral op de aanwijzingen van de organisatie.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>6. EHBO:</strong> Er zijn bevoegde EHBO-ers aanwezig. Waarschuw ook even het secretariaat bij een blessure.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>7. Aantal spelers:</strong> Het team bestaat uit maximaal 10 spelers. Er staan 8 spelers (meisjes) in het veld, deze dienen allemaal ingeschreven te zijn op de betreffende school en zitten in groep 7 of 8.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>8. Capaciteit zaal:</strong> Er mogen per team maximaal 10 spelers en 3 begeleiders in de zaal aanwezig zijn. Alle toeschouwers dienen plaats te nemen op de tribunes.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>9. Diefstallen:</strong> De organisatie is daar niet verantwoordelijk voor. Laat de kinderen geen waardevolle dingen meenemen.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>10. Aansprakelijkheid:</strong> De organisatie aanvaardt op geen enkele wijze aansprakelijkheid voor vermissing of beschadiging van eigendommen.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>11. Schade rapporteren:</strong> Schade aangebracht aan of in de kleedkamers dient direct gemeld te worden bij het wedstrijdsecretariaat.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>12. Hygiëne:</strong> Houd de zaal en de omgeving schoon; controleer de kleedkamers bij het verlaten.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p><strong>13. Toernooileiding:</strong> De toernooileiding beslist in gevallen waarin het reglement niet voorziet.</p>
                        </div>
                    </div>
                </section>

                <!-- Reglementen -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Reglementen Paaslijnbal Basisonderwijs</h2>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>1. Deelnemers:</strong> Wedstrijden zijn uitsluitend voor leerlingen van groep 7 en 8.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>2. Team samenstelling:</strong> Maximaal 10 spelers. 8 in het veld, 2 speelsters mogen indraaien.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>3. Opgooi:</strong> Het eerstgenoemde team begint. Mag gebeuren vanaf een willekeurig punt achter de stippellijn.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>4. Puntensysteem:</strong> Winnaar krijgt 3 punten, verliezer 0, gelijkspel 1 punt per partij.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>5. Aanwezigheid:</strong> Teams dienen 5 minuten voor aanvang aanwezig te zijn. Niet aanwezig = 10-0 verlies.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>6. Opwerp:</strong> Met 1 hand over het net. Bal moet in het veld komen. Op de lijn is in.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>7. Aantal aanrakingen:</strong> Elk team mag de bal ten hoogste 3X aanraken.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>8. Dubbel vangen:</strong> Een speelster mag de bal slechts 1 maal achter elkaar aanraken. Dubbel vangen niet toegestaan.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>9. Lichaamsdelen:</strong> Bal mag met elk lichaamsdeel tot navelhoogte worden aangeraakt.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>10. Gelijktijdig aanraken:</strong> Twee speelsters mogen de bal gelijktijdig aanraken (telt als 2x).</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>11. Netraking:</strong> Bal mag het net raken. Speelster mag het net NIET aanraken.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>12. Middellijn:</strong> Speelster mag niet over de middellijn onder het net door.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>13. Positie vangen/gooien:</strong> Plaats van vangen = plaats van werpen. Geen lopen met de bal.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>14. Netspelers:</strong> Alleen netspelers mogen bal opspringend over het net spelen.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>15. Blokkeren:</strong> Niet toegestaan.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>16. Signalen:</strong> Begin en einde van de wedstrijd wordt centraal aangegeven.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>17. Finalewedstrijden:</strong> Na eindsignaal moet rally worden afgespeeld, verschil van 2 punten vereist.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>18. Nethoogte:</strong> 205cm.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>19. Speelduur:</strong> Poule: 10 minuten. Finales: met minimaal 2 punten verschil.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded">
                            <p><strong>20. Gelijke eindstand:</strong> Onderlinge uitslag  Meeste punten voor  Minste tegen  Tossen.</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Voetbal Tab -->
            <div x-show="selectedTab === 'voetbal'" class="p-8 space-y-8">
                
                <!-- Algemene Uitgangspunten -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Algemene Uitgangspunten</h2>
                    <div class="space-y-3 text-gray-700">
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>1. Aanmelding:</strong> Meld het team tijdig bij de wedstrijdleiding aan. Vergeet niet de volledig ingevulde spelerslijst in te leveren.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>2. Begeleiding en officials:</strong> Zorg voor voldoende begeleiding, scheidsrechters en grensrechters.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>3. Op tijd aanwezig:</strong> Kijk tijdig hoe laat en waar het team moet spelen.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>4. Netheid:</strong> Help de organisatie een handje. Zowel op het veld als in de kleedkamers staan vuilnisbakken.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>5. Volg aanwijzingen op:</strong> Let vooral op de aanwijzingen van de organisatie.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>6. EHBO:</strong> Er zijn bevoegde EHBO-ers aanwezig. Waarschuw het secretariaat bij een blessure.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>7. Respect voor officials:</strong> Lever geen commentaar op scheidsrechters en spelbegeleiders.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>8. Aantal spelers:</strong> 11 spelers in het veld. Allen ingeschreven bij de school, groep 1 (eerste klas).</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>9. Secretariaat:</strong> Alleen toegankelijk voor organisatie, scheidsrechters en leiders.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>10. Diefstallen:</strong> De organisatie is daar niet verantwoordelijk voor. Laat kinderen geen waardevolle dingen meenemen.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>11. Aansprakelijkheid:</strong> De organisatie aanvaardt op geen enkele wijze aansprakelijkheid voor vermissing of beschadiging.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>12. Schade rapporteren:</strong> Direct melden bij het wedstrijdsecretariaat.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>13. Hygiëne:</strong> Houd het veld en de omgeving schoon. Controleer kleedkamers bij verlaten.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p><strong>14. Toernooileiding:</strong> De toernooileiding beslist in gevallen waarin het reglement niet voorziet.</p>
                        </div>
                    </div>
                </section>

                <!-- Reglementen -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Reglementen Paasvoetbal Voortgezet Onderwijs</h2>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>1. KNVB Regels:</strong> Wedstrijden worden gespeeld volgens KNVB reglementen onder leiding van KNVB-scheidsrechters.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>2. Klasse C:</strong> Spelers zijn ingeschreven bij de betreffende school en zitten in de eerste klas.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>3. Geen wisseling van teams:</strong> Spelers mogen niet tussen teams wisselen, ook niet als andere teams zijn uitgeschakeld.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>4. Ongerechtige spelers:</strong> Elftal met ongerechtige spelers: wedstrijd ongeldig, tegenpartij wint 2-0.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>5a. Gelijke stand - Doelsaldo:</strong> Wanneer twee ploegen gelijk zijn geëindigd, telt eerst het doelsaldo.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>5b. Gelijke stand - Doelpunten:</strong> Is doelsaldo gelijk, telt het aantal doelpunten.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>5c. Gelijke stand - Onderlinge:</strong> Is dat gelijk, telt het onderlinge resultaat.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>5d. Gelijke stand - Strafschoppen:</strong> Leveren deze criteria geen winnaar op, volgt strafschoppenserie (3 per team).</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>6. Speelduur:</strong> Poule wedstrijden: 20 minuten. Kruisfinales: 30 minuten.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>7. Grensrechter:</strong> Uw school moet tijdens elke wedstrijd een grensrechter hebben (16 jaar of ouder).</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>8. Sportiviteit:</strong> Toon je sportiviteit door aanwezigheid tijdens de prijsuitreiking.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p><strong>9. Hesjes voor kleurconflict:</strong> Bij gelijke shirtkleuren zijn gekleurde hesjes beschikbaar bij de wedstrijdleiding.</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Back Button -->
        <div class="pt-8">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Terug naar home
            </a>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
