<?php


class yrewrite_scheme_urlreplace extends rex_yrewrite_scheme {
    public function getRedirection(rex_article $art, rex_yrewrite_domain $domain) {
        if ($art->isStartArticle() && ($cats = $art->getCategory()->getChildren(true)) && !rex_article_slice::getFirstSliceForCtype(1, $art->getId(), rex_clang::getCurrentId())) {
            return $cats[0];
        }
 	return false;
 	
    }  

    public function appendArticle($path, rex_article $art, rex_yrewrite_domain $domain) {
    	 $path_suffix = rex_config::get('schemes', 'suffix');
        return $path .$path_suffix ;
    }
}


class yrewrite_scheme_nomatter extends rex_yrewrite_scheme
{
    public function getRedirection(rex_article $art, rex_yrewrite_domain $domain)
    {
        if ($art->isStartArticle() && ($cats = $art->getCategory()->getChildren(true))) {
            return $cats[0];
        }
        return false;
    }
        public function appendArticle($path, rex_article $art, rex_yrewrite_domain $domain) {
    	 $path_suffix = rex_config::get('schemes', 'suffix');
        return $path .$path_suffix ;
    }
}

class yrewrite_scheme_suffix extends rex_yrewrite_scheme {
	public function appendArticle($path, rex_article $art, rex_yrewrite_domain $domain) {
    	$path_suffix = rex_config::get('schemes', 'suffix');
		if ($art->isStartArticle() && $domain->getMountId() != $art->getId()) {
			return $path . $path_suffix;
		}
		return $path . '/' . $this->normalize($art->getName()) . $path_suffix;
	}
}

class yrewrite_one_level extends rex_yrewrite_scheme
{ 
    public function appendCategory($path, rex_category $cat, rex_yrewrite_domain $domain)
    {
        return $path ;
    }
    public function appendArticle($path, rex_article $art, rex_yrewrite_domain $domain)
    {
    	$suffix_content = rex_config::get('schemes', 'suffix');
        return $path . '/' . $this->normalize($art->getName(), $art->getClang()) . $path_suffix;
    }
}