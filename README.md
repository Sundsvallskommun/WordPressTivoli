# Beroenden

WordPress-plugins:

* [ACF (Advanced Custom Fields) Pro](http://www.advancedcustomfields.com/pro/)
* [TinyMCE Advanced](https://sv.wordpress.org/plugins/tinymce-advanced/)
	(Används just nu endast för tabeller, som läggs till programmatiskt i
	verktygsfältet. Aktivera pluginet och avaktivera alla inställningar.)
* Gravity Forms

# Installation

1. Installera WP
2. Installera DB
3. Lägg in eller symlinka temat i `wp-content/themes`
4. Installera och aktivera WordPress-plugins (se beroenden ovan).

# Utveckling

Gulp används som byggprocess. Använd flaggan --production för att bygga för
produktion, som att t.ex. minifiera css.

## JavaScript

## Ikoner

svg-ikoner placeras under `./assets/images/icons/`. Dessa blir sedan kombinerad
till en fil med <symbol>-element med hjälp av
[svgstore](https://github.com/w0rm/gulp-svgstore). Denna fil laddas in av ett
script efter sidladdning.

Två hjälpfunktioner finns för att hämkta ut ikonerna: `the_icon()` och
`get_icon()`. Dessa skapar ett svg-element som länkar in ikonen medd ett
`<use>`-element.

## Hjälpfunktioner

Funktioner från `lib/helpers/`

### the_icon och get_icon

Skriv ut eller returnera en svg-ikon.

### format_phone

Formaterar telefonnummer med bindestreck och blanksteg. Flera telefonnummer
separeras med kommatecken.

### get_phone_links

Formaterar telefonnummer och returnerar telefonlänkar för alla nummer.

### get_email_links

Formaterar telefonnummer och returnerar epostlänkar för alla adresser.

### get_section_class_name

Returnerar css-klassnamn baserat på den sektion av webbplatsen som sidan
tillhör.

### ancestor_field

Returnerar den närmsta sidan uppåt i strukturen som har rätt värden i ACF-fält.
Används för att ärva inställningar.

### sk_get_excerpt

Returnerar sidutdrag från post-id.

### format_file_size

Formaterar filstorlek i B, KB, MB eller GB.

### sk_get_json

Anropar url och returnerar json.

### is_navigation

Kollar om en sida är en navigationssida.

## Actions

### sk_header_end

### sk_before_main_content

### sk_before_page_title

### sk_after_page_title

### sk_before_page_content

### sk_after_page_content

### sk_page_helpmenu

### sk_page_widgets

### sk_popular_eservices

# Övrigt

## "Blev du hjälp av sidan?"

I admin under Webbplatsen->allmänt->Sidspecifikt formulär anges id-nummer till
det Gravity Forms-formulär som ska visas när besökaren röstat på en sida.

För att ett epostmeddelande ska skickas ut till författaren till sidan måste
det finnas en notis för formuläret i Gravity Forms som heter "Författarnotis".

