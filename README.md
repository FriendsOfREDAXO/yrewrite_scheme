#Schemes

##Standard
Ersetzt die URLs nach dem normalen YRewrite-Schema + die Auswahl des gesetzten Suffix
##URL_REPLACER

Ersetzt die URLs leerer Elternkategorien mit den URLs der nächsten inhaltversehenen Kindkategorien.
Ideal für Webpräsenzen, die keine Vorschaltseiten für die jeweilige Kategorie benötigen (z.B. bei einer Dropdown-Navi)

##One-Level

Implementiert ein kurzes URL-Schema für alle Unterseiten auf Basis von [yrewrite](https://github.com/yakamara/redaxo_yrewrite).

### Schema

__Vorher:__

`example.tld/en/coffee/beans/india/malabar.html`  _(yrewrite 1)_  
`example.tld/en/coffee/beans/india/malabar/`  _(yrewrite 2)_  

__Nachher:__

`example.tld/en/malabar`  _(ohne trailing slash)_  

## Eigenes Schema verwenden ohne dieses AddOn?

Anleitung und Beispiele: https://github.com/FriendsOfREDAXO/tricks/blob/master/addons_yrewrite_url_schemes.md

### Lizenz

siehe [LICENCE](https://github.com/FriendsOfREDAXO/urlreplacer/blob/master/LICENCE)

**Projekt-Lead**

[KLXM Crossmedia / Thomas Skerbis](https://klxm.de)

## Credits

- [Christian Gehrke](https://github.com/chrison94) 
- [Joachim Dörr](https://github.com/joachimdoerr)
- [Dirk Schürjohann](https://github.com/schuer)
- [FriendsOfREDAXO](https://github.com/FriendsOfREDAXO)
