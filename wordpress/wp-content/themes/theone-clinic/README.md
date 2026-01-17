# TheOne Clinic – motyw WordPress

To jest motyw WordPress przeniesiony z wersji statycznej (HTML/CSS/JS), z importem treści stron.

## Instalacja

1. Skopiuj folder motywu do WordPress:
   - `wordpress/wp-content/themes/theone-clinic/` → `wp-content/themes/theone-clinic/`
2. W panelu WordPress: **Wygląd → Motywy** → aktywuj **TheOne Clinic**.
3. Zaimportuj treści stron:
   - **Narzędzia → Import** → **WordPress (Importer)**
   - importuj plik: `wordpress/import/theone-clinic-pages.xml`
4. Ustaw stronę główną:
   - **Ustawienia → Czytanie** → „Strona główna wyświetla” → **Strona statyczna**
   - wybierz stronę **Strona główna** (zaimportowaną)

5. (Zalecane) Włącz przyjazne linki:
   - **Ustawienia → Bezpośrednie odnośniki** → wybierz „Nazwa wpisu”

## Edycja treści

Treści są w normalnych **Stronach** WordPress. Większość treści to HTML wstawiony jako blok „Własny HTML” – możesz go edytować w edytorze blokowym.

## Edycja globalnych danych (telefon/adres/Booksy/social)

W motywie są ustawienia w **Wygląd → Dostosuj → TheOne Clinic: Kontakt i linki**.
Import treści używa shortcode’ów, więc po zmianie wartości w Customizerze zaktualizują się automatycznie w treści:

- `[theone_phone_display]`, `[theone_phone_href]`
- `[theone_address_text]`, `[theone_maps_url]`
- `[theone_booksy_url]`

## Promocje (modal „Aktualne promocje”)

Promocje są zarządzane w panelu WordPress:

- **Promocje → Dodaj nową**

W promocji możesz ustawić:

- **Tytuł** (nagłówek w modalu)
- **Treść/opis** (opis w modalu)
- **Obrazek wyróżniający** (grafika w modalu)
- **Czas trwania** w bocznym boxie: „Od” i „Do” (opcjonalne)

Modal na stronie jest generowany automatycznie w stopce (nie jest już wklejony na stałe w treść strony).
Gdy nie ma aktywnych promocji, w modalu pojawi się komunikat: **Brak promocji**.
Na stronie głównej przycisk jest wstawiony jako shortcode:

- `[theone_promotions_button]`

## Uwaga o assetach

Treści importowane odwołują się do assetów motywu pod ścieżką:

- `/wp-content/themes/theone-clinic/media/...`

Dlatego folder motywu powinien nazywać się dokładnie `theone-clinic`.

## Brakujące pliki (jeśli jakieś obrazki się nie wyświetlają)

W [pages/efekty.html](pages/efekty.html) są odwołania do `media/gallery/*.jpg`. W tym repo nie ma tych plików, więc w motywie utworzyłem pusty folder `media/gallery/`.
Jeśli masz zdjęcia „przed i po”, wrzuć je do:

- `wp-content/themes/theone-clinic/media/gallery/`
