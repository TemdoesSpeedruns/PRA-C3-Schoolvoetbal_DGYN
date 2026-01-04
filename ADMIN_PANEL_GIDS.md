# 🛠️ ADMIN CONTROLE PANEL - GEÏNTEGREERD OVERZICHT

## 📋 Overzicht

Het nieuwe **Admin Controle Panel** combineert ALLE admin functies op **EEN PAGINA** met een intuïtief tabsysteem.

---

## 🎯 Hoe Te Gebruiken

### 1. Toegang
- Navigeer naar **`/admin/panel`** OF
- Klik op **"🛠️ Admin Panel"** in de navigatiebalk

### 2. Tabs Beschikbaar

| Tab | Functies | Icoon |
|-----|----------|-------|
| **Overzicht** | Dashboard met stats en recente items | 📋 |
| **Scholen** | Beheer en goedkeuren van scholen | 🏫 |
| **Toernooien** | Toernooi- en poolbeheer | 🎯 |
| **Poules** | Poule indeling per toernooi | 🔀 |
| **Wedstrijden** | Wedstrijdbeheer en scores | ⚽ |
| **Gebruikers** | Beheer van admin accounts | 👥 |

---

## 📊 Dashboard (Overzicht Tab)

### Statistics Cards (Bovenaan)
- 🏫 **Scholen Totaal** - Alle scholen in het systeem
- ✅ **Goedgekeurd** - Actieve scholen
- ⚽ **Toernooien** - Actieve toernooien
- 👥 **Admins** - Aantal admin accounts

### Recent Items
- **Lopende Aanmeldingen** - Scholen die nog goedgekeurd moeten worden
- **Recente Wedstrijden** - Meest recente matches

---

## 🏫 Scholen Tab

Beheer alle scholen:
- Status: Pending, Approved, Rejected
- Poule-indeling (indien aanwezig)
- Snelle acties (goedkeuren, bewerken)

---

## 🎯 Toernooien Tab

Overzicht van alle toernooien:
- Naam en type (voetbal/lijnbal)
- Status (active/completed/pending)
- Aantal poules en scholen
- Link naar gedetailleerd beheer

---

## 🔀 Poules Tab

Zie alle poules per toernooi:
- Toernooi naam
- Poule naam (A, B, C, D...)
- Aantal scholen per poule (max 4)
- Deelnemende scholen

---

## ⚽ Wedstrijden Tab

Beheer alle wedstrijden:
- Team 1 vs Team 2
- Status (gepland/ongepland)
- Planningstijd
- Bewerk-link

---

## 👥 Gebruikers Tab

Beheer admin accounts:
- Gebruiker naam en email
- Admin status
- Promote/Demote acties

---

## 🎨 Design Features

### Responsive Layout
- Desktop: Veel kolommen, alle info zichtbaar
- Tablet: Aangepaaste grid
- Mobiel: Stapelbare sections

### Color Coding
- 🟢 Groen = Goedgekeurd/Actief
- 🟡 Geel = In behandeling
- 🔴 Rood = Afgewezen
- 🔵 Blauw = Default info

### Quick Navigation
Elke tab heeft een link naar het **volledige overzicht** voor dieper beheer

---

## 🔗 Links Naar Gedetailleerde Pagina's

Elk tab bevat een **"Volledig Overzicht →"** link die je naar de specifieke beheerpagina brengt:

- Scholen → `/admin/scholen`
- Toernooien → `/admin/toernooien`
- Poules → `/admin/poules`
- Wedstrijden → `/admin/scores`
- Gebruikers → `/manage-users`

---

## 📱 Navigatie Aanpassingen

De navigatiebalk is vereenvoudigd:
- Oud: Meerdere "Admin..." links
- Nieuw: Één "🛠️ Admin Panel" link

Dit maakt navigatie schoner en sneller!

---

## ✅ Voordelen

✅ **Alles op één plek** - Geen gedoe met meerdere pagina's  
✅ **Schoon design** - Overzichtelijke tabs en cards  
✅ **Snelle acties** - Goedkeuren, bewerken, promoten direct vanuit panel  
✅ **Statistics** - Instant overzicht van systeemstatus  
✅ **Responsive** - Werkt op desktop, tablet en mobiel  
✅ **Deep links** - Kan nog naar detailpagina's voor uitgebreid beheer  

---

## 🚀 Routes

```
GET /admin/panel              → Main Admin Panel (nieuwe pagina)
GET /AdminDashboard           → Legacy dashboard (outdated)
GET /admin/scholen            → School detail beheer
GET /admin/toernooien         → Tournament detail beheer
GET /admin/poules             → Pool detail beheer
GET /admin/scores             → Match/Score detail beheer
GET /manage-users             → User detail beheer
```

---

## 💡 Pro Tips

1. **Tabs sneller laden** - Klik op tab buttons linksboven
2. **Snelle acties** - Goedkeuren van scholen direct uit Overzicht tab
3. **Sorteren** - Alle items zijn gesorteerd op "recent first"
4. **Paginering** - Gebruik "Volledig Overzicht" links voor meer items

---

## 🎉 Klaar!

Het admin panel is volledig functioneel en klaar voor gebruik!

Login als admin en ga naar `/admin/panel` of klik op **🛠️ Admin Panel** in de navigatiebalk.
