<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation;

class ModerationTool implements ModerationInterface
{
    /**
     * @var ModerationInterface[]
     */
    protected $moderations;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $permissionName;

    /**
     * @param string $name
     * @param string $desc
     * @param string $permissionName
     * @param ModerationInterface[] $moderations
     */
    public function __construct(
        string $name,
        string $description,
        string $permissionName = null,
        array $moderations = []
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->permissionName = $permissionName;

        foreach ($moderations as $moderation) {
            $this->addModeration($moderation);
        }
    }

    /**
     * @param ModerationInterface $moderation
     */
    public function addModeration(ModerationInterface $moderation)
    {
        $this->moderations[$moderation->getName()] = $moderation;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param mixed $content
     *
     * @param array $options
     *
     * @return mixed
     */
    public function apply($content, array $options = [])
    {
        foreach ($this->moderations as $moderation) {
            $moderation->apply($content);
        }
    }

    /**
     * @param mixed $content
     *
     * @param array $options
     *
     * @return bool
     */
    public function supports($content, array $options = []) : bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return 'custom-tool';
    }

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function visible($content) : bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getPermissionName() : string
    {
        return $this->permissionName;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }
}
