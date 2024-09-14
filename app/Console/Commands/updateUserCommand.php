<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Question\Question;

class updateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update_user_command {--id= : User id} {--email= : User email}';

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
        $userId = $this->option('id');
        $userEmail = $this->option('email');

        $user = User::where('id', $userId)
            ->orWhere('email', $userEmail)
            ->get()[0];

        echo "\n Usuário $user->name encontrado. Inserir novos valores: ";
        $updateValues = [
            'name' => $this->ask('Novo nome de usuário'),
            'email' => $this->ask('Novo email de usuário'),
            'password' => $this->secret('Nova senha de usuário'),
        ];

        if (empty(array_filter($updateValues))) {
            dd('Empty array to update. Try again with some value');
        }

        $validator = Validator::make($updateValues, [
            'name' => 'string|max:255|nullable',
            'email' => 'email|unique:users|nullable',
            'password' => 'string|min:5|nullable'
        ]);

        if ($validator->fails()) {
            dd($validator->errors()); 
        }

        echo "Dados para o usuário $user->name com Id $user->id";
        foreach($updateValues as $label => $value) {
            if ($label == 'password') {
                $value = str_repeat('*', strlen($value));
            }
            echo "\n $label => " . ($value ?? 'null');
        }

        if ($this->ask("\n\nConfirma os valores de atualização[y/n]") == 'n') {
            echo 'Valores não atualizados.';
            return;
        }

        $updateValues['password'] = !empty($updateValues['password']) ?
            password_hash($updateValues['password'], PASSWORD_BCRYPT, ['cost' => 12]) :
            null;

        try {
            $affectedRows = $user::where('id', $userId)->update(
                array_filter($updateValues)
            );
        } catch (\Exception $e) {
            return $e;
        }
        echo "$affectedRows Rows Affected";
        return true;
    }
}
