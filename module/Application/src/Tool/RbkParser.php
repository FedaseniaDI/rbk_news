<?php
namespace Application\Tool;

use Laminas\Dom\Query;

class RbkParser {
    public static function parseNewsFeeds(string $content)
    {
        $newsFeedsData = [];
        $dom = (new Query)->setDocumentHtml($content);
        $feeds = $dom->execute('.js-news-feed-list .news-feed__item');
        foreach ($feeds as $feed) {
            $feedData = [];
            $linkAttrs = $feed->attributes;
            $feedData['s_id'] = str_replace('id_newsfeed_', '',$linkAttrs->getNamedItem('id')->nodeValue);
            $feedData['s_url'] = str_replace('https://', 'http://', $linkAttrs->getNamedItem('href')->nodeValue);
            $feedData['datetime'] = date("Y-m-d H:i:s", $linkAttrs->getNamedItem('data-modif')->nodeValue);
            $feedData['title'] = trim($feed->childNodes->item(1)->textContent);
            $newsFeedsData[$feedData['s_url']] = $feedData;
        }
        return $newsFeedsData;
    }

    public static function parseOneNews($content)
    {
        $dom = (new Query)->setDocumentHtml($content);
        $title = $dom->execute('*[itemprop="headline"]')->current()->textContent;
        $textOverviewNList = $dom->execute('*[itemprop="articleBody"] .article__text__overview');
        $imageNList = $dom->execute('img.article__main-image__image');
        $imageDescriptionNList = $dom->execute('.article__main-image__title *[itemprop="description"]');
        $imageAuthorNList = $dom->execute('.article__main-image__title .article__main-image__author');
        $contentsNList = $dom->execute('.article__text p');
        $authorNList = $dom->execute('.article *[itemprop="author"]');
        $newsData['title'] = $title;
        $newsData['text_overview'] = $textOverviewNList->count() ?
            trim($textOverviewNList->current()->textContent) :
            null;
        $newsData['img_src'] = $imageNList->count() ?
            $imageNList->current()->attributes->getNamedItem('src')->textContent :
            null;
        $newsData['img_description'] = $imageDescriptionNList->count() ?
            trim($imageDescriptionNList->current()->textContent) :
            null;
        $newsData['img_author'] = $imageAuthorNList->count() ?
            trim($imageAuthorNList->current()->textContent) :
            null;

        $content = '';
        foreach($contentsNList as $contentNode) {
            $content .= $contentsNList->getDocument()->saveHTML($contentNode) . "\n";
        }
        $newsData['content'] = $content;

        $newsData['author'] = $authorNList->count() ?
            $authorNList->current()->attributes->getNamedItem('content')->textContent :
            null;

        return $newsData;
    }
}