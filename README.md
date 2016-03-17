# Beroenden

WordPress-plugins:

* [ACF (Advanced Custom Fields) Pro](http://www.advancedcustomfields.com/pro/)
* [TinyMCE Advanced](https://sv.wordpress.org/plugins/tinymce-advanced/)
	(Används just nu endast för tabeller, som läggs till programmatiskt i
	verktygsfältet. Aktivera pluginet och avaktivera alla inställningar.)

# Installation

1. Installera WP
2. Installera DB
3. Lägg in eller symlinka temat i `wp-content/themes`
4. Installera och aktivera WordPress-plugins (se beroenden ovan).

# Utveckling

## JavaScript

## Ikoner

svg-ikoner placeras under `./assets/images/icons/`. Dessa blir sedan kombinerad
till en fil med <symbol>-element med hjälp av
[svgstore](https://github.com/w0rm/gulp-svgstore). Denna fil laddas in av ett
script efter sidladdning.

Två hjälpfunktioner finns för att hämkta ut ikonerna: `the_icon()` och
`get_icon()`. Dessa skapar ett svg-element som länkar in ikonen medd ett
`<use>`-element.
