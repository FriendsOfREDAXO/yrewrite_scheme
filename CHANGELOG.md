# YRewrite Scheme – Changelog

## Version 3.9.0 – 14.04.2026

### 🐛 Bugfixes

* **URLReplace Scheme**: Mount-Point-Artikel werden nicht mehr auf die erste Kind-Kategorie weitergeleitet, was zu Endlos-Redirects führen konnte (#54) – thx @tyrant88

### 🛡️ Security

* **Input-Verarbeitung**: `$_POST` in der Settings-Seite durch `rex_post()` ersetzt – keine direkten Super-Global-Zugriffe mehr
* **Output-Escaping**: `htmlspecialchars()` in den Ersetzungs-Formularen mit `ENT_QUOTES | ENT_HTML5` und `UTF-8` abgesichert

### 🚀 Code-Qualität

* **JavaScript**: Inline-`<script>`-Block aus PHP entfernt und in separate Datei `assets/yrewrite_scheme.js` ausgelagert
* **JavaScript**: Einbindung über `boot.php` mit Page-Check (`yrewrite/yrewrite_scheme`), statt auf jeder Backend-Seite
* **JavaScript**: Verwendet nun `rex:ready` statt `$(document).ready()` für korrekte PJAX-Kompatibilität
* **PHP**: `Null` auf korrekte Kleinschreibung `null` korrigiert
* **PHP**: Veralteten REDAXO-5.6.0-Kompatibilitäts-Hack entfernt
* **PHP**: Hardcodierten Sprachname-Vergleich (`'Deutsch'`) durch sprachneutrale Lösung ersetzt

### ⚡ Performance

* **Config-Caching**: `rex_config::get()`-Aufrufe in `appendArticle()` und `getRedirection()` werden jetzt über statische Variablen gecacht – die Konfiguration wird damit pro Request nur einmal gelesen statt bei jedem der n Artikel- und Kategorie-Aufrufe im URL-Generierungs-Durchlauf

---

## Version 3.8.0

* Sprachspezifische URL-Ersetzungen für YRewrite Scheme (#49)
* Fix url Ersetzung bei eigenem Scheme

## Version 3.7.0

* Fix excluded categories path
