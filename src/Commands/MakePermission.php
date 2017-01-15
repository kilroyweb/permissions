<?php

namespace KilroyWeb\Permissions\Commands;

use Illuminate\Console\Command;

class MakePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:permission {className}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new permission class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $creator = new \KilroyWeb\Permissions\Creator\PermissionCreator();
        $creator->setInterface($this);
        $creator->setClassName($this->argument('className'));
        $creator->create();
    }
}