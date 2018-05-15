<?php

namespace Fivesqrd\Mutex\Laravel\Console;

use Illuminate\Console\Command;
use Config;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mutex:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test lock with Mutex service';

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
        if (!resolve('mutex')->lock(self::class)->acquire()) {
            $this->info("Failed to acquire lock for this command");
            return;
        }

        /* logic here */

        $this->info("Command completed successfully");
    }
}
