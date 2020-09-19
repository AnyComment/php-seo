<?php


namespace AnyComment\Seo\Generator;

use AnyComment\Dto\Comment\Index\User;
use AnyComment\Dto\Comment\Index\Comment;

/**
 * SingleComment represents single comment HTML structure.
 *
 * @package AnyComment\Generator
 */
class SingleComment
{
    /**
     * @var Comment
     */
    private $comment;
    /**
     * @var User
     */
    private $author;

    /**
     * @var string Root HTML element, comment structure would be inside this element.
     */
    private $rootElement = 'li';

    /**
     * SingleComment constructor.
     * @param $comment
     * @param $author
     */
    public function __construct($comment, $author)
    {
        if (!$comment instanceof Comment) {
            throw new \InvalidArgumentException('Wrong comment instance passed, expected ' . Comment::class);
        }

        if (!$author instanceof User) {
            throw new \InvalidArgumentException('Wrong user instance passed, expected ' . User::class);
        }

        $this->author = $author;
        $this->comment = $comment;
    }


    /**
     * HTML representation of single comment.
     *
     * @return string
     */
    public function __toString()
    {
        $likeCount = $this->comment->like_count;
        $dislikeCount = $this->comment->dislike_count;
        $dateCreated = date('c', strtotime($this->comment->created_date));
        $authorUrl = $this->author->social_url;
        $authorName = $this->author->name;
        $commentContent = $this->comment->content;

        $authorNameTemplate = <<<HTML
<span itemprop="name">$authorName</span>
HTML;

        if (!empty($authorUrl)) {
            $authorNameTemplate = <<<HTML
<span itemprop="name"><a href="$authorUrl" rel="external nofollow" itemprop="url">$authorName</a></span>
HTML;
        }

        $tag = $this->rootElement;

        return <<<HTML
<{$tag} itemtype="http://schema.org/Comment" itemscope="">
    <div>
        <div itemprop="upvoteCount">$likeCount</div>
        <div itemprop="downvoteCount">$dislikeCount</div>
        
        <time itemprop="dateCreated" datetime="$dateCreated" itemprop="dateCreated">$dateCreated</time>
    </div>
    <div>
        <p itemprop="creator" itemscope itemtype="http://schema.org/Person">
            $authorNameTemplate
        </p>
    </div>
    <div itemprop="text">$commentContent</div>
</{$tag}>
HTML;

    }

    /**
     * @param string $rootElement
     */
    public function setRootElement($rootElement)
    {
        if (!is_string($rootElement)) {
            throw new \InvalidArgumentException('Root element should be a string type');
        }
        $this->rootElement = $rootElement;
    }
}
