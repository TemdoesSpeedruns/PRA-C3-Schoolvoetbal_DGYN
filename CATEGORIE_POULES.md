# 🎯 CATEGORIE-GEBASEERDE POULES - UITGEBREIDE GIDS

## Overzicht

Het poulesysteem is nu uitgebreid met **leeftijdscategorie ondersteuning**. Dit betekent dat scholen automatisch in aparte poules worden ingedeeld op basis van hun leeftijdscategorie.

---

## 📚 Beschikbare Categorieën

```
3/4     → Groep 3 en 4 (7-8 jaar oud)
5/6     → Groep 5 en 6 (9-10 jaar oud)
7/8     → Groep 7 en 8 (11-12 jaar oud)
brugklas → Brugklas (12-13 jaar oud)
```

---

## 🔧 Hoe Het Werkt

### Stap 1: Categorie Instellen
```
Admin gaat naar: /admin/scholen/{id}/edit
Admin selecteert: Leeftijdscategorie (3/4, 5/6, 7/8, brugklas)
Admin klikt: Bijwerken
```

### Stap 2: Automatische Indeling
```
School: Groep 3/4 - Goedgekeurd
  ↓
Systeem controleert: Actieve toernooien
  ↓
Voor ELK toernooi:
  - Zoek/maak Poule A voor Categorie "3/4"
  - Is Poule A vol (4 scholen)? → Maak Poule B
  - Voeg school toe aan minst volle poule
```

### Stap 3: Zichtbaarheid
```
Admin ziet: /admin/poules
  - Voetbal 2025
    - 📚 Categorie: 3/4
      - Poule A (3 scholen)
      - Poule B (2 scholen)
    - 📚 Categorie: 5/6
      - Poule A (4 scholen)
    - 📚 Categorie: 7/8
      - Poule A (1 school)

School ziet: /mijn-poule
  - Poule: A
  - Leeftijdscategorie: 3/4
  - Medescholen: (3 anderen in dezelfde poule)
```

---

## 🎯 Praktische Voorbeelden

### Voorbeeld 1: Voetbal 2025 - 12 Deelnemers

**Aanmeldingen:**
```
1. School A - Groep 3/4  ← Categorie
2. School B - Groep 3/4
3. School C - Groep 3/4
4. School D - Groep 3/4
5. School E - Groep 3/4
6. School F - Groep 5/6
7. School G - Groep 5/6
8. School H - Groep 5/6
9. School I - Groep 7/8
10. School J - Groep 7/8
11. School K - Groep 7/8
12. School L - Brugklas
```

**Automatische Indeling (Na Goedkeuring):**

```
📚 VOETBAL 2025 - CATEGORIE: 3/4
├─ Poule A (4 scholen): School A, B, C, D
└─ Poule B (1 school): School E

📚 VOETBAL 2025 - CATEGORIE: 5/6
└─ Poule A (3 scholen): School F, G, H

📚 VOETBAL 2025 - CATEGORIE: 7/8
└─ Poule A (3 scholen): School I, J, K

📚 VOETBAL 2025 - CATEGORIE: BRUGKLAS
└─ Poule A (1 school): School L
```

**Voordelen:**
- ✅ Eerlijke verdeling per leeftijdsgroep
- ✅ Gelijke speelniveaus
- ✅ Veilig competitiesniveau
- ✅ Automatisch georganiseerd

---

## 🖥️ Beheersinterface

### Scholen Beheren
```
Route: /admin/scholen

Kolom "Categorie" toont:
- 3/4 (paarse badge)
- 5/6 (paarse badge)
- 7/8 (paarse badge)
- brugklas (paarse badge)
- "Niet ingesteld" (als leeg)

Actie: Klik "✏️ Bewerk" om categorie in te stellen
```

### Poules Bekijken
```
Route: /admin/poules

Groepering per categorie:
Voetbal 2025
  📚 Categorie: 3/4
    ├─ Poule A
    └─ Poule B
  📚 Categorie: 5/6
    └─ Poule A
  📚 Categorie: 7/8
    └─ Poule A
  📚 Categorie: brugklas
    └─ Poule A
```

---

## 📋 Checklist voor Gebruik

