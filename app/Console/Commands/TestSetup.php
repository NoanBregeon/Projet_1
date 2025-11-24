<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TestSetup extends Command
{
    protected $signature = 'test:setup';

    protected $description = 'Créer la base de test (si besoin) et exécuter les migrations sur la BDD de test';

    public function handle(): int
    {
        // Récupérer config depuis .env.testing si dispo
        $envPath = base_path('.env.testing');
        $env = $this->parseEnvFile($envPath);

        $dbName = $env['DB_DATABASE'] ?? config('database.connections.mysql.database', 'laravel');
        if (! Str::endsWith($dbName, '_test')) {
            $dbNameTest = $dbName.'_test';
        } else {
            $dbNameTest = $dbName;
        }

        // Appeler la commande de création (réutilise db:create-test)
        $this->info("Vérification/création de la base : {$dbNameTest}");
        $exit = Artisan::call('db:create-test');
        if ($exit !== 0) {
            $this->error('La création de la base de test a échoué.');

            return 1;
        }

        // Forcer la config runtime pour que les migrations ciblent la BDD de test
        config(['database.connections.mysql.database' => $dbNameTest]);
        $this->info("Configuration runtime de la connexion mysql vers '{$dbNameTest}'.");

        // Lancer les migrations (force pour éviter confirmation en CI)
        $this->info('Exécution des migrations sur la base de test...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Migrations terminées.');

        return 0;
    }

    /**
     * @return array<string,string>
     */
    private function parseEnvFile(string $path): array
    {
        if (! File::exists($path)) {
            return [];
        }

        $lines = preg_split('/\r\n|\n|\r/', File::get($path)) ?: [];
        $result = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (! str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $value = trim($value);
            $value = preg_replace('/(^[\'"]|[\'"]$)/', '', $value);
            $result[trim($key)] = $value;
        }

        return $result;
    }
}
