# Poule Indeling & Automatische Wedstrijdschema

## Overzicht

Dit systeem automatiseert het proces van het indelen van scholen in poules en het aanmaken van wedstrijdschema's. De workflow is als volgt:

### 📋 Stap-voor-stap Proces

#### **Stap 1: Scholen Aanmelden & Goedkeuren**
1. Scholen melden zich aan via `/scholen/registreren`
2. Status wordt ingesteld op `pending`
3. Admin keurt aanmelding goed via `/admin/scholen`
4. Status wijzigt naar `approved`

#### **Stap 2: Automatische Poule Indeling**
1. Ga naar `/admin/toernooien/{tournament}/poules`
2. Klik op **"📋 Scholen indelen in poules"**
3. Het systeem:
   - Verzamelt alle `approved` scholen zonder `pool_id`
   - Groepeert scholen per categorie (`3/4`)
   - Maakt automatisch poules aan met max **4 scholen per poule**
   - Wijst scholen toe aan hun poule
   - Werkt met round-robin systeem (elke school tegen elke andere school)

#### **Stap 3: Automatische Wedstrijdaanmaak**
1. Klik op **"⚽ Wedstrijden aanmaken"**
2. Het systeem:
   - Itereert over alle poules
   - Maakt round-robin wedstrijden aan (elke school tegen elke andere school 1x)
   - Voegt wedstrijden toe met status `scheduled`
   - Controleert op duplicaten

#### **Stap 4: Wedstrijdschema Invullen**
1. Ga naar `/admin/schedule`
2. Voor elke wedstrijd:
   - Selecteer datum/tijd
   - Selecteer veld
   - Selecteer scheidsrechter
   - Sla op

---

## 🏗️ Technische Architectuur

### Database Schema

```
Tournaments (1)
    ↓
    ├── Pools (n) - "Categorie 3/4 - Poule 1", "Categorie 3/4 - Poule 2", etc.
    │   ↓
    │   └── Schools (1-4)
    │
    └── GameMatches (n)
        ├── home_school_id
        ├── away_school_id
        ├── status: 'scheduled'
        └── (optioneel: referee_id, field_id, match_date, scheduled_time)
```

### Models & Relaties

**Tournament Model:**
```php
public function pools(): HasMany {
    return $this->hasMany(Pool::class);
}

public function matches(): HasMany {
    return $this->hasMany(GameMatch::class);
}
```

**Pool Model:**
```php
public function schools(): HasMany {
    return $this->hasMany(School::class);
}

public function tournament(): BelongsTo {
    return $this->belongsTo(Tournament::class);
}
```

**School Model:**
```php
public function pool(): BelongsTo {
    return $this->belongsTo(Pool::class);
}
```

---

## 🎯 Routes

### Admin Routes

| Method | Route | Beschrijving |
|--------|-------|-------------|
| `GET` | `/admin/toernooien/{tournament}/poules` | Toon poule management pagina |
| `POST` | `/admin/toernooien/{tournament}/poules/allocate` | Indeel scholen in poules |
| `POST` | `/admin/toernooien/{tournament}/poules/create-matches` | Maak wedstrijden aan |
| `POST` | `/admin/toernooien/{tournament}/poules/reset` | Reset alles (verwijder poules & wedstrijden) |
| `DELETE` | `/admin/toernooien/{tournament}/poules/{poolId}` | Verwijder specifieke poule |

---

## 💡 Voorbeelden

### Voorbeeld 1: 16 Scholen indelen

**Input:**
- 16 goedgekeurde scholen (category 3/4)
- Tournament ID: 1

**Proces:**
1. Systeem creëert: `Categorie 3/4 - Poule 1`, `Categorie 3/4 - Poule 2`, `Categorie 3/4 - Poule 3`, `Categorie 3/4 - Poule 4`
2. Verdeling:
   - Poule 1: 4 scholen
   - Poule 2: 4 scholen
   - Poule 3: 4 scholen
   - Poule 4: 4 scholen

