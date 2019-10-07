# YRewrite Scheme

stellt eine Auswahl von URL-Schemes für YRewrite zur Verfügung. 

Für jedes Schema kann der Suffix und die passende **URL-Normalisierung je Sprache** gewählt werden. Andere AddOns, die eigene Schemes installieren, sollten vorab deaktiviert werden. Die Einstellungen findet man im zusätzlichen Reiter **YRewrite Scheme** in YRewrite. 

## Schemen

### 1. Standard

Stellt ein optimiertes YRewrite-Schema zur Verfügung, das dieser Form entspricht:  
`example.tld/sprache/kategorie/kategorie/…/artikel/`
Es ist insofern optimiert, dass es html Tags aus URLs entfernt und eine erweiterte Ersetzungstabelle verwendet. Die erweiterte Ersetzungstabelle weiß nur bei speziellen Serverkonfigurationen unterschiede zum YRewrite Schema auf. Sie ist vor allem für Sprachen, die die URL kodierung Nutzen interessant.

### 2. URLReplace

Ersetzt die URLs der Elternkategorien mit den URLs der nächst zugehörigen Kindkategorie.  
Hier stehen 2 Varianten zur Auswahl:

- Variante 1: Es werden nur die Kategorien ersetzt, deren Startartikel keinen Inhalt haben.
- Variante 2: Es werden alle Kategorien ersetzt, unabhängig vom Inhalt der Startartikel. 

> Ideal für Webpräsenzen, die keine Vorschaltseiten für die jeweilige Kategorie benötigen (z.B. bei einer Dropdown-Navigation)

### 3. One Level

Implementiert ein kurzes URL-Schema für alle Unterseiten.

__Vorher:__

`example.tld/en/coffee/beans/india/malabar.html`  _(yrewrite 1)_  
`example.tld/en/coffee/beans/india/malabar/`  _(yrewrite 2)_  

__Nachher:__

`example.tld/en/malabar`  _(ohne suffix)_  

> ⚠️ Wichtig: Das Schema ist nur dann sinnvoll, wenn Seiten innerhalb einer Sprache __nicht mehrfach vorkommen__. Gäbe es etwa den Malabar-Kaffee nicht nur in 🇮🇳 Indien, sondern auch in 🇧🇷 Brasilien, sollte dieses URL-Schema besser nicht verwendet werden!

---

## Sprachen

Für jede Sprache kann eingestellt werden, ob das optimierte YRewrite Schema verwendet werden soll, oder die Zeichen URL kodiert werden sollen. Letzteres ermöglicht russische, chinesische und andere URLs - kurz gesagt URLs mit Zeichen die nicht das lateinische Alphabet verwenden.

мне-нравится-редакс.html

编辑系统.html

---

## Eigenes Schema verwenden ohne dieses AddOn?

Anleitung und Beispiele findet Ihr in der Dokumentation innerhalb des yrewrite-Addons oder auf [Github](https://github.com/yakamara/redaxo_yrewrite/blob/2bfc3c5e5b5776676241c300f65900fcc2914622/pages/docs.php#L239).

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
