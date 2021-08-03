<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetTasksWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset weekly tasks.';

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
     * @return int
     */
    public function handle()
    {
        Task::where('completed', 1)->whereNotNull('weekly')->update(['completed' => 0, 'completedby' => NULL]);
        echo "Operation Complete";

    }
}
