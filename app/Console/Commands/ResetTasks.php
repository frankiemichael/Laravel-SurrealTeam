<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
class ResetTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset daily tasks.';

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
        Task::where('completed', 1)->whereNotNull('daily')->update(['completed' => 0]);
        echo "Operation Complete";
    }
}
