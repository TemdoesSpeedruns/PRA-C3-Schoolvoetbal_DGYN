<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-black dark:text-white">
            <h1 class="text-3xl font-bold mb-6">Laatste Uitslagen</h1>

            @if($tournaments->isEmpty())
                <p>Er zijn nog geen resultaten beschikbaar.</p>
            @else
                <ul class="space-y-4">
                    @foreach($tournaments as $tournament)
                        <li class="border-b border-gray-300 dark:border-gray-600 pb-2">
                            {{ $tournament->name }} ({{ $tournament->year }})
                            @if($tournament->results)
                                - Resultaten: {{ $tournament->results }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
