<?php

namespace App\Console\Commands;

use FilesystemIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class GemeosBackup extends Command
{
    protected $signature = 'gemeos:backup';

    protected $description =
        'Cria backup do banco e das fotos da Gêmeos Interiores';

    public function handle(): int
    {
        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÕES
        |--------------------------------------------------------------------------
        */

        $backupBase = config('gemeos.backup_path');

        $mysqldump = config(
            'gemeos.mysqldump_path'
        );


        /*
        |--------------------------------------------------------------------------
        | CONFERE MYSQLDUMP
        |--------------------------------------------------------------------------
        */

        if (
            !$mysqldump ||
            !File::exists($mysqldump)
        ) {

            $this->error(
                'mysqldump.exe não foi encontrado.'
            );

            $this->line(
                'Caminho configurado: ' .
                $mysqldump
            );

            return self::FAILURE;
        }


        /*
        |--------------------------------------------------------------------------
        | CRIA PASTA DO BACKUP
        |--------------------------------------------------------------------------
        */

        $data = now()->format(
            'Y-m-d_H-i-s'
        );


        $pastaBackup =
            rtrim(
                $backupBase,
                '/\\'
            )
            . DIRECTORY_SEPARATOR
            . $data;


        if (!File::exists($pastaBackup)) {

            File::makeDirectory(
                $pastaBackup,
                0755,
                true
            );
        }


        $this->info(
            'Iniciando backup...'
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÃO DO MYSQL
        |--------------------------------------------------------------------------
        */

        $mysql = config(
            'database.connections.mysql'
        );


        $banco =
            $mysql['database'] ?? null;

        $usuario =
            $mysql['username'] ?? null;

        $senha =
            $mysql['password'] ?? '';

        $host =
            $mysql['host'] ?? '127.0.0.1';

        $porta =
            $mysql['port'] ?? '3306';


        if (!$banco || !$usuario) {

            $this->error(
                'Configuração do MySQL incompleta.'
            );

            return self::FAILURE;
        }


        /*
        |--------------------------------------------------------------------------
        | BACKUP DO BANCO
        |--------------------------------------------------------------------------
        */

        $arquivoSql =
            $pastaBackup
            . DIRECTORY_SEPARATOR
            . 'banco_gemeos_interiores.sql';


        $this->line(
            'Criando backup do banco...'
        );


        $comando = [

            $mysqldump,

            '--host=' . $host,

            '--port=' . $porta,

            '--user=' . $usuario,

            '--single-transaction',

            '--routines',

            '--triggers',

            '--events',

            '--no-tablespaces',

            '--default-character-set=utf8mb4',

            '--result-file=' . $arquivoSql,

            $banco,

        ];


        /*
        |--------------------------------------------------------------------------
        | EXECUTA MYSQLDUMP
        |--------------------------------------------------------------------------
        |
        | A senha é passada como variável de ambiente,
        | evitando colocá-la diretamente no comando.
        |
        */

        $resultado = Process::timeout(300)
            ->env([
                'MYSQL_PWD' => (string) $senha,
            ])
            ->run($comando);


        if ($resultado->failed()) {

            $this->error(
                'Erro ao criar backup do banco.'
            );

            $this->error(
                $resultado->errorOutput()
            );

            return self::FAILURE;
        }


        if (!File::exists($arquivoSql)) {

            $this->error(
                'O arquivo SQL não foi criado.'
            );

            return self::FAILURE;
        }


        $this->info(
            'Banco salvo com sucesso.'
        );


        /*
        |--------------------------------------------------------------------------
        | BACKUP DAS FOTOS
        |--------------------------------------------------------------------------
        */

        $pastaFotos = storage_path(
            'app/public/pecas'
        );


        $arquivoZip =
            $pastaBackup
            . DIRECTORY_SEPARATOR
            . 'fotos.zip';


        $this->line(
            'Compactando fotos...'
        );


        $zip = new ZipArchive();


        $resultadoZip = $zip->open(
            $arquivoZip,
            ZipArchive::CREATE |
            ZipArchive::OVERWRITE
        );


        if ($resultadoZip !== true) {

            $this->error(
                'Não foi possível criar fotos.zip.'
            );

            return self::FAILURE;
        }


        /*
        |--------------------------------------------------------------------------
        | ADICIONA FOTOS AO ZIP
        |--------------------------------------------------------------------------
        */

        if (File::exists($pastaFotos)) {

            $arquivos =
                new RecursiveIteratorIterator(

                    new RecursiveDirectoryIterator(
                        $pastaFotos,
                        FilesystemIterator::SKIP_DOTS
                    ),

                    RecursiveIteratorIterator::LEAVES_ONLY
                );


            foreach ($arquivos as $arquivo) {

                if (!$arquivo->isFile()) {
                    continue;
                }


                $caminhoReal =
                    $arquivo->getRealPath();


                if ($caminhoReal === false) {
                    continue;
                }


                $caminhoRelativo =
                    substr(
                        $caminhoReal,
                        strlen($pastaFotos)
                    );


                $caminhoRelativo =
                    ltrim(
                        str_replace(
                            '\\',
                            '/',
                            $caminhoRelativo
                        ),
                        '/'
                    );


                $zip->addFile(
                    $caminhoReal,
                    'pecas/' .
                    $caminhoRelativo
                );
            }
        }


        $zip->close();


        /*
        |--------------------------------------------------------------------------
        | FINALIZA
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'BACKUP CONCLUÍDO COM SUCESSO!'
        );


        $this->line(
            'Local: ' .
            $pastaBackup
        );


        $this->line(
            'Banco: banco_gemeos_interiores.sql'
        );


        $this->line(
            'Fotos: fotos.zip'
        );


        return self::SUCCESS;
    }
}