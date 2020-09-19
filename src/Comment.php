<?php


namespace AnyComment\Seo;

use Stash\Pool;
use AnyComment\Api;
use AnyComment\Config;
use Stash\Driver\FileSystem;
use Symfony\Component\HttpFoundation\Request;
use AnyComment\Seo\Generator\SeoGenerator;
use AnyComment\Dto\Comment\Index\CommentIndexResponse;

/**
 * AnyCommentSeo is the main class to process comments from AnyComment REST API.
 *
 * @package AnyComment\Seo
 */
class Comment
{
    /**
     * @var string API key to make requests.
     */
    private $apiKey;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Pool
     */
    private $cache;

    /**
     * @var array List of generate options.
     * @see SeoGenerator::DEFAULT_CONFIG for details about available options.
     */
    private $generateOptions = [];

    /**
     * @param string $apiKey Website API key to make requests to AnyComment REST API.
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        if ($this->request === null) {
            $this->request = Request::createFromGlobals();
        }

        if ($this->cache === null) {
            $cachePath = dirname(__DIR__ . '..') . str_replace('/', DIRECTORY_SEPARATOR, '/runtime/cache');
            if (!is_dir($cachePath)) {
                @mkdir($cachePath, 0770, true);
            }
            $driver = new FileSystem(['path' => $cachePath]);
            $this->cache = new Pool($driver);

        }
    }

    /**
     * @param $pageUrl
     * @param null $createdAt
     * @return mixed|string
     * @throws \AnyComment\Exceptions\ClassMapException
     * @throws \AnyComment\Exceptions\RequestFailException
     * @throws \AnyComment\Exceptions\UnexpectedResponseException
     */
    public function render($pageUrl, $createdAt = null)
    {
        $cacheKey = 'anycomment/generated/' . md5($pageUrl);
        $item = $this->cache->getItem($cacheKey);
        if (!$item->isMiss()) {
            return $item->get();
        }

        $config = new Config($this->apiKey);
        $api = new Api($config);
        /** @var $commentListResponse CommentIndexResponse */
        $commentListResponse = $api->getComment()->getList($createdAt, $pageUrl);
        $generator = new SeoGenerator($commentListResponse);
        $generator->withOptions($this->generateOptions);
        $content = $generator->generate();
        $item->set($content);
        $item->expiresAfter(strtotime(date('d-m-Y 23:59:59')) - time());
        $item->save();
        return $content;
    }

    /**
     * Set custom request component.
     * @param $request
     */
    public function withRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Set custom cache component.
     *
     * @param $cache
     */
    public function withCache($cache)
    {
        $this->cache = $cache;
    }
}
