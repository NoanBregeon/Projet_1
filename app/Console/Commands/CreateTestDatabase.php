<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PDO;
use PDOException;

class CreateTestDatabase extends Command
{
    protected $signature = 'db:create-test';

    protected $description = 'Créer la base de données de test (utilise .env.testing si présent)';

    public function handle()
    {
        // Charger la config depuis .env.testing si existe, sinon depuis env()
        $envPath = base_path('.env.testing');
        $env = $this->parseEnvFile($envPath);

        $host = $env['DB_HOST'] ?? env('DB_HOST', '127.0.0.1');
        $port = $env['DB_PORT'] ?? env('DB_PORT', '3306');
        $username = $env['DB_USERNAME'] ?? env('DB_USERNAME', 'root');
        $password = $env['DB_PASSWORD'] ?? env('DB_PASSWORD', '');
        $dbName = $env['DB_DATABASE'] ?? env('DB_DATABASE', 'laravel');

        // garantir suffixe _test
        if (! Str::endsWith($dbName, '_test')) {
            $dbNameTest = $dbName.'_test';
        } else {
            $dbNameTest = $dbName;
        }

        $this->info("Création de la base de données de test : {$dbNameTest} sur {$host}:{$port}");

        try {
            $dsn = "mysql:host={$host};port={$port}";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbNameTest}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $this->info("Base de données '{$dbNameTest}' créée (ou déjà existante).");

            return 0;
        } catch (PDOException $e) {
            $this->error('Erreur durant la création de la base de données : '.$e->getMessage());

            return 1;
        }
    }

    private function parseEnvFile($path)
    {
        if (! File::exists($path)) {
            return [];
        }

        $lines = preg_split('/\r\n|\n|\r/', File::get($path));
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
            // enlever éventuelles quotes
            $value = preg_replace('/(^[\'"]|[\'"]$)/', '', $value);
            $result[trim($key)] = $value;
        }

        return $result;
    }
}
