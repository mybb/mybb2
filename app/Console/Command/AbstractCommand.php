<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->fire();
    }

    /**
     * Fire the command.
     */
    abstract protected function fire();

    /**
     * Did the user pass the given option?
     *
     * @param string $name
     * @return bool
     */
    protected function hasOption(string $name)
    {
        return $this->input->hasOption($name);
    }

    /**
     * Send an info string to the user.
     *
     * @param string $string
     */
    protected function info(string $string)
    {
        $this->output->writeln("<info>$string</info>");
    }
}
