# 🚀 Snelle Start Gids - Poule Systeem

## Stap 1: Voer Database Migratie Uit (ESSENTIEEL)
```bash
cd c:\laragon\www\PRA-C3-Schoolvoetbal_DGYN
php artisan migrate
```

Dit creëert de poule-infrastructuur. **U moet dit eerst doen!**

## Stap 2: Maak/Controleer Actief Toernooi
1. Zorg dat u een toernooi hebt met `status = 'active'`
2. Ga naar `/admin/toernooien`
3. Controleer bestaande toernooien of maak een nieuwe
4. Zorg dat het zegt "🟢 Actief" of heeft status "actief"

## Stap 3: Keur Scholen Goed
1. Ga naar `/admin/scholen`
2. Klik "Approve" bij een wachtende school
3. School wordt automatisch aan Poule A toegewezen
4. Email wordt naar school verzonden met goedkeuringsbericht

## Stap 4: Bekijk Poules
1. Ga naar `/admin/poules` OF
2. Klik "Bekijk Poules" op Admin Dashboard
3. Zie alle poules en hun toegewezen scholen

## Stap 5: Test Verdeling (Optioneel)
Keur 5+ scholen goed om te zien:
- Poule A krijgt eerste 4 scholen
- Poule B wordt automatisch aangemaakt
- School 5 gaat naar Poule B
- Blijf goedkeuren om de verdeling te zien

---

## Wat Elke Route Doet

| Route | Wie | Wat |
|-------|-----|-----|
| `/admin/poules` | Admin | Bekijk alle poules & scholen |
| `/admin/scholen` | Admin | Beheer scholen (met poule weergegeven) |
| `/mijn-poule` | Publiek | Zie uw pouletoewijzing |
| `/admin/toernooien` | Admin | Beheer toernooien |

---

## Achter de Schermen (Wat Gebeurt Bij Goedkeuring)

1. ✓ Schoolstatus gewijzigd in "goedgekeurd"
2. ✓ Poulesysteem vindt actieve toernooien
3. ✓ Voor elk toernooi:
   - Maakt Poule A aan als deze ontbreekt
   - Telt scholen in elke poule
   - Plaatst school in minst volle poule
   - Maakt Poule B aan als Poule A 4+ scholen heeft
4. ✓ Email verzonden naar school
5. ✓ School kan nu hun poule zien

---

## Veelgestelde Vragen

**V: Waarom zit mijn school niet in een poule?**
A: Zorg dat:
- Toernooistatus is "actief" (niet "afgerond")
- School is "goedgekeurd" (niet wachtend/afgewezen)
- U `php artisan migrate` hebt uitgevoerd

**V: Kan ik poules handmatig wijzigen?**
A: Nog niet - zou rechtstreeks bewerken via database of toekomstige functie vereisen. Huidig systeem is volledig automatisch.

**V: Wat gebeurt er als ik een school verwijder?**
A: Deze wordt onmiddellijk uit de poule verwijderd. Andere scholen blijven in hun poules.

**V: Kan één school in meerdere poules zijn?**
A: Nee - slechts één poule per school per toernooi.

**V: Wat is de maximale poulegrootte?**
A: 4 scholen per poule. Wanneer de 5e school wordt goedgekeurd, wordt Poule B aangemaakt.

---

## Probleemoplossing

### Probleem: Migratie mislukt
**Oplossing**: 
- Controleer of tabellen al bestaan: `php artisan migrate:status`
- Probeer terugdraaien: `php artisan migrate:rollback`
- Migreer dan opnieuw: `php artisan migrate`

### Probleem: Scholen worden niet toegewezen
**Controleer**:
1. Hebt u een toernooi met status="actief"?
2. Wordt de school daadwerkelijk goedgekeurd?
3. Controleer database: `SELECT * FROM schools WHERE id = X;` (zou pool_id moeten hebben)

### Probleem: /admin/poules toont geen poules
**Controleer**:
1. Keur minstens één school goed
2. Controleer dat toernooistatus "actief" is
3. Ga naar: `SELECT * FROM pools;` in database

### Probleem: "Bekijk Poules" knop niet zichtbaar
**Oplossing**: 
- Wis cache: `php artisan config:cache`
- Zorg dat u admin bent: Controleer `/admin/scholen` toegang

---

## Bestandslocaties

Iets nodig om te bewerken?

| Doel | Bestand |
|------|---------|
| Pouletoewijzingslogica | `app/Http/Controllers/SchoolApprovalController.php` |
| Admin pouleweergave | `app/Http/Controllers/PoolController.php` |
| Poulemodel | `app/Models/Pool.php` |
| Admin poulesweergave | `resources/views/admin/pools/index.blade.php` |
| Publieke poulesweergave | `resources/views/my-pool.blade.php` |
| Routes | `routes/web.php` (zoek naar "admin.pools") |

---

## Testchecklist

Gebruik dit om te verifiëren dat alles werkt:

```
1. ☐ Voer uit: php artisan migrate (Controleer op succesmeldingen)
2. ☐ Controleer database: pools tabel bestaat
3. ☐ Bezoek /admin/toernooien - zie actief toernooi
4. ☐ Bezoek /admin/scholen - zie scholen
5. ☐ Keur school #1 goed - let op succesmeldingen
6. ☐ Controleer of email is verzonden (indien geconfigureerd)
7. ☐ Bezoek /admin/poules - zie Poule A met school #1
8. ☐ Keur scholen #2, #3, #4 goed - allemaal naar Poule A
9. ☐ Keur school #5 goed - Poule B zou moeten worden aangemaakt
10. ☐ Bezoek /mijn-poule - zie uw poule (als u school_id kunt instellen)
11. ☐ Bezoek /admin/poules - zie 2 poules met verdeelde scholen
12. ☐ Bekijk poulebeheer: Dashboard → Bekijk Poules knop
```

---

## Na Migratie

Nadat de migratie klaar is, is alles anders automatisch:
- ✅ Modellen ingesteld
- ✅ Controllers klaar
- ✅ Views gemaakt
- ✅ Routes geconfigureerd
- ✅ Admin dashboard bijgewerkt

**Keur gewoon scholen goed en kijk hoe de poules automatisch worden gevormd!**

---

## Volgende: Geavanceerde Functies

Zodra basispoules werken, kunt u overwegen toe te voegen:
1. Op poule gebaseerde wedstrijdplanning
2. Poulescore/rankings
3. Handmatig poulemanagement (verplaats scholen)
4. Link scholen aan gebruikersaccounts
5. Poulejspecifieke regels

---

**Klaar?** Voer uit: `php artisan migrate`

Ga dan naar: `/admin/scholen` en keur een school goed! 🎉
