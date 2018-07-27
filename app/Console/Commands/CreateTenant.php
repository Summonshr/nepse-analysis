<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {domain} {email} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        User::forceCreate(['email'=>$this->argument('email'),'password'=>$this->argument('password'),'domain'=>$this->argument('domain')]);
    }
}
