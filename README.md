# YRewrite Scheme

stellt eine Auswahl von URL-Schemes für YRewrite zur Verfügung. 

Für jedes Schema können der Suffix, die passende **URL-Normalisierung je Sprache** und eine URL-Ersetzung gewählt werden. Andere AddOns, die eigene Schemes installieren, sollten vorab deaktiviert werden. Die Einstellungen findet man im zusätzlichen Reiter **YRewrite Scheme** in YRewrite. 

## Suffix

Hier kann der Suffix der URLs festgelegt werden.
Zur Auswahl stehen:
- "ohne"
- ".html"
- "/"

## Schemen

### 1. Standard

Stellt ein optimiertes YRewrite-Schema zur Verfügung, das dieser Form entspricht:  
`example.tld/sprache/kategorie/kategorie/…/artikel/`
Es ist insofern optimiert, dass es html Tags aus URLs entfernt und eine erweiterte Ersetzungstabelle verwendet. Die erweiterte Ersetzungstabelle weiß nur bei speziellen Serverkonfigurationen unterschiede zum YRewrite Schema auf. Sie ist vor allem für Sprachen, die die URL kodierung Nutzen interessant.

### 2. One Level

Implementiert ein kurzes URL-Schema für alle Unterseiten.

__Vorher:__

`example.tld/en/coffee/beans/india/malabar.html`  _(yrewrite 1)_  
`example.tld/en/coffee/beans/india/malabar/`  _(yrewrite 2)_  

__Nachher:__

`example.tld/en/malabar`  _(ohne suffix)_  

> ⚠️ Wichtig: Das Schema ist nur dann sinnvoll, wenn Seiten innerhalb einer Sprache __nicht mehrfach vorkommen__. Gäbe es etwa den Malabar-Kaffee nicht nur in 🇮🇳 Indien, sondern auch in 🇧🇷 Brasilien, sollte dieses URL-Schema besser nicht verwendet werden!

---

## URLReplace

Ersetzt die URLs der Elternkategorien mit den URLs der nächst zugehörigen Kindkategorie.  
Hier stehen 2 Varianten zur Auswahl:

- Variante 1: Es werden nur die Kategorien ersetzt, deren Startartikel keinen Inhalt haben.
- Variante 2: Es werden alle Kategorien ersetzt, unabhängig vom Inhalt der Startartikel. 

> Ideal für Webpräsenzen, die keine Vorschaltseiten für die jeweilige Kategorie benötigen (z.B. bei einer Dropdown-Navigation)



## Sprachen

Für jede Sprache kann eingestellt werden, ob das optimierte YRewrite Schema verwendet werden soll, oder die Zeichen URL kodiert werden sollen. Letzteres ermöglicht russische, chinesische und andere URLs - kurz gesagt URLs mit Zeichen die nicht das lateinische Alphabet verwenden.

мне-нравится-редакс.html

编辑系统.html


## Schema modifizieren

yrewrite_scheme lässt sich wie das Original von YRewrite auch modifizieren / erweitern. 
Hierzu muss sichergestellt werden, dass das AddOn oder das Projekt-AddOn das die Erweiterung enthält in der Package `load: late` notiert hat. Gegebenenfalls muss das AddOn oder das Projekt-AddOn reinstalliert werden.
 Eine weitere Registrierung in der boot.php ist nicht nötig, da das bereits vorhandene Schema von YRewrite Scheme ergänzt wird. 

### Beispiel boot.php wenn man die Einstellungen von yrewrite_scheme um ein eigenes Scheme erweitern will

```php
$addon = rex_addon::get('project');

$scheme = new my_project_rewrite_scheme();
$scheme->setSuffix(rex_config::get('yrewrite_scheme', 'suffix'));
rex_yrewrite::setScheme($scheme);
```

### Beispiel Änderung der Umschreibung für das & Zeichen in einer Url 

```php
class my_project_rewrite_scheme extends yrewrite_url_schemes
{
    /**
     * @param string $string
     * @param int    $clang
     *
     * @return string
     */
    public function normalize($string, $clang = 1)
    {
        $string = str_replace(
      ['&'],
      ['und'],
      $string
    );
        return parent::normalize($string, $clang);
    }
}
```


---

## Eigenes Schema verwenden ohne dieses AddOn?

Anleitung und Beispiele findet Ihr in der Dokumentation innerhalb des yrewrite-Addons oder auf [Github](https://github.com/yakamara/redaxo_yrewrite).

## Lizenz

siehe [LICENSE](https://github.com/FriendsOfREDAXO/schemes/blob/master/LICENSE)

## Projekt-Lead

[KLXM Crossmedia / Thomas Skerbis](https://klxm.de)

## Credits

- [Joachim Dörr](https://github.com/joachimdoerr)
- [Christian Gehrke](https://github.com/chrison94)
- [Tobias Krais](https://github.com/tobiaskrais)
- [Dirk Schürjohann](https://github.com/schuer)
- [FriendsOfREDAXO](https://github.com/FriendsOfREDAXO)
