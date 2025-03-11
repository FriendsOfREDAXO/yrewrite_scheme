# YRewrite Scheme

Provides a selection of URL schemes for YRewrite.

For each scheme, the suffix, the matching **URL normalization per language**, language-specific character replacements, and a URL replacement can be selected. Other AddOns that install their own schemes should be deactivated beforehand. The settings can be found in the additional tab **YRewrite Scheme** in YRewrite.

## Suffix

Here you can define the suffix of the URLs.
Available options:
- "none"
- ".html"
- "/"

## Schemes

### 1. Standard

Provides an optimized YRewrite scheme that conforms to this format:
`example.tld/language/category/category/…/article/`
It is optimized in that it removes HTML tags from URLs and uses an extended replacement table. The extended replacement table differs from the YRewrite scheme only in special server configurations. It is especially interesting for languages that use URL encoding.

### 2. One Level

Implements a short URL scheme for all subpages.

__Before:__

`example.tld/en/coffee/beans/india/malabar.html`  _(yrewrite 1)_
`example.tld/en/coffee/beans/india/malabar/`  _(yrewrite 2)_

__After:__

`example.tld/en/malabar`  _(without suffix)_

> ⚠️ Important: The scheme is only useful if pages within a language __do not occur multiple times__. For example, if Malabar coffee were available not only in 🇮🇳 India but also in 🇧🇷 Brazil, this URL scheme should preferably not be used!

---

## URLReplace

Replaces the URLs of the parent categories with the URLs of the nearest child category.
There are 2 options to choose from:

- Option 1: Only categories whose start articles have no content are replaced.
- Option 2: All categories are replaced, regardless of the content of the start articles.

> Ideal for websites that do not need introductory pages for the respective category (e.g. for a dropdown navigation)

## Languages

For each language, you can specify whether to use the optimized YRewrite scheme, or whether to encode the characters in the URL. The latter enables Russian, Chinese and other URLs - in short, URLs with characters that do not use the Latin alphabet.

мне-нравится-редакс.html

编辑系统.html

## Language-Specific Replacements

Individual character replacements can be defined for each language. These are applied before the standard replacements and allow for finer control over URL generation.

Examples of replacements:
- "&" → "und" (for German)
- "&" → "and" (for English)
- "+" → "plus" (for all languages)

Language-specific replacements can be easily added, edited, or removed via the backend.

## Modify Scheme

YRewrite Scheme, like the original YRewrite, can also be modified/extended.
To do this, you must ensure that your own AddOn or the project AddOn has `load: late` noted in the package.yml. This would ensure that it is loaded and registered after the yrewrite_scheme AddOn.

Note: If necessary, a reinstall of the Project AddOn is required after changing to `load: late`.

### Example: Changing the rewriting for the & character in a URL

In the boot.php of the project AddOn:

```php
$addon = rex_addon::get('project');
$scheme = new my_project_rewrite_scheme();
rex_yrewrite::setScheme($scheme);
```

In the Lib folder of the project AddOn (e.g. `my_project_rewrite_scheme.php)

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

## Using Your Own Scheme Without This AddOn?

Instructions and examples can be found in the documentation within the yrewrite AddOn or on [Github](https://github.com/yakamara/redaxo_yrewrite).

## License

see [LICENSE](https://github.com/FriendsOfREDAXO/schemes/blob/master/LICENSE)

## Credits
- [Thomas Skerbis](https://github.com/skerbis)
- [Joachim Dörr](https://github.com/joachimdoerr)
- [Christian Gehrke](https://github.com/chrison94)
- [Tobias Krais](https://github.com/tobiaskrais)
- [Dirk Schürjohann](https://github.com/schuer)
- [Norbert Micheel](https://github.com/tyrant88)
- [FriendsOfREDAXO](https://github.com/FriendsOfREDAXO)
