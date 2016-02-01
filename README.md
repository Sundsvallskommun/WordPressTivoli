# Beroenden

WordPress-plugins:

* [ACF (Advanced Custom Fields) Pro](http://www.advancedcustomfields.com/pro/)

# Installation

1. Installera WP
2. Installera DB
3. Lägg in eller symlinka temat i `wp-content/themes`
4. Installera och aktivera WordPress-plugins (se beroenden ovan).

# Utveckling

## JavaScript

All JS i temat körs via Babel (med ES2015), läggs samman och minifieras. De
filer som ska ingå anges i `gulpfile.js` i `themeScripts`-variableln.

Det är angivet att alla filer som ligger direkt under `assets/js/source/`
följer med.  Om filer i undermappar ska användas läggs dessa till i arrayen.

Alla bootstrap-komponenters script finns angivna, men de som inte används är
utkommenterade.
