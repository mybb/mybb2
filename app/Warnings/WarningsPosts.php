<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Warnings;

use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Exceptions\PostNotFoundException;

class WarningsPosts implements WarnableContentInterface
{

    /**
     * @var PostRepositoryInterface
     */
    private $contentRepository;

    /**
     * WarningsPosts constructor.
     * @param PostRepositoryInterface $contentRepository
     */
    public function __construct(PostRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
    * {@inheritdoc}
    */
    public function getContentType()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningContent($contentId)
    {
        $post = $this->contentRepository->find($contentId);
        if(!$post)
            throw new PostNotFoundException;

        $content = [
            'user_id' => $post->user_id,
            'content' => $post->content,
        ];

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningPreviewView($content)
    {
        //todo use post parser

        return view('warnings.content_post_preview', compact('content'));
    }

}
