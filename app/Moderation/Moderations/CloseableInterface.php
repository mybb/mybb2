<?php

namespace MyBB\Core\Moderation\Moderations;

interface CloseableInterface
{
	public function close();

	public function open();
}