**Wedstrijden per poule:**
- 4 scholen = 6 wedstrijden per poule (C(4,2) = 6)
- Totaal: 4 poules × 6 wedstrijden = 24 wedstrijden

### Voorbeeld 2: 10 Scholen (2 categorieën)

**Input:**
- 6 scholen categorie 3
- 4 scholen categorie 4

**Poules aangemaakt:**
- `Categorie 3 - Poule 1`: 4 scholen → 6 wedstrijden
- `Categorie 3 - Poule 2`: 2 scholen → 1 wedstrijd
- `Categorie 4 - Poule 1`: 4 scholen → 6 wedstrijden

**Totaal:** 13 wedstrijden

---

## 🔧 Services

### PoolAllocationService

**Methoden:**

1. **`allocateSchoolsToPoolsAndCreateMatches(Tournament $tournament)`**
   - Indeel alle goedgekeurde scholen in poules
   - Maak automatisch wedstrijden aan
   - Return: `['success' => bool, 'message' => string, 'pools_created' => int, 'matches_created' => int]`

2. **`checkAndCreateMatches(Tournament $tournament)`**
   - Controleer bestaande poules
   - Maak wedstrijden aan voor poules die nog geen wedstrijden hebben
   - Return: `['success' => bool, 'message' => string, 'matches_created' => int]`

---

## 📊 Status Codes

### School Status
- `pending` - Aanmelding in behandeling
- `approved` - Goedgekeurd en klaar om in te delen
- `rejected` - Afgewezen

### Match Status
- `scheduled` - Ingepland (kan nog datum/time/scheidsrechter krijgen)
- `completed` - Wedstrijd gespeeld
- `cancelled` - Geannuleerd

### GameMatch Fields
```php
[
    'tournament_id' => Tournament ID,
    'home_school_id' => School ID,
    'away_school_id' => School ID,
    'home_goals' => Integer (null bij aanmaak),
    'away_goals' => Integer (null bij aanmaak),
    'status' => 'scheduled',
    'match_date' => DateTime (null bij aanmaak),
    'scheduled_time' => DateTime (null bij aanmaak),
    'duration_minutes' => Integer,
    'field_id' => Integer (null),
    'referee_id' => Integer (null),
    'notes' => String (null)
]
```

---

## ✅ Features

- ✅ Automatische categorisering scholen
- ✅ Max 4 scholen per poule
- ✅ Round-robin wedstrijdschema (elke school vs elke ander)
- ✅ Voorkoming van duplicate wedstrijden
- ✅ Reset functie (verwijder alles en start opnieuw)
- ✅ Poule deletion (verwijder specifieke poule)
- ✅ Admin panel integratie
- ✅ Error handling en validatie
- ✅ Responsive UI met Tailwind CSS

---

## 🐛 Troubleshooting

### "Geen goedgekeurde scholen beschikbaar"
- ✓ Controleer dat scholen status `approved` hebben
- ✓ Controleer dat scholen geen `pool_id` hebben (nog niet ingedeeld)

### "Geen nieuwe wedstrijden nodig"
- ✓ Wedstrijden bestaan al
- ✓ Poules zijn leeg

### Poules niet zichtbaar
- ✓ Run `php artisan view:clear`
- ✓ Check browser cache (Ctrl+Shift+Delete)

---

## 📝 Database Queries

### Alle goedgekeurde scholen zonder poule:
```sql
SELECT * FROM schools WHERE status = 'approved' AND pool_id IS NULL;
```

### Wedstrijden per poule:
```sql
SELECT COUNT(*) 
FROM matches 
WHERE (home_school_id IN (SELECT id FROM schools WHERE pool_id = ?) 
       OR away_school_id IN (SELECT id FROM schools WHERE pool_id = ?));
```

### Scholen per poule:
```sql
SELECT s.* FROM schools s WHERE s.pool_id = ? ORDER BY s.name;
```

---

## 🎓 Leerwaarde

Dit systeem demonstreert:
- Laravel service classes voor business logic
- Relatiebeheer in Eloquent
- Automatische gegevensgeneratie
- Round-robin algoritme implementatie
- Admin UI best practices
- Form validation en error handling