```
☐ 1. Ga naar /admin/scholen
☐ 2. Voor elke aangemelde school:
     ☐ Klik "✏️ Bewerk"
     ☐ Selecteer Leeftijdscategorie (3/4, 5/6, 7/8, brugklas)
     ☐ Klik "Bijwerken"
☐ 3. Keur scholen goed (status → "Goedgekeurd")
     ☐ Systeem wijst AUTOMATISCH in per categorie toe
☐ 4. Ga naar /admin/poules
☐ 5. Controleer indeling per categorie
```

---

## 🚀 Setup Stappen

### Stap 1: Database Gereed
✅ Migratie is al uitgevoerd
✅ `category` kolom op `pools` tabel
✅ `category` kolom op `schools` tabel

### Stap 2: Toernooi Actief
```bash
# Ga naar /admin/toernooien
# Zorg dat toernooi "actief" status heeft
```

### Stap 3: Categorieën Instellen
```bash
# Voor elke school:
# 1. Ga naar /admin/scholen
# 2. Klik "✏️ Bewerk"
# 3. Selecteer categorie
# 4. Klik "Bijwerken"
```

### Stap 4: Goedkeuring
```bash
# Ga naar /admin/scholen
# Voor elke school:
# - Klik "✓ Goedkeuren"
# - Systeem wijst AUTOMATISCH toe per categorie
```

### Stap 5: Verifiëring
```bash
# Ga naar /admin/poules
# Controleer alle categorieën zijn ingedeeld
```

---

## 💡 Technische Details

### Database Schema

```sql
-- Pools tabel
CREATE TABLE pools (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tournament_id BIGINT FOREIGN KEY,
    name VARCHAR(255),      -- A, B, C, D...
    category VARCHAR(255),  -- 3/4, 5/6, 7/8, brugklas
    timestamps
);

-- Schools tabel
ALTER TABLE schools ADD COLUMN category VARCHAR(255);
```

### Model Structuur

```php
// Pool.php
protected $fillable = [
    'tournament_id',
    'name',
    'category',  // ← Nieuw
];

// School.php
protected $fillable = [
    'name',
    'contact_person',
    'email',
    'status',
    'pool_id',
    'category',  // ← Nieuw
];
```

### Toewijzingslogica

```php
private function assignToPool(School $school): void
{
    // Gebruik schoolcategorie voor toewijzing
    $category = $school->category ?? 'all';
    
    // Maak/Vind poule PER CATEGORIE
    $pool = Pool::where('tournament_id', $id)
               ->where('category', $category)  // ← Categorie filter
               ->withCount('schools')
               ->orderBy('schools_count')
               ->first();
    
    // Indeling gebeurt per categorie!
}
```

---

## 🎯 Voorbeeldworkflow

### Scenario: Schoolvoetbaltoernooi Organiseren

**Week 1: Registratie**
```
- 20 scholen schrijven in
- Status: pending
- Categorie: nog niet ingesteld
```

**Week 2: Categorisering**
```
Admin werk: /admin/scholen
- School A: Groep 3/4
- School B: Groep 3/4
- School C: Groep 5/6
- School D: Groep 5/6
- School E: Groep 7/8
- ...etc
```

**Week 3: Goedkeuring & Indeling**
```
Admin keur 5 scholen goed (status → "approved")
Systeem AUTOMATISCH:
- Categorie 3/4 → Poule A (3 scholen)
- Categorie 5/6 → Poule A (2 scholen)

Admin keur 5 meer goed
Systeem AUTOMATISCH:
- Categorie 3/4 → Poule A vol → Maak Poule B (2 meer)
- Categorie 5/6 → Poule A vol → Maak Poule B (2 meer)
- Categorie 7/8 → Poule A (2 scholen)
```

**Week 4: Toernooi Voorbereiding**
```
Admin bezoekt: /admin/poules
Ziet complete indeling per categorie:
- Categorie 3/4: Poule A (4), Poule B (4)
- Categorie 5/6: Poule A (4), Poule B (4)
- Categorie 7/8: Poule A (3), Poule B (2)
- Categorie brugklas: Poule A (2)

Perfect balanced per leeftijd! ✓
```

**Week 5: Publieke Info**
```
Scholen bezoeken: /mijn-poule
Zien:
- Mijn poule: A
- Categorie: 3/4
- Medescholen: (3 andere in Poule A)
- Klaar om te spelen!
```

---

## ⚠️ Belangrijk

