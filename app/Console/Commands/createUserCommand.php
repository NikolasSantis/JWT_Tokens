<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Validator;

class createUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user-command {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    }
}
