# YRewrite Scheme

provides a choice of 2 URL schemes for YRewrite.

For each scheme, the suffix, the appropriate **URL normalisation per language** and a URL replacement can be selected. Other AddOns that install their own schemes should be deactivated in advance. The settings can be found in the additional tab **YRewrite Scheme** in YRewrite. 

## Suffix

The suffix of the URLs can be set here.
The following are available for selection:
- "without"
- ".html"
- "/"

## Schemes

### 1. Standard

Provides an optimised YRewrite scheme corresponding to this form:  
`example.tld/language/category/category/.../article/`  
It is optimised in that it removes html tags from URLs and uses an extended replacement table. The extended replacement table only differs from the YRewrite scheme in some special server configurations. It is especially interesting for languages that use URL coding.

### 2. One Level

Implements a short URL scheme for all subpages.

__before:__

`example.tld/en/coffee/beans/india/malabar.html`  _(yrewrite 1)_  
`example.tld/en/coffee/beans/india/malabar/`  _(yrewrite 2)_  

__after:__

`example.tld/en/malabar`  _(ohne suffix)_  

> ⚠️ Important: The scheme only makes sense if pages __do not occur more than once__ within a language. For example, if Malabar coffee existed not only in 🇮🇳 India but also in 🇧🇷 Brazil, it would be better not to use this URL scheme!

---

## URLReplace

Replaces the URLs of the parent categories with the URLs of the next corresponding child category.  
There are 2 variants to choose from here:

- Variant 1: Only the categories whose start articles have no content are replaced.
- Variant 2: All categories are replaced, regardless of the content of the start articles.

> Ideal for websites that do not require preliminary pages for the respective category (e.g. for a dropdown navigation).




## Languages

For each language, you can choose whether to use the YRewrite standard scheme, the optimised YRewrite scheme or to URL-encode the characters.  
The latter allows Russian, Chinese and other URLs - in short, URLs with characters that do not use the Latin alphabet.

мне-нравится-редакс.html

编辑系统.html


## Modify scheme

YRewrite Scheme can also be modified / extended like the original YRewrite.
For this, it must be ensured that your own AddOn or the project AddOn has `load: late` noted in the package.yml. This would ensure that it is loaded and registered after the yRewrite_scheme AddOn.


### Example for changing the paraphrase for the & character in an url 

In the file `boot.php` of project-AddOn: 

```php
$addon = rex_addon::get('project');
$scheme = new my_project_rewrite_scheme();
rex_yrewrite::setScheme($scheme);
```

In the lib folder of the project AddOn (e.g. `my_project_rewrite_scheme.php`)

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
      ['and'],
      $string
    );
        return parent::normalize($string, $clang);
    }
}
```


---

## Use your own schema without this add-on?

Instructions and examples can be found in the documentation within the yrewrite addon or on [Github](https://github.com/yakamara/redaxo_yrewrite).

## License

see [LICENSE](https://github.com/FriendsOfREDAXO/schemes/blob/master/LICENSE)

## Project lead

[KLXM Crossmedia / Thomas Skerbis](https://klxm.de)

## Credits

- [Thomas Skerbis](https://github.com/skerbis)
- [Joachim Dörr](https://github.com/joachimdoerr)
- [Christian Gehrke](https://github.com/chrison94)
- [Tobias Krais](https://github.com/tobiaskrais)
- [Dirk Schürjohann](https://github.com/schuer)
- [Norbert Micheel](https://github.com/tyrant88)
- [FriendsOfREDAXO](https://github.com/FriendsOfREDAXO)
