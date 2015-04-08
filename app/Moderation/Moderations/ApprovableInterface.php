<?php

namespace MyBB\Core\Moderation\Moderations;

interface ApprovableInterface
{
    public function approve();

    public function unapprove();
}
