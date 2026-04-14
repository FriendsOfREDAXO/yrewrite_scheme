<?php
/**
 * General class for managing yrewrite schemes.
 */
class yrewrite_url_schemes extends rex_yrewrite_scheme
{
    /**
     * Append article name
     * @param string $path
     * @param rex_article $art
     * @param rex_yrewrite_domain $domain
     *
     * @return string
     */
    public function appendArticle($path, rex_article $art, rex_yrewrite_domain $domain)
    {
        static $scheme = null;
        static $excludedCategories = null;
        if ($scheme === null) {
            $scheme = rex_config::get('yrewrite_scheme', 'scheme', '');
            $excludedCategories = rex_config::get('yrewrite_scheme', 'excluded_categories', []);
        }

        foreach ($art->getParentTree() as $category) {
            if (in_array($category->getId(), $excludedCategories)) {
                $catname = '\/' . $this->normalize($category->getName(), $category->getClangId());
                if (!$art->isStartArticle() || strlen($path) > strlen($this->getClang($art->getClangId(), $domain) . $catname)) {
                    $path = preg_replace('/' . $catname . '/U', '', $path, 1);
                }
            }
        }

        if ($scheme === 'yrewrite_scheme_suffix') {
            // standard / suffix scheme
            if ($art->isStartArticle() && $domain->getMountId() !== $art->getId()) {
                return $path . $this->suffix;
            }
            return $path . '/' . $this->normalize($art->getName(), $art->getClang()) . $this->suffix;
        } elseif ($scheme === 'yrewrite_one_level') {
            // one level scheme
            return $path . '/' . $this->normalize($art->getName(), $art->getClang()) . $this->suffix;
        }

        // Default
        return parent::appendArticle($path, $art, $domain);
    }

    /**
     * @param rex_article $art
     * @param rex_yrewrite_domain $domain
     *
     * @return string|false
     */
    public function getCustomUrl(rex_article $art, rex_yrewrite_domain $domain)
    {
        $pathSuffix = $this->suffix;
        if ($pathSuffix === '.html') {
            $pathSuffix = '/';
        }

        if ($domain->getStartId() === $art->getId()) {
            if (!$domain->isStartClangAuto() && $domain->getStartClang() === $art->getClang()) {
                return '/';
            }
            return $this->getClang($art->getClang(), $domain) . $pathSuffix;
        }
        $url = $art->getValue('yrewrite_url');
        return $url ? $url : false;
    }

    /**
     * Append category name
     * @param string $path
     * @param rex_category $cat
     * @param rex_yrewrite_domain $domain
     *
     * @return string
     */
    public function appendCategory($path, rex_category $cat, rex_yrewrite_domain $domain)
    {
        $scheme = rex_config::get('yrewrite_scheme', 'scheme', '');

        if ($scheme === 'yrewrite_one_level') {
            // one level scheme
            return $path;
        }

        // Default
        return parent::appendCategory($path, $cat, $domain);
    }

    /**
     * @param rex_article $art
     * @param rex_yrewrite_domain $domain
     *
     * @return rex_structure_element|false
     */
    public function getRedirection(rex_article $art, rex_yrewrite_domain $domain)
    {
        static $urlReplacer = null;
        if ($urlReplacer === null) {
            $urlReplacer = rex_config::get('yrewrite_scheme', 'urlreplacer', '');
        }

        if ($urlReplacer === 'yrewrite_scheme_urlreplace') {
            // urlreplace scheme
            if ($art->isStartArticle() && $domain->getMountId() !== $art->getId() && ($cats = $art->getCategory()->getChildren(true)) && !rex_article_slice::getFirstSliceForCtype(1, $art->getId(), rex_clang::getCurrentId())) {
                return $cats[0];
            }
            return false;
        } elseif ($urlReplacer === 'yrewrite_scheme_nomatter') {
            // nomatter scheme
            if ($art->isStartArticle() && $domain->getMountId() !== $art->getId() && $domain->getStartId() !== $art->getId() && ($cats = $art->getCategory()->getChildren(true))) {
                return $cats[0];
            }
            return false;
        }

        // Default
        return parent::getRedirection($art, $domain);
    }

    /**
     * Rewrites String
     * @param string $string String to rewrite
     * @param int $clang Redaxo language ID
     * @return string Rewritten string
     */
    public function normalize($string, $clang = 0)
    {
        // Sprachspezifische Ersetzungen anwenden
        $language_replaces = rex_config::get('yrewrite_scheme', 'language_replaces_' . $clang, []);
        
        if (!empty($language_replaces) && is_array($language_replaces)) {
            $search = [];
            $replace = [];
            
            foreach ($language_replaces as $item) {
                if (isset($item['search']) && isset($item['replace'])) {
                    $search[] = $item['search'];
                    $replace[] = $item['replace'];
                }
            }
            
            if (!empty($search) && !empty($replace)) {
                $string = str_replace($search, $replace, $string);
            }
        }
        
        if (rex_config::get('yrewrite_scheme', 'urlencode-lang-' . $clang, 'original') === 'original') {
            return parent::normalize($string, $clang);
        }

        $replacedString = str_replace(
            ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', '/', '®', '©', '™', ':', '#', '$', '%', '&', '(', ')', '*', '+', ',', '.', '/', '!', ';', '<', '=', '>', '?', '@', '[', ']', '^', '_', '`', '{', '}', '~', '–', '\'', '¿', '"', '"', ' '],
            ['A', 'A', 'A', 'A', 'Ae', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'Oe', 'Oe', 'U', 'U', 'U', 'Ue', 'Y', 'ss', 'a', 'a', 'a', 'a', 'ae', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'oe', 'oe', 'u', 'u', 'u', 'ue', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'Oe', 'oe', '-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '-'],
            strip_tags(trim(mb_strtolower($string)))
        );
        $finalString = parent::normalize($replacedString);

        // In case settings require URL encode or normalizing the standard way failed
        if (($clang > 0 && rex_config::get('yrewrite_scheme', 'urlencode-lang-' . $clang, 'standard') === 'urlencode') || ($finalString === '' || $finalString === '-')) {
            $string = str_replace(['й'], ['и'], mb_strtolower($replacedString));
            $finalString = preg_replace('/[+-]+/', '-', $string);
        }

        return $finalString;
    }
}
