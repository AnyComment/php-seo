<?php


namespace AnyComment\Seo\Generator;

use AnyComment\Dto\Comment\Index\CommentIndexResponse;

class SeoGenerator
{
    const DEFAULT_CONFIG = [
        // Whether to display page 5 start rating or not
        'show_page_rating' => false,
        // HTML tag name used as root DOM element for list of comments
        'comment_list_root_tag' => 'ul',
        // HTML tag name used for single comment
        'comment_list_item_tag' => 'li',
    ];

    /**
     * @var CommentIndexResponse
     */
    private $responseEnvelope;

    /**
     * @var array Configuration.
     */
    private $options = self::DEFAULT_CONFIG;

    /**
     * CommentListResponse constructor.
     * @param CommentIndexResponse $responseEnvelope
     */
    public function __construct($responseEnvelope)
    {
        if (!$responseEnvelope instanceof CommentIndexResponse) {
            throw new \InvalidArgumentException('Wrong instance passed');
        }
        $this->responseEnvelope = $responseEnvelope;
    }

    /**
     * Generates HTML indexable content.
     *
     * @return string
     */
    public function generate()
    {
        $html = '<noscript>';
        $html .= '<ul>';
        foreach ($this->responseEnvelope->items as $comment) {
            $commentAuthor = $this->responseEnvelope->users[$comment->author_id];
            $singleComment = new SingleComment($comment, $commentAuthor);
            $singleComment->setRootElement($this->options['comment_list_item_tag']);
            $html .= $singleComment;
        }

        $html .= '</ul>';
        $html .= '</noscript>';

        return $html;
    }

    /**
     * Ability to provide custom configuration to default rendering options.
     *
     * @param array $options
     */
    public function withOptions($options)
    {
        if (!is_array($options)) {
            throw new \InvalidArgumentException('Config should be provided as array');
        }

        $defaultConfig = self::DEFAULT_CONFIG;

        foreach ($options as $key => $value) {
            if (!isset($defaultConfig[$key])) {
                throw new \DomainException(
                    'Config key ' . $key . ' does not exist, available config keys: ' . array_keys($defaultConfig)
                );
            }
        }

        $this->options = array_merge($this->options, $options);
    }
}
