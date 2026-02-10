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
        $scheme = rex_config::get('yrewrite_scheme', 'scheme', '');
        $excludedCategories = rex_config::get('yrewrite_scheme', 'excluded_categories', []);

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
        $urlReplacer = rex_config::get('yrewrite_scheme', 'urlreplacer', '');

        if ($urlReplacer === 'yrewrite_scheme_urlreplace') {
            // urlreplace scheme
            if ($art->isStartArticle() && ($cats = $art->getCategory()->getChildren(true)) && !rex_article_slice::getFirstSliceForCtype(1, $art->getId(), rex_clang::getCurrentId())) {
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
        // 1. First cleanup: lowercasing and trimming
        $string = strip_tags(trim(mb_strtolower($string)));

        // 2. Remove Emojis and Symbols
        // Match Emoticons, Misc Symbols and Pictographs, Transport and Map, Flags, etc.
        $string = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E0}-\x{1F1FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{FE00}-\x{FE0F}\x{1F900}-\x{1F9FF}]/u', '', $string);

        // 3. Sprachspezifische Ersetzungen anwenden
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

        // 4. Global replacements (Refactored: Cleaned up array, only lowercase)
        $replacements = $this->getReplacements();
        $replacedString = str_replace(
            $replacements['search'],
            $replacements['replace'],
            $string
        );
        $finalString = parent::normalize($replacedString);

        // In case settings require URL encode or normalizing the standard way failed
        if (($clang > 0 && rex_config::get('yrewrite_scheme', 'urlencode-lang-' . $clang, 'standard') === 'urlencode') || ($finalString === '' || $finalString === '-')) {
            $string = str_replace(['й'], ['и'], mb_strtolower($replacedString));
            $finalString = preg_replace('/[+-]+/', '-', $string);
        }

        return $finalString;
    }

    /**
     * Returns the replacement table for URL normalization.
     * Contains only lowercase keys as the input string is already lowercased.
     * 
     * @return array{search: array<string>, replace: array<string>}
     */
    protected function getReplacements()
    {
        return [
            'search' => [
                'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 
                'ā', 'ă', 'ą', 'ć', 'ĉ', 'ċ', 'č', 'ď', 'đ', 'ē', 'ĕ', 'ė', 'ę', 'ě', 'ĝ', 'ğ', 'ġ', 'ģ', 'ĥ', 'ħ', 'ĩ', 'ī', 'ĭ', 'į', 'ı', 'ĳ', 'ĵ', 'ķ', 'ĺ', 'ļ', 'ľ', 'ŀ', 'ł', 'ń', 'ņ', 'ň', 'ŉ', 'ō', 'ŏ', 'ő', 'œ', 'ŕ', 'ŗ', 'ř', 'ś', 'ŝ', 'ş', 'š', 'ţ', 'ť', 'ŧ', 'ũ', 'ū', 'ŭ', 'ů', 'ű', 'ų', 'ŵ', 'ŷ', 'ź', 'ż', 'ž', 'ſ', 'ƒ', 'ơ', 'ư', 'ǎ', 'ǐ', 'ǒ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'ǻ', 'ǽ', 'ǿ',
                '/', '®', '©', '™', ':', '#', '$', '%', '&', '(', ')', '*', '+', ',', '.', '/', '!', ';', '<', '=', '>', '?', '@', '[', ']', '^', '_', '`', '{', '}', '~', '–', '\'', '¿', '"', '"', ' '
            ],
            'replace' => [
                'ss', 'a', 'a', 'a', 'a', 'ae', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'oe', 'oe', 'u', 'u', 'u', 'ue', 'y', 'y', 
                'a', 'a', 'a', 'c', 'c', 'c', 'c', 'd', 'd', 'e', 'e', 'e', 'e', 'e', 'g', 'g', 'g', 'g', 'h', 'h', 'i', 'i', 'i', 'i', 'i', 'ij', 'j', 'k', 'l', 'l', 'l', 'l', 'l', 'n', 'n', 'n', 'n', 'o', 'o', 'o', 'oe', 'r', 'r', 'r', 's', 's', 's', 's', 't', 't', 't', 'u', 'u', 'u', 'u', 'u', 'u', 'w', 'y', 'z', 'z', 'z', 's', 'f', 'o', 'u', 'a', 'i', 'o', 'u', 'u', 'u', 'u', 'u', 'a', 'ae', 'oe',
                '-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '-'
            ]
        ];
    }
}
