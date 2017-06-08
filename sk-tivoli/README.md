# Beroenden

WordPress-plugins:

* [ACF (Advanced Custom Fields) Pro](http://www.advancedcustomfields.com/pro/)

* [Location "Nav Menu" for ACF](https://wordpress.org/plugins/location-nav-menu-for-acf/)

* [TinyMCE Advanced](https://sv.wordpress.org/plugins/tinymce-advanced/)

	(Används just nu endast för tabeller, som läggs till programmatiskt i
	verktygsfältet. Aktivera pluginet och avaktivera alla inställningar.)
* Gravity Forms

# Installation

1. Installera WP
2. Installera DB
3. Lägg in eller symlinka temat i `wp-content/themes`
4. Installera och aktivera WordPress-plugins (se beroenden ovan).

En startsida och nyhetsarkiv behöver skapas och sedan kopplas på under "Inställningar > Läsa". 
Välj en statisk sida för Startsida och Inläggsida.

Permalänkar ska vara satt till "Inläggsnamn" under "Inställningar > Permalänkar".

En navigationsmeny behöver skapas och kopplas som Huvudmeny, se "Utseende > Menyer".

Temat har en del övriga inställningar för att eventuellt anpassa webbplatsen. Dessa inställningar finns under menyvalet "Webbplatsen".


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

## Filters
#### Alter search query
Parameters: array $args

`add_filter( 'search_attachments_args', 'my_search_attachments_args', 10, 1 );`

`add_filter( 'search_contacts_args', 'my_search_contacts_args', 10, 1 );`

`add_filter( 'search_posts_args', 'my_search_posts_args', 10, 1 );`

`add_filter( 'search_pages_args', 'my_search_pages_args', 10, 1 );`

## Shortcodes

### parkeringsplatser / sk-parking
Används på sidor eller inlägg för att visa en tabell med lediga parkeringsplatser i Sundsvall med data hämtat från Infracontrol.

### tivoli-nyheter
Kan användas på sidor/inlägg och i mallen Startsida - avancerad. Listar ut de tre senaste inläggen med bild.

# Övrigt

## "Startsida avancerad"
Sidmallen "Startsida - avancerad" används då man vill skapa en dynamisk anpassningsbar startsida tillsammans med Blocks eller Short Code.

## Blocks
Blocks är en egen posttyp som just nu används för att skapa innehåll på startsidan. Blocks går och bör byggas ut med fler anpassade block. Det finns olika typer av block och utifrån vald blocktyp genereras associerade fält.

För att lägga till en egen blocktyp i ett barntema så kan följande filter användas: sk_default_block_types.


## "Blev du hjälp av sidan?"

I admin under Webbplatsen->allmänt->Sidspecifikt formulär anges id-nummer till
det Gravity Forms-formulär som ska visas när besökaren röstat på en sida.

För att ett epostmeddelande ska skickas ut till författaren till sidan måste
det finnas en notis för formuläret i Gravity Forms som heter "Författarnotis".

## "Webb i webb"

Kallas advanced template i koden.

### Nyheter

Nyheter som ska visas anges via ett ACF-fält på webb-i-webb-mallen. Där väljer
man en eller flera kategorier som ska visas på startsidan.

För att ha ett nyhetsarkiv som ligger i webb-i-webbens struktur så skapas en
sida under webb-i-webbens startsida med mallen "Nyhetsarkiv: Webb-i-webb".

### Bildpuffar

Fungerar som på webbplatsens startsida, ACF-fält där man kan lägga till upp
till 3 bildpuffar (med titel bild och länk).

### Driftmeddelanden

Visas i en lika list som på startsidan, men visas bara om det finns
driftmeddelanden knutna till webb-i-webben. Man knyter de på samma sätt som man
knyter driftmeddelanden till andra sidor på webbplatsen (inne på
driftmeddelandet).