### Categorie MOET Ingesteld Zijn
```
Zonder categorie → School krijgt categorie "all"
Met categorie → School krijgt correcte indeling

AANBEVELING: Set altijd categorie voordat je goedkeurt!
```

### Verwijdering & Wijziging
```
Categorie wijzigen = Nieuwe poule indeling
(School krijgt nieuwe toewijzing bij volgende goedkeuring)

Categorie verwijderen = Fallback naar "all"
(Kan onbedoelde indeling veroorzaken)
```

### Meerdere Toernooien
```
Niet doen: Zelfde school in zelfde poule voor 2 toernooien
Wel doen: School kan in Poule A voor voetbal & Poule B voor lijnbal
         (Per toernooi aparte indeling!)
```

---

## 🔄 Update Migratie

De migratie `2025_12_24_000000_add_category_to_pools_and_schools.php`:
- ✅ Voegde `category` kolom toe aan `pools`
- ✅ Voegde `category` kolom toe aan `schools`
- ✅ Voegde indexen toe voor prestatie
- ✅ Rollback staat in `down()`

---

## 📊 Data Structuur Voorbeeld

```
Tournament: "Voetbal 2025"
├─ Pool A (Category: 3/4)
│  ├─ School 1 (Category: 3/4)
│  ├─ School 2 (Category: 3/4)
│  ├─ School 3 (Category: 3/4)
│  └─ School 4 (Category: 3/4)
│
├─ Pool B (Category: 3/4)
│  ├─ School 5 (Category: 3/4)
│  └─ School 6 (Category: 3/4)
│
├─ Pool A (Category: 5/6)
│  ├─ School 7 (Category: 5/6)
│  ├─ School 8 (Category: 5/6)
│  └─ School 9 (Category: 5/6)
│
└─ Pool A (Category: 7/8)
   ├─ School 10 (Category: 7/8)
   └─ School 11 (Category: 7/8)
```

---

## ✨ Voordelen

✅ **Eerlijke Verdeling**
- Scholen spelen tegen gelijke niveaus
- Niet te sterke tegen te zwakke

✅ **Veilig Spelniveau**
- Groep 3/4 speelt met groep 3/4
- Geen fysieke mismatch

✅ **Organisatorisch Voordeel**
- Scheidsrechters kennen speelstijlen
- Wedstrijdschema makkelijker te plannen

✅ **Transparantie**
- Scholen zien hun categorie
- Weten tegen wie ze spelen

✅ **Automatisering**
- Geen handmatig indelen nodig
- Minder administratie
- Geen fouten

---

## 🎓 FAQs

**V: Wat als school geen categorie heeft?**
A: Standaard "all" - adviseert niet! Stel altijd categorie in.

**V: Kan ik categorie later wijzigen?**
A: Ja, ga naar /admin/scholen → Bewerk → Wijzig categorie

**V: Voegt categorie wijziging direct poule toe?**
A: Nee, school krijgt nieuwe poule bij volgende goedkeuring

**V: Kunnen scholen in meerdere categorieën?**
A: Nee, 1 school = 1 categorie, maar meerdere poules per toernooi

**V: Wat gebeurt er als ik categorie wis?**
A: School wordt "all" - niet aanbevolen!

---

## 🚀 Volgende Stappen

1. ✅ Migratie uitgevoerd
2. ✅ Views bijgewerkt
3. ✅ Controller logica aangepast
4. ⏳ **Categorieën instellen** (uw actie)
5. ⏳ **Scholen goedkeuren** (uw actie)
6. ⏳ **Indeling verifiëren** (uw actie)

---

## 📞 Support

**Probleem: School zit niet in correcte categorie poule**
→ Controleer: school.category ingesteld?
→ Controleer: staat school op "approved"?

**Probleem: Poules niet per categorie gegroepeerd**
→ Controleer: /admin/poules laadt correct?
→ Kijk in browser console op errors

**Probleem: Ik kan categorie niet wijzigen**
→ Zorg dat je admin bent
→ Controleer school edit pagina laadt

---

## 🎉 Klaar!

Het categorie-gebaseerde poulesysteem is volledig geïmplementeerd en klaar voor gebruik!

**Nu nog te doen:**
1. Categorieën instellen voor alle scholen
2. Scholen goedkeuren
3. Poules worden automatisch ingedeeld! ✨

---

**Geïmplementeerd:** 24 December 2025
**Status:** ✅ KLAAR
**Versie:** 2.0
