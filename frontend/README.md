# 🧃 Soft Drink Web Organizer – Frontend Design System

## 📌 Descriere

Acest document definește standardele de design pentru aplicația **Soft Drink Web Organizer**, realizată în PHP, HTML, CSS și JavaScript.

Scopul designului este de a oferi o interfață:
- modernă
- curată (dashboard-style)
- fresh & natural (temă inspirată din băuturi non-alcoolice)
- ușor de utilizat și consistentă pe toate paginile

---

# 🎨 1. Paleta de culori (Design System)

## 🧭 Culoare principală (brand)
- **Fresh Green:** `#3FBF7F`
  - utilizată pentru butoane principale, elemente active, highlight-uri

## 🧊 Culori secundare
- **Light Blue:** `#4DA3FF` (linkuri, info, grafice)
- **Mint Accent:** `#A8E6CF` (fundaluri soft, carduri accent)

## ⚪ Fundaluri
- **Background principal:** `#F6F9FC`
- **Carduri:** `#FFFFFF`
- **Hover card:** `#F0FBF6`

## 🧱 Text
- **Text principal:** `#1F2937`
- **Text secundar:** `#6B7280`
- **Text dezactivat:** `#9CA3AF`

## ⚠️ Status colors
- Success: `#22C55E`
- Warning: `#F59E0B`
- Error: `#EF4444`
- Info: `#3B82F6`

---

# 🔤 2. Tipografie (Font System)

## 🧠 Font principal
- **Inter** (UI standard pentru toate componentele)

## 🧃 Font secundar (opțional)
- **Poppins** (pentru titluri mari și elemente decorative)

---

## 📏 Reguli tipografice

| Element | Dimensiune | Stil |
|--------|------------|------|
| H1 | 32px | Bold |
| H2 | 24px | Semibold |
| H3 | 18px | Semibold |
| Body | 14–16px | Regular |
| Small text | 12px | Regular |

- Line height recomandat: **1.4 – 1.6**

---

# 🧱 3. Layout general

Toate paginile trebuie să respecte structura:


## 📌 Sidebar
- lățime: 240px
- fundal: alb
- icon + text pentru navigație
- element activ: verde (#3FBF7F)

## 🔝 Top bar
- search bar
- notificări
- user profile

## 📦 Content area
- padding: 24px
- layout tip grid
- max width: 1200–1400px

---

# 🃏 4. Card System

Toate produsele (băuturi) sunt afișate în carduri.

## 📦 Stil card:
- background: `#FFFFFF`
- border-radius: 12px
- shadow: 0 2px 10px rgba(0,0,0,0.05)
- hover: 0 2px 10px rgba(250, 246, 246, 0.98)
- translateY(-3px)
- border verde subtil

## 🧃 Conținut card:
- imagine produs
- nume
- categorie
- preț
- badge (popular / sezon / nou)

---

# 🔘 5. Butoane

## 🟢 Primary button
- background: `#3FBF7F`
- text: alb
- hover: variantă mai închisă

## ⚪ Secondary button
- border: `#3FBF7F`
- text verde
- background: transparent

## ❌ Danger button
- `#EF4444`

---

# 📊 6. Grafice și statistici

- stil flat (fără 3D)
- culori din paleta sistemului
- fundal alb
- utilizat pentru:
- top produse
- consum
- statistici utilizatori

---

# 🔍 7. Formulare

## Input fields:
- background: `#F9FAFB`
- border: `#E5E7EB`
- focus: border verde (#3FBF7F)
- border-radius: 8px

---

# 🧭 8. UX Rules (obligatorii)

- ✔ consistență între pagini
- ✔ spacing bazat pe grid de 8px (8, 16, 24, 32)
- ✔ maximum 2 culori dominante pe ecran
- ✔ feedback vizual la hover/click
- ✔ animații subtile (150–200ms)

---

# 🌙 9. Dark Mode (opțional)

## Culori:
- Background: `#111827`
- Cards: `#1F2937`
- Text: alb/gri deschis
- Accent verde păstrat