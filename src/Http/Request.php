<?php


namespace AnyComment\Seo\Http;

use Symfony\Component\HttpFoundation\Request as SymphonyRequest;


/**
 * Request a custom request wrapper around symphony to add extra helpers.
 * @package AnyComment\Seo\Http
 */
class Request
{
    private $request;

    public function __construct($request = null)
    {
        if ($request === null) {
            $this->request = SymphonyRequest::createFromGlobals();
        }
    }

    /**
     * Returns current page URL.
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        $protocol = 'http://';

        if ($this->request->server->get('HTTP_X_FORWARDED_PROTO') === 'https') {
            $protocol = 'https://';
        }

        return $protocol .
            $this->request->server->get('HTTP_HOST') .
            $this->request->server->get('REQUEST_URI');
    }
}
