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
        $data = [
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password')
        ];

        $messages = [
            'name.required' => 'Um :attribute de usuário é obrigatório.',
            'name.max' => 'O :attribute de usuário é muito grande',
            'email.email' => 'O :attribute informado não é um endereço email válido',
            'email.unique' => 'O :attribute já está sendo usando por outro usuário',
            'passowrd.required' => 'É necessário inserir uma :attribute',
            'password.min' => 'A :attribute é muito curta'
        ];

        $attributes = [
            'name' => 'nome', 
            'email' => 'e-mail',
            'password' => 'senha'
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:5'
        ], $messages, $attributes);

        
        if ($validator->fails()) {
            dd($validator->errors());
        };

        
    }
}
