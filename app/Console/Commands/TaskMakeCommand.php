<?php

namespace MyBB\Core\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use MyBB\Core\Database\Repositories\TasksRepositoryInterface;

class TaskMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new MyBB Task';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'MyBB Task';

    /**
     * @var DatabaseManager
     */
    protected $dbManager;

    /**
     * @var TasksRepositoryInterface
     */
    protected $tasksRepository;

    /**
     * TaskMakeCommand constructor.
     * @param Filesystem $files
     * @param DatabaseManager $dbManager
     * @param TasksRepositoryInterface $tasksRepository
     */
    public function __construct(
        Filesystem $files,
        DatabaseManager $dbManager,
        TasksRepositoryInterface $tasksRepository
    ) {
        parent::__construct($files);

        $this->dbManager = $dbManager;
        $this->tasksRepository = $tasksRepository;
    }

    /**
     * Fire command
     *
     * @return void
     */
    public function fire()
    {
        $fileCreated = parent::fire();

        if ($this->dbManager->connection() && $fileCreated !== false && !$this->option('no-database')) {
            $task = $this->tasksRepository->createTask([
                'namespace' => $this->getDefaultNamespace('MyBB\Core') . '\\' . $this->argument('name'),
                'frequency' => $this->option('frequency'),
                'name'      => $this->option('name'),
                'desc'      => $this->option('description'),
                'logging'   => 1,
                'enabled'   => (int)$this->option('enabled'),
                'last_run'  => time(),
                'next_run'  => time(),
            ]);

            if ($task) {
                $this->info('Task successfully inserted to database');
            }
        }
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string $stub
     * @param  string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('command', $this->option('command'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/task.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Tasking\Tasks';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the task command.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned.', 'command'],
            ['frequency', 'f', InputOption::VALUE_OPTIONAL, 'Frequency of task execution.', '* * * * *'],
            ['name', 'n', InputOption::VALUE_OPTIONAL, 'Task display name language path.', 'admin::tasks.task'],
            ['desc', 'd', InputOption::VALUE_OPTIONAL, 'Task description language path.', 'admin::tasks.task'],
            ['enabled', 'e', InputOption::VALUE_OPTIONAL, 'Enable task?', 0],
            ['no-database', null, InputOption::VALUE_NONE, 'Do not insert task to database', null],
        ];
    }
}
