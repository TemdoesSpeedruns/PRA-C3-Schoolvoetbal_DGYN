# ✅ POULE SYSTEEM - VEREENVOUDIGD & WERKEND

## 🎯 Wat Is Het Poule Systeem?

Het poule systeem verdeel alle goedgekeurde scholen automatisch in groepen (poules) voor toernooien:

- **Automatisch**: Geen handmatige configuratie nodig
- **Intelligent**: Verdelen in groepen van max 4 scholen
- **Real-time**: Toewijzing gebeurt direct bij goedkeuring
- **Eenvoudig**: Geen categorieën, gewoon groepen A, B, C, D...

---

## 🚀 Hoe Het Werkt (Simpel)

### Stap 1: Admin keurt een school goed
```
Admin gaat naar: /admin/scholen
Admin klikt: "Goedkeuren" op een school
```

### Stap 2: Automatische toewijzing
```
Het systeem:
1. Zoekt EERSTE actieve toernooi
2. Telt scholen in huidige poules
3. Zoekt poule met minst scholen (max 4)
4. Wijs school toe
5. Maakt nieuwe poule (B, C, D) als nodig
```

### Stap 3: School ziet hun poule
```
School gaat naar: /mijn-poule
School ziet: Welke scholen in hun groep
```

---

## 📁 Database Schema

### Pools tabel
```sql
id (PK)
tournament_id (FK)
name (A, B, C, D...)
created_at
updated_at
```

### Schools tabel
```sql
id (PK)
name
email
status (pending, approved, rejected)
pool_id (FK - NULL tot goedgekeurd)
```

---

## 🔧 Code Structuur

### Model: Pool.php
```php
public function tournament() -> belongsTo Tournament
public function schools() -> hasMany School
```

### Model: School.php
```php
public function pool() -> belongsTo Pool
```

### Model: Tournament.php
```php
public function pools() -> hasMany Pool
```

### Controller: SchoolApprovalController
```php
approve($id):
  → school.status = 'approved'
  → assignToPool($school)  // ← AUTOMATISCH
```

### Toewijzingslogica: assignToPool()
```php
1. Vind eerste ACTIEVE toernooi
2. Haal alle poules op (gesorteerd op aantal scholen)
3. Vind eerste poule met < 4 scholen
4. Als geen poule: maak poule A aan
5. Als alle vol: maak nieuwe poule (B, C, D...) aan
6. Update school.pool_id
```

---

## 📊 Routes

### Admin
- `GET /admin/poules` → Ziet alle poules (PoolController@index)

### Publiek
- `GET /mijn-poule` → Ziet eigen poule (PublicPoolController@myPool)

---

## ✅ Testing Checklist

- [x] Database migratie
- [x] Pool model
- [x] School relatie
- [x] Tournament relatie
- [x] SchoolApprovalController logica
- [x] Admin pools view
- [x] Public my-pool view
- [x] Routes ingesteld
- [x] Seeders werkend

---

## 🧪 Handmatig Testen

### Test Case 1: Single Pool
```
1. Ga naar /admin/scholen
2. Keur "School Amsterdam" goed
   → Poule A aangmaakt
   → School Amsterdam → Poule A

3. Keur "School Rotterdam" goed
   → Poule A al aanwezig
   → School Rotterdam → Poule A

Resulaat: Beide in Poule A ✅
```

### Test Case 2: Multiple Pools
```
1. Keur 4 scholen goed
   → Alle in Poule A (vol!)

2. Keur 5e school goed
   → Poule B aangemaakt
   → 5e school → Poule B

Resultaat: A=4, B=1 ✅
```

### Test Case 3: Check Poules
```
1. Ga naar /admin/poules
2. Ziet all poules met scholen
3. Ziet counts per poule

Resultaat: Alle poules zichtbaar ✅
```

### Test Case 4: School bekijkt poule
```
1. School logt in (of via /mijn-poule zonder login)
2. Ziet eigen poule
3. Ziet andere scholen in poule

Resultaat: Poule informatie correct ✅
```

---

## ⚙️ Troubleshooting

### Poules worden niet aangemaakt?
- Check: Is er een ACTIEF toernooi?
- Check: `Tournament::where('status', 'active')`

### School wordt niet ingedeeld?
- Check: School status = 'approved'?
- Check: assignToPool() wordt aangeroepen?
- Check: Logs in storage/logs/laravel.log

### View toont geen poules?
- Check: `php artisan cache:clear`
- Check: Database migratie ran?
- Check: Poules tabel populated?

---

## 📝 Summary

Dit is een **eenvoudig, werkend poule-systeem** dat:
- ✅ Automatisch scholen indeel
- ✅ Geen handmatige configuratie nodig
- ✅ Schalen tot oneindig veel poules
- ✅ Real-time updates
- ✅ User-friendly views

**Het is nu klaar en werkend!** 🎉
