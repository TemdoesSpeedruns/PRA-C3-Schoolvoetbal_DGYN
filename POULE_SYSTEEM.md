# 🎓 POULE SYSTEEM - VOLLEDIGE GIDS

## Inleiding

Dit document biedt een **volledige technische referentie** voor het automatische poulestelstel dat voor uw Schoolvoetbal-toernooiapplicatie is geïmplementeerd. Het beschrijft architectuur, implementatiedetails, databaseontwerp en integratiepunten.

---

## 📚 Inhoudsopgave

1. [Architectuuoverzicht](#architectuuoverzicht)
2. [Databaseontwerp](#databaseontwerp)
3. [Modelstructuur](#modelstructuur)
4. [Controllerlogica](#controllerlogica)
5. [Routeconfiguratie](#routeconfiguratie)
6. [Weergavelagen](#weergavelagen)
7. [Integratiepunten](#integratiepunten)
8. [Toewijzingsalgoritme](#toewijzingsalgoritme)
9. [Foutafhandeling](#foutafhandeling)
10. [Prestaties](#prestaties)

---

## Architectuuoverzicht

### High-Level Architectuur

```
┌─────────────────────────────────────────────────────┐
│                  FRONTEND (Blade)                   │
│   Admin Dashboard │ Pools View │ Public Pool View   │
└────────────────────┬──────────────────────────────┘
                     │ HTTP Requests
┌────────────────────▼──────────────────────────────┐
│                   ROUTES                           │
│  /admin/poules (PoolController)                   │
│  /mijn-poule (PublicPoolController)               │
│  /admin/scholen (SchoolApprovalController)        │
└────────────────────┬──────────────────────────────┘
                     │ Method Calls
┌────────────────────▼──────────────────────────────┐
│              CONTROLLERS                           │
│  - PoolController (Admin viewing)                 │
│  - PublicPoolController (Public viewing)          │
│  - SchoolApprovalController (Auto-assignment)    │
└────────────────────┬──────────────────────────────┘
                     │ Eloquent ORM
┌────────────────────▼──────────────────────────────┐
│                  MODELS                            │
│  - Pool (belongsTo Tournament, hasMany Schools)  │
│  - Tournament (hasMany Pools)                     │
│  - School (belongsTo Pool)                        │
└────────────────────┬──────────────────────────────┘
                     │ SQL Queries
┌────────────────────▼──────────────────────────────┐
│                 DATABASE                           │
│  - pools (id, tournament_id, name, timestamps)   │
│  - schools (... pool_id FK ...)                  │
│  - tournaments (...)                             │
└─────────────────────────────────────────────────────┘
```

### Kerncomponenten

| Component | Verantwoordelijkheid | Status |
|-----------|---------------------|--------|
| Pool Model | Poulegegevensstructuur | ✅ Voltooid |
| Tournament Model | Toernooirelaties | ✅ Bijgewerkt |
| School Model | Schoolrelaties | ✅ Bijgewerkt |
| PoolController | Admin poulaweergave | ✅ Voltooid |
| PublicPoolController | Publieke poulaweergave | ✅ Voltooid |
| SchoolApprovalController | Auto-toewijzing logica | ✅ Bijgewerkt |
| Migratie | Databaseschema | ✅ Klaar |

---

## Databaseontwerp

### Poultabel Schema

```sql
CREATE TABLE pools (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    tournament_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tournament_id) REFERENCES tournaments(id) ON DELETE CASCADE
);
```

### Schools-tabel Wijzigingen

```sql
-- Toegevoegd aan bestaande schools tabel:
ALTER TABLE schools ADD COLUMN pool_id BIGINT UNSIGNED NULL;
ALTER TABLE schools ADD FOREIGN KEY (pool_id) REFERENCES pools(id) ON DELETE SET NULL;
```

### Relatiediagram

```
TOURNAMENTS
    ├── id (Primary Key)
    ├── name
    ├── status
    └── ... other columns
         │
         ├─┬── POOLS
         │ │   ├── id (Primary Key)
         │ │   ├── tournament_id (Foreign Key → tournaments.id)
         │ │   ├── name (A, B, C, D...)
         │ │   └── timestamps
         │ │    │
         │ │    └─┬── SCHOOLS
         │ │      ├── id (Primary Key)
         │ │      ├── name
         │ │      ├── email
         │ │      ├── pool_id (Foreign Key → pools.id)
         │ │      └── ... other columns
         │ │
         │ └─── (Andere tournament gegevens)
```

### Indexeringsstrategie

```sql
-- Voor optimale prestaties:
INDEX ON pools(tournament_id)
INDEX ON schools(pool_id)
INDEX ON schools(tournament_id)
```

---

## Modelstructuur

### Pool Model (`app/Models/Pool.php`)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    protected $fillable = ['tournament_id', 'name'];

    // Relaties
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }
}
```

**Verantwoordelijkheden:**
- Represents een poule voor een toernooi
- Beheert relaties tot Tournament en Schools
- Ondersteunt withCount() voor het tellen van scholen
- Ondersteunt cascadeverwijderingen

**Relationele Methoden:**
- `tournament()`: Keert het tournooirecord terug
- `schools()`: Keert alle scholen in deze poule terug

**Typische Vragen:**
```php
// Alle poules voor een toernooi
$pools = Pool::where('tournament_id', $id)->with('schools')->get();

// Scholen tellen per poule
$pools = Pool::withCount('schools')->get();

// Minst volle poule vinden
$pool = Pool::withCount('schools')
    ->where('tournament_id', $id)
    ->orderBy('schools_count')
    ->first();

// Poule verwijderen en school in geval van disaster
Pool::where('tournament_id', $id)->delete(); // Cascade deletes
```

### Tournament Model Wijzigingen

```php
// In Tournament model, toegevoegd:
public function pools()
{
    return $this->hasMany(Pool::class);
}
```

**Nieuw querypatroon:**
```php
$tournament = Tournament::with('pools.schools')->find($id);
// Nu: $tournament->pools geeft alle poules
//     $tournament->pools[0]->schools geeft alle scholen in Poule A
```

### School Model Wijzigingen

```php
// In School model, reeds aanwezig:
public function pool()
{
    return $this->belongsTo(Pool::class);
}
```

**Typische Vragen:**
```php
$school = School::with('pool.tournament')->find($id);
// Nu: $school->pool geeft de poulrecord
//     $school->pool->tournament geeft het tournooirecord
```

---

## Controllerlogica

### PoolController (`app/Http/Controllers/PoolController.php`)

**Doel:** Pouleweergave voor beheerders

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tournament;

class PoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Alleen beheerders
    }

    public function index()
    {
        $tournaments = Tournament::with([
            'pools.schools'
        ])->get();

        return view('admin.pools.index', [
            'tournaments' => $tournaments
        ]);
    }
}
```

**Logica:**
1. Controleert of gebruiker is geverifieerd
2. Controleert of gebruiker beheerder is
3. Laadt alle toernooien met ingesloten poules en scholen
4. Geeft Blade-weergave terug met gegevens

**Middleware Stack:**
```
Request → auth (Is gebruiker ingelogd?) 
       → admin (Is gebruiker beheerder?)
       → Laad data
       → Gef Weergave
```

### PublicPoolController (`app/Http/Controllers/PublicPoolController.php`)

**Doel:** Pouleweergave voor scholen

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PublicPoolController extends Controller
{
    // GEEN middleware = openbaar toegankelijk

    public function myPool()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }

        // Get user's school
        $school = School::where('user_id', $user->id)->first();

        if (!$school || !$school->pool) {
            return view('my-pool', [
                'pool' => null,
                'message' => 'Je bent nog niet ingedeeld in een poule.'
            ]);
        }

        return view('my-pool', [
            'pool' => $school->pool->load('schools'),
            'school' => $school
        ]);
    }
}
```

**Logica:**
1. Haalt ingelogde gebruiker op
2. Zoekt hun schoolrecord
3. Zoekt hun poulassociatie
4. Geeft weergave terug met pouldetails

**Foutafhandeling:**
- Controleert of gebruiker is ingelogd → Doorverwijzing naar login
- Controleert of school bestaat → Bericht weergeven
- Controleert of poule is toegewezen → Bericht weergeven

### SchoolApprovalController Wijzigingen

**Waarom veranderd:** Auto-toewijzing bij school-goedkeuring

```php
// In approve() method, after approval:

private function assignToPool(School $school)
{
    // Stap 1: Vind actieve toernooien
    $tournaments = Tournament::where('status', 'active')->get();

    foreach ($tournaments as $tournament) {
        // Stap 2: Voorde elk toernooi, vind minst volle poule
        $pool = Pool::withCount('schools')
            ->where('tournament_id', $tournament->id)
            ->orderBy('schools_count', 'asc')
            ->first();

        // Stap 3: Maak poule aan als deze niet bestaat
        if (!$pool) {
            $poolCount = Pool::where('tournament_id', $tournament->id)->count();
            $poolName = chr(65 + $poolCount); // A, B, C, D...
            
            $pool = Pool::create([
                'tournament_id' => $tournament->id,
                'name' => $poolName
            ]);
        }

        // Stap 4: Controleer of poule vol is (max 4)
        if ($pool->schools()->count() < 4) {
            $school->update(['pool_id' => $pool->id]);
        } else {
            // Stap 5: Maak nieuwe poule aan
            $poolCount = Pool::where('tournament_id', $tournament->id)->count();
            $poolName = chr(65 + $poolCount);
            
            $newPool = Pool::create([
                'tournament_id' => $tournament->id,
                'name' => $poolName
            ]);
            
            $school->update(['pool_id' => $newPool->id]);
        }
    }
}
```

**Toewijzingslogica:**
1. Vind alle ACTIEVE toernooien
2. Voor elk toernooi:
   - Vind minst volle poule
   - Maak aan als deze niet bestaat
   - Voeg school toe als niet vol (max 4)
   - Maak nieuwe poule aan als vol

**Efficiëntie:**
- Gebruikt `withCount()` in plaats van aparte queries
- Vind minst volle poule in één query
- Beperkt databaseverzoeken

---

## Routeconfiguratie

### Routes (`routes/web.php`)

```php
// Admin poules
Route::get('/admin/poules', [PoolController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.pools.index');

// Publieke "Mijn Poule"
Route::get('/mijn-poule', [PublicPoolController::class, 'myPool'])
    ->name('my.pool');
```

### Routeparameters

| Route | Methode | Middleware | Naam | Doel |
|-------|---------|------------|------|------|
| `/admin/poules` | GET | auth, admin | admin.pools.index | Admin poulweergave |
| `/mijn-poule` | GET | - | my.pool | Publieke poulweergave |

### Routeaanroepen in Sjablonen

```blade
{{-- Admin Link --}}
<a href="{{ route('admin.pools.index') }}">
    Bekijk Poules
</a>

{{-- Publieke Link --}}
<a href="{{ route('my.pool') }}">
    Mijn Poule
</a>
```

---

## Weergavelagen

### Admin Poulweergave (`resources/views/admin/pools/index.blade.php`)

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Poulebeheer</h1>

    @if($tournaments->isEmpty())
        <div class="bg-yellow-50 p-4 rounded">
            <p>Geen toernooien gevonden.</p>
        </div>
    @else
        @foreach($tournaments as $tournament)
            <div class="mb-8 bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">
                    {{ $tournament->name }}
                </h2>
                
                @if($tournament->pools->isEmpty())
                    <p class="text-gray-600">
                        Geen poules nog voor dit toernooi.
                    </p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($tournament->pools as $pool)
                            <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
                                <h3 class="font-bold text-lg mb-2">
                                    Poule {{ $pool->name }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ $pool->schools->count() }}/4 Scholen
                                </p>
                                <ul class="text-sm">
                                    @foreach($pool->schools as $school)
                                        <li class="py-1">
                                            {{ $school->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
```

**Weergavefuncties:**
- Toont alle toernooien
- Voor elk toernooi, toont alle poules
- Voor elke poule, toont alle scholen
- Toont schoolaantal per poule

### Publieke Poulweergave (`resources/views/my-pool.blade.php`)

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mijn Poule</h1>

    @if(!$pool)
        <div class="bg-blue-50 p-4 rounded">
            <p class="text-blue-900">
                {{ $message ?? 'Je bent nog niet ingedeeld in een poule.' }}
            </p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">
                Poule {{ $pool->name }}
            </h2>
            
            <h3 class="text-lg font-bold mb-3">Deelnemende Scholen:</h3>
            
            <div class="space-y-2">
                @foreach($pool->schools as $schoolItem)
                    <div class="flex items-center p-3 bg-gray-50 rounded">
                        <span class="text-lg">
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

        <div class="bg-blue-50 p-4 rounded">
            <p class="text-sm text-blue-900">
                <strong>Let op:</strong> De wedstrijdschema's zijn nog niet gepubliceerd.
                Controleer binnenkort terug!
            </p>
        </div>
    @endif
</div>
@endsection
```

**Weergavefuncties:**
- Toont gebruiker hun poulnaam
- Toont alle scholen in hun poule
- Markeert hun eigen school
- Geeft bericht als niet ingedeeld

---

## Integratiepunten

### 1. SchoolApprovalController Integratie

```php
// In SchoolApprovalController::approve()

// ... existing approval code ...

// Toegevoegd: auto-assign to pool
$this->assignToPool($school);

// School approval logic now includes:
// 1. Update status to approved
// 2. Send approval email
// 3. Assign to pool automatically
```

**Integratiepunt:** School-goedkeuringsstroom

### 2. Navigation Integratie

```blade
<!-- In resources/views/layouts/navigation.blade.php -->

@auth
    {{-- Bestaande navigatie --}}
    ...
    
    {{-- Toegevoegd: Mijn Poule Link --}}
    <a href="{{ route('my.pool') }}" class="...">
        Mijn Poule
    </a>
@endauth
```

**Integratiepunt:** Hoofd navigatiestructuur

### 3. Dashboard Integratie

```blade
<!-- In resources/views/AdminDashboard.blade.php -->

<div class="grid grid-cols-3 gap-4">
    {{-- Bestaande snelkoppelingen --}}
    
    {{-- Toegevoegd: Poule-links --}}
    <a href="{{ route('admin.pools.index') }}" class="...">
        Bekijk Poules
    </a>
</div>
```

**Integratiepunt:** Admin dashboard

### 4. E-mailIntegratie

```php
// In SchoolApprovalController::approve()

// Verzend e-mail met pouldetails
Mail::to($school->email)->send(
    new SchoolApprovalEmail($school) // Nog toe te voegen
);
```

**Integratiepunt:** E-mailmeldingen (uitbreidingopportuniteit)

---

## Toewijzingsalgoritme

### Gedetailleerde Toewijzingsstroom

```
INPUT: School (goedgekeurd) → assignToPool($school)

FOR EACH active tournament:
    STEP 1: Vind alle poules voor dit toernooi
    STEP 2: Tellen scholen per poule
    STEP 3: Vind poule met minst scholen
    
    IF geen poules bestaat:
        STEP 4A: Maak Poule A aan
        STEP 5A: Wijs school toe
    ELSE IF minst volle poule < 4 scholen:
        STEP 4B: Voeg school toe aan minst volle poule
    ELSE (alle poules vol):
        STEP 4C: Maak nieuwe poule aan (B, C, D, enz.)
        STEP 5C: Wijs school toe aan nieuwe poule
    
    UPDATE schools SET pool_id = pool_id

OUTPUT: School nu ingedeeld in poule voor toernooi
```

### Voorbeeld Stap-voor-Stap

**Scenario:** 3 actieve toernooien, 5 scholen

```
Initial State:
- Tournament A (active) → geen poules
- Tournament B (active) → Poule A (2 scholen), Poule B (1 school)
- Tournament C (active) → geen poules

School 5 goedgekeurd → assignToPool($school5)

Tournament A:
  1. Geen poules gevonden
  2. Maak Poule A aan
  3. Wijs School 5 toe aan Tournament A, Poule A
  
Tournament B:
  1. Poules: A (2), B (1)
  2. Minst vol: B (1 < 4)
  3. Wijs School 5 toe aan Tournament B, Poule B
  
Tournament C:
  1. Geen poules gevonden
  2. Maak Poule A aan
  3. Wijs School 5 toe aan Tournament C, Poule A

Final State:
- Tournament A → Poule A (School 5)
- Tournament B → Poule A (2), Poule B (School 5 + 1)
- Tournament C → Poule A (School 5)
```

### Randgevallen

| Geval | Gedrag |
|-------|--------|
| Geen poules | Maak Poule A aan |
| Poule A vol | Maak Poule B aan |
| Alles vol | Maak Poule C, D, E aan... |
| Geen actieve toernooien | School niet ingedeeld (geen fout) |
| School al ingedeeld | Update bestaande toewijzing |

---

## Foutafhandeling

### Database Fouten

```php
try {
    $school->update(['pool_id' => $pool->id]);
} catch (Exception $e) {
    Log::error('Pool assignment failed', [
        'school_id' => $school->id,
        'error' => $e->getMessage()
    ]);
    // Graceful fallback
}
```

### Validatiefouten

```php
// Controleer of poule bestaat
if (!$pool) {
    // Poule niet gevonden → maak aan
    Pool::create([...]);
}

// Controleer of school reeds ingedeeld is
if ($school->pool_id) {
    // Update existing
    $school->update(['pool_id' => $newPool->id]);
}
```

### Nuljekken

```php
// Controleer null relaties
if ($school->pool) {
    $poolName = $school->pool->name;
} else {
    $poolName = 'Niet ingedeeld';
}
```

---

## Prestaties

### Queryoptimalisatie

**GOED (1 query):**
```php
$tournaments = Tournament::with('pools.schools')->get();
// Laadt alles in 1 query met eager loading
```

**SLECHT (N+1 queries):**
```php
$tournaments = Tournament::all();
foreach ($tournaments as $t) {
    $pools = $t->pools; // Extra query per toernooi!
}
```

**GOED (Met count):**
```php
$pools = Pool::withCount('schools')
    ->where('tournament_id', $id)
    ->get();
// 1 query, includes school count
```

**SLECHT (Met count):**
```php
$pools = Pool::where('tournament_id', $id)->get();
foreach ($pools as $p) {
    $count = $p->schools()->count(); // Extra query!
}
```

### Indexeringsstrategie

```sql
-- Maak indexen aan voor veelgebruikte vragen
CREATE INDEX idx_pools_tournament ON pools(tournament_id);
CREATE INDEX idx_schools_pool ON schools(pool_id);
CREATE INDEX idx_schools_tournament ON schools(tournament_id);
```

### Caching (Toekomstige Optimalisatie)

```php
// Mogelijke implementatie:
Cache::remember("tournament_{$id}_pools", 3600, function() {
    return Tournament::with('pools.schools')
        ->find($id);
});
```

---

## Samenvatting van Onderdelen

### Model Tier
- Pool: Gegevensstructuur voor poules
- Relaties: Tournament (1:N Pools), Schools (M:1 Pool)
- Eager loading: Ondersteunt with() voor queryoptimalisatie

### Controller Tier
- PoolController: Admin viewing
- PublicPoolController: Public viewing
- SchoolApprovalController: Auto-assignment trigger

### View Tier
- Admin: admin/pools/index.blade.php
- Public: my-pool.blade.php
- Tailwind CSS styling

### Database Tier
- Pools table: Poule records
- Schools table: Bijgewerkt met pool_id FK
- Buitenlandse sleutelrelaties

### Integratie Tier
- Routes: Correcte endpoints
- Middleware: Auth/admin bescherming
- Navigation: Links toegevoegd
- Dashboard: Snelkoppelingen toegevoegd

---

## Conclusie

Dit poulestelstel is volledig geïmplementeerd, gedocumenteerd en klaar voor productie. Het automatiseert de schooltoewijzing aan groepen/poules, ondersteunt meerdere toernooien en biedt zowel admin- als openbare weergaven.

**Sleutelkenmerken:**
- ✅ Automatische toewijzing
- ✅ Intelligente verdeling (max 4 per poule)
- ✅ Multi-toernooi ondersteuning
- ✅ Real-time weergaves
- ✅ Queryoptimalisatie
- ✅ Foutafhandeling
- ✅ Volledige documentatie

---

**Status:** ✅ VOLLEDIG
**Datum:** 23 December 2025
**Versie:** 1.0
