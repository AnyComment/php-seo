<?php


namespace AnyComment\Seo\Generator;

use AnyComment\Dto\Comment\Index\Page;

/**
 * PageRating renders given page rating.
 *
 * @package AnyComment\Seo\Generator
 */
class PageRating
{
    /**
     * @var Page
     */
    private $page;

    /**
     * PageRating constructor.
     * @param Page $page
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    public function __toString()
    {
        $pageTitle = $this->page->page_title;
        $pageUrl = $this->page->url;
        $ratingAverage = $this->page->rating_average;
        $ratingCount = $this->page->rating_count;
        return <<<HTML
<div itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="$pageTitle">
    <meta itemprop="url" content="$pageUrl">
    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <span itemprop="ratingValue">$ratingAverage</span>
        <span itemprop="reviewCount">$ratingCount</span>
    </div>
</div>
HTML;
    }
}