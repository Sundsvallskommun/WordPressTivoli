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

## Ikoner

svg-ikoner placeras under `./assets/images/icons/`. Dessa blir sedan kombinerad
till en fil med <symbol>-element med hjälp av
[svgstore](https://github.com/w0rm/gulp-svgstore). Denna fil laddas in av ett
script efter sidladdning.

Två hjälpfunktioner finns för att hämkta ut ikonerna: `the_icon()` och
`get_icon()`. Dessa skapar ett svg-element som länkar in ikonen medd ett
`<use>`-element.
