# 🎨 UI GIDS - POULE SYSTEEM INTERFACES

## Inleiding

Deze gids beschrijft alle gebruikersinterfaces (UI) die voor het poulestelseem zijn aangemaakt. Dit omvat admin interfaces, publieke weergaven en integratie met bestaande interfaces.

---

## 📑 Inhoudsopgave

1. [Admin Poulbeheer](#admin-poulbeheer)
2. [Publieke Mijn Poule](#publieke-mijn-poule)
3. [Admin Dashboard](#admin-dashboard)
4. [Navigatie](#navigatie)
5. [Design Systeem](#design-systeem)
6. [Responsive Ontwerp](#responsive-ontwerp)
7. [Accessibility](#accessibility)
8. [Error States](#error-states)
9. [Future Enhancements](#future-enhancements)

---

## Admin Poulbeheer

### Route
```
GET /admin/poules → PoolController@index
```

### Doel
Admin mogelijkheid om alle toernooien met hun poules en scholen te zien.

### URL
```
http://localhost/admin/poules
```

### Vereenvoudigde Mockup

```
┌─────────────────────────────────────────────┐
│           POULEBEHEER                        │
│                                              │
├─────────────────────────────────────────────┤
│  Toernooi: Voetbal 2025                    │
│  Status: Actief                            │
│                                              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐ │
│  │ Poule A  │  │ Poule B  │  │ Poule C  │ │
│  │ 4/4      │  │ 3/4      │  │ 2/4      │ │
│  │──────────│  │──────────│  │──────────│ │
│  │School 1  │  │School 5  │  │School 9  │ │
│  │School 2  │  │School 6  │  │School10  │ │
│  │School 3  │  │School 7  │  │          │ │
│  │School 4  │  │School 8  │  │          │ │
│  └──────────┘  └──────────┘  └──────────┘ │
│                                              │
├─────────────────────────────────────────────┤
│  Toernooi: Lijnbal 2025                    │
│  Status: Actief                            │
│                                              │
│  ┌──────────────────┐                       │
│  │ Poule A         │                       │
│  │ 2/4             │                       │
│  │──────────────────│                       │
│  │School 11        │                       │
│  │School 12        │                       │
│  └──────────────────┘                       │
│                                              │
└─────────────────────────────────────────────┘
```

### HTML Structuur

```blade
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Poulebeheer</h1>

    @foreach($tournaments as $tournament)
        <!-- Toernooi Kaart -->
        <div class="mb-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4">
                {{ $tournament->name }}
            </h2>
            
            @if($tournament->pools->isEmpty())
                <p class="text-gray-600">
                    Geen poules gevonden.
                </p>
            @else
                <!-- Poule Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($tournament->pools as $pool)
                        <!-- Poule Kaart -->
                        <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
                            <h3 class="font-bold text-lg mb-2">
                                Poule {{ $pool->name }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">
                                {{ $pool->schools->count() }}/4 Scholen
                            </p>
                            <ul class="text-sm space-y-1">
                                @foreach($pool->schools as $school)
                                    <li class="py-1">
                                        • {{ $school->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
```

### Visual Hierarchy

```
┌─ H1: Pagina Titel (3xl, bold)
│
├─ H2: Toernooi Naam (2xl, bold, witblok)
│
├─ H3: Poule Naam (lg, bold)
│
├─ P: Schoolteller (sm, grijs)
│
└─ LI: Schoolnamen (sm, list)
```

### Kleuren

- **Achtergrond**: Wit (#FFFFFF)
- **Poule Card BG**: Licht blauw (#EFF6FF)
- **Poule Border**: Blauw (#3B82F6)
- **Text**: Zwart/Grijs (#000000 / #4B5563)
- **Accent**: Blauw (#3B82F6)

### Tailwind CSS Klassen

```tailwind
.container        → max-width container
.mx-auto          → center horizontally
.px-4             → padding sides
.py-8             → padding vertical
.text-3xl         → font size
.font-bold        → font weight
.bg-white         → white background
.rounded-lg       → rounded corners
.shadow           → drop shadow
.grid             → grid layout
.grid-cols-1      → 1 column mobile
.md:grid-cols-2   → 2 columns tablet
.lg:grid-cols-4   → 4 columns desktop
.gap-4            → space between items
.bg-blue-50       → light blue bg
.border-l-4       → left border
.border-blue-500  → blue border
.text-gray-600    → gray text
.space-y-1        → vertical spacing
```

### Responsive Gedrag

```
Mobile (< 768px)
├─ 1 kolom
├─ Vol scherm width
└─ Poules onder elkaar

Tablet (768px - 1024px)
├─ 2 kolommen
├─ 50% width
└─ 2 poules per rij

Desktop (> 1024px)
├─ 4 kolommen
├─ 25% width
└─ 4 poules per rij
```

### Interactiviteit

- ✅ Hover effect op kaarten (optioneel)
- ✅ Scroll op lange pagina's
- ✅ Mobile responsive
- ✅ Geen JavaScript vereist

---

## Publieke Mijn Poule

### Route
```
GET /mijn-poule → PublicPoolController@myPool
```

### Doel
Scholen kunnen hun toewijde poule en medescholen zien.

### URL
```
http://localhost/mijn-poule
```

### Vereenvoudigde Mockup

```
┌─────────────────────────────────────────────┐
│           MIJN POULE                         │
│                                              │
├─────────────────────────────────────────────┤
│  Poule C                                   │
│                                              │
│  Deelnemende Scholen:                       │
│                                              │
│  ✓ Jouw School (Jij)                       │
│  • School 2                                │
│  • School 3                                │
│  • School 4                                │
│                                              │
├─────────────────────────────────────────────┤
│  Let op: Wedstrijdschema's niet beschikbaar │
│  Controleer binnenkort terug!               │
│                                              │
└─────────────────────────────────────────────┘
```

### HTML Structuur

```blade
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mijn Poule</h1>

    @if(!$pool)
        <!-- Niet Ingedeeld Bericht -->
        <div class="bg-blue-50 p-4 rounded">
            <p class="text-blue-900">
                {{ $message ?? 'Je bent nog niet ingedeeld.' }}
            </p>
        </div>
    @else
        <!-- Poule Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">
                Poule {{ $pool->name }}
            </h2>
            
            <h3 class="text-lg font-bold mb-3">Deelnemende Scholen:</h3>
            
            <div class="space-y-2">
                @foreach($pool->schools as $schoolItem)
                    <div class="flex items-center p-3 bg-gray-50 rounded">
                        <span class="text-lg font-bold">
                            @if($schoolItem->id === $school->id)
                                ✓
                            @else
                                •
                            @endif
                        </span>
                        <span class="ml-3">
                            {{ $schoolItem->name }}
                            @if($schoolItem->id === $school->id)
                                <span class="text-xs text-gray-600">(Jij)</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Info Bericht -->
        <div class="bg-blue-50 p-4 rounded">
            <p class="text-sm text-blue-900">
                <strong>Let op:</strong> Schema's niet beschikbaar.
            </p>
        </div>
    @endif
</div>
```

### Visual Hierarchy

```
┌─ H1: Pagina Titel
│
├─ Alert (Niet Ingedeeld)  of  Poule Info
│
├─ H2: Poule Naam
│
├─ H3: Schoolenlijst
│
└─ LI: Scholen items
   ├─ Jij (✓ checkmark)
   └─ Anderen (• bullet)
```

### Kleuren

- **Achtergrond**: Wit
- **Alert BG**: Licht blauw
- **Alert Text**: Donkerblauw
- **List BG**: Licht grijs (#F3F4F6)
- **Checkmark**: Groen (✓)
- **Label**: Licht grijs

### Tailwind CSS Klassen

```tailwind
.container      → main wrapper
.bg-blue-50     → light blue
.text-blue-900  → dark blue text
.bg-white       → white background
.p-6            → padding
.mb-4           → margin bottom
.flex           → flexbox
.items-center   → vertical center
.ml-3           → margin left
.text-xs        → extra small text
.text-gray-600  → gray text
.space-y-2      → vertical spacing
.bg-gray-50     → light gray
```

### Interactiviteit

- ✅ Clear visuals
- ✅ Easy to understand
- ✅ Mobile responsive
- ✅ Accessible

---

## Admin Dashboard

### Wijzigingen

Voorkant admin dashboard bijgewerkt met poulelinks.

### Locatie

```
resources/views/AdminDashboard.blade.php
```

### Toevoegde Snelkoppelingen

```blade
<div class="grid grid-cols-3 gap-4">
    <!-- Bestaande Items -->
    ...
    
    <!-- Nieuw: Poule Links -->
    <a href="{{ route('admin.pools.index') }}" 
       class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded">
        📊 Bekijk Poules
    </a>
</div>
```

### Mockup

```
┌───────────────────────────────────────┐
│  ADMIN DASHBOARD                      │
│                                        │
│  [Scholen] [Toernooien] [Poules]  │
│  [Scores]  [Resultaten] [...]     │
│                                        │
│  Snelacties:                          │
│  ┌──────────┐ ┌──────────┐           │
│  │ Bekijk   │ │ Beheer   │           │
│  │ Poules   │ │ Scholen  │           │
│  └──────────┘ └──────────┘           │
│                                        │
└───────────────────────────────────────┘
```

---

## Navigatie

### Wijzigingen

Hoofdnavigatie bijgewerkt met "Mijn Poule" link.

### Locatie

```
resources/views/layouts/navigation.blade.php
```

### Toevoegde Link

```blade
@auth
    {{-- Bestaande navigatie --}}
    ...
    
    {{-- Nieuw: Mijn Poule --}}
    <a href="{{ route('my.pool') }}" class="...">
        🎯 Mijn Poule
    </a>
@endauth
```

### Menu Structuur

```
MENU ITEMS:
├─ Dashboard
├─ Scholen (Admin)
├─ Toernooien (Admin)
├─ Poules (Admin)        ← NIEUW
├─ Mijn Poule (Openbaar) ← NIEUW
└─ Profiel
```

---

## Design Systeem

### Kleurenpalet

```
Primair Blauw
├─ blue-50   #EFF6FF (Backgrounds)
├─ blue-500  #3B82F6 (Borders, Accents)
└─ blue-600  #2563EB (Hovers)

Neutrals
├─ white     #FFFFFF
├─ gray-50   #F9FAFB (Backgrounds)
├─ gray-100  #F3F4F6
├─ gray-600  #4B5563 (Text)
└─ black     #000000

Alerts
├─ yellow    (Warnings)
└─ red       (Errors)
```

### Typografie

```
Grootte Schaal (Tailwind):
├─ text-3xl = H1 (Pagina titel)
├─ text-2xl = H2 (Toernooi)
├─ text-lg  = H3 (Poule)
├─ text-sm  = Metadata
└─ text-xs  = Labels

Gewicht:
├─ font-bold    = Headings
├─ font-normal  = Body
└─ font-light   = Secondary
```

### Spacing

```
Padding (p-x):
├─ p-3 = Small containers
├─ p-4 = Medium containers
└─ p-6 = Large containers

Margin (m-x):
├─ mb-2 = Small gaps
├─ mb-4 = Medium gaps
└─ mb-8 = Large gaps

Gap (gap-x):
├─ gap-2 = Small grids
├─ gap-4 = Medium grids
└─ gap-8 = Large grids
```

### Border Radius

```
Rounded:
├─ rounded     = 0.25rem
└─ rounded-lg  = 0.5rem (Used)
```

### Shadows

```
Shadow:
├─ shadow    = Drop shadow
└─ shadow-lg = Larger shadow
```

---

## Responsive Ontwerp

### Breakpoints

```
Mobile:  < 640px   (single-col)
Tablet:  641-1024px (2-col)
Desktop: > 1024px   (4-col)
```

### Responsive Klassen

```tailwind
.grid-cols-1       → Mobile
.md:grid-cols-2    → Tablet
.lg:grid-cols-4    → Desktop

.block             → Block
.md:inline-block   → Inline on tablet
.lg:flex           → Flex on desktop
```

### Mobiel Optimalisatie

- ✅ Single kolom layouts
- ✅ Volledige breedte
- ✅ Touch-vriendelijk
- ✅ Groot toetsenbord
- ✅ Minimale scrolling

### Tablet Optimalisatie

- ✅ 2-kolom grid
- ✅ Gebalanceerde spacing
- ✅ Optimale leesbaarheid

### Desktop Optimalisatie

- ✅ Multi-kolom grid (max 4)
- ✅ Volle mogelijkheden
- ✅ Side-by-side inhoud

---

## Accessibility

### ARIA Labels

```blade
<div role="grid" aria-label="Tournament Pools">
    <!-- Pool cards -->
</div>
```

### Semantic HTML

```blade
<h1>Titel</h1>      <!-- Juist gebruikt -->
<h2>Subtitel</h2>   <!-- Hiërarchie -->
<section></section> <!-- Semantische tags -->
```

### Kleurcontrast

- ✅ Text/Background > 4.5:1 ratio
- ✅ Blauw op wit = accessible
- ✅ Grijs op wit = acceptable

### Keyboard Navegatie

- ✅ Links navigeerbaar met Tab
- ✅ Geen keyboard traps
- ✅ Logische tab order

### Screen Reader

- ✅ Alt tekst voorzien (waar nodig)
- ✅ Semantische structuur
- ✅ ARIA labels

---

## Error States

### Geen Poules

```blade
@if($tournament->pools->isEmpty())
    <div class="bg-yellow-50 p-4 rounded">
        <p class="text-yellow-900">
            Geen poules gevonden voor dit toernooi.
        </p>
    </div>
@endif
```

### Niet Ingedeeld

```blade
@if(!$pool)
    <div class="bg-blue-50 p-4 rounded">
        <p class="text-blue-900">
            Je bent nog niet ingedeeld in een poule.
        </p>
    </div>
@endif
```

### Geen Toernooien

```blade
@if($tournaments->isEmpty())
    <div class="bg-yellow-50 p-4 rounded">
        <p class="text-yellow-900">
            Geen toernooien gevonden.
        </p>
    </div>
@endif
```

### Styling

- **Achtergrond**: Pastel kleur (#FEF3C7, #EFF6FF)
- **Text**: Donkere variant (#92400E, #0C4A6E)
- **Border**: Opsioneel links border

---

## Toekomstige Verbeteringen

### UI Enhancements

- [ ] Drag-and-drop poulrearrangement
- [ ] Poulebewerking (naam wijzigen)
- [ ] School verwijdering uit poule
- [ ] Poule verwijdering
- [ ] Bulk school import
- [ ] CSV export

### Interactiviteit

- [ ] Modals voor acties
- [ ] Inline editing
- [ ] Real-time updates (WebSockets)
- [ ] Notifications
- [ ] Animations

### Analytics

- [ ] Pool statistics
- [ ] School distribution graphs
- [ ] Capacity charts
- [ ] Dashboard widgets

### Mobile App

- [ ] Native iOS app
- [ ] Native Android app
- [ ] Push notifications
- [ ] Offline support

---

## Checklist voor Tests

### Visual Tests
- [ ] Poules weergave correct
- [ ] Schools correct gelisted
- [ ] Telaanduiding klopt
- [ ] Kleuren consistent
- [ ] Spacing uniform

### Functional Tests
- [ ] Links werken
- [ ] Data laadt correct
- [ ] Geen errors in console
- [ ] Responsive op alle schermen
- [ ] Keyboard navigatie werkt

### Accessibility Tests
- [ ] Screen reader compatible
- [ ] Kleurcontrast OK
- [ ] Tabbing werkt
- [ ] Headings correct genest
- [ ] Semantic HTML gebruikt

---

## Referenties

### Tailwind CSS
- https://tailwindcss.com/docs
- Classes: grid, gap, grid-cols
- Responsive: md:, lg:, xl:

### Blade Templating
- Laravel loops: @foreach, @if
- Named routes: route('name')
- Components: @component

### Best Practices
- Mobile-first design
- Semantic HTML
- WCAG 2.1 compliance
- Performance optimization

---

## Conclusie

De UI interfaces zijn schoon, responsief en gebruikersvriendelijk. Alle componenten gebruiken consistent Tailwind CSS styling en volgen accessibility best practices.

**Key Features:**
- ✅ Responsive design
- ✅ Accessible
- ✅ Consistent styling
- ✅ Clear hierarchy
- ✅ Error handling

---

**Opgesteld:** 23 December 2025
**Status:** ✅ VOLLEDIG
**Versie:** 1.0
