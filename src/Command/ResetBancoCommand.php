<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:reset-banco',
    description: 'Reseta completamente o banco de dados e popula com dados mockados',
)]
class ResetBancoCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Reset Completo do Banco de Dados');
        
        try {
            $io->section('Removendo schema...');
            $command = $this->getApplication()->find('doctrine:schema:drop');
            $returnCode = $command->run(new ArrayInput(['--force' => true]), $output);
            if ($returnCode !== 0) throw new \Exception('Erro ao remover schema');

            $io->section('Criando schema...');
            $command = $this->getApplication()->find('doctrine:schema:create');
            $returnCode = $command->run(new ArrayInput([]), $output);
            if ($returnCode !== 0) throw new \Exception('Erro ao criar schema');

            $io->section('Populando banco...');
            $command = $this->getApplication()->find('app:popular-banco');
            $returnCode = $command->run(new ArrayInput(['--no-interaction' => true]), $output);
            if ($returnCode !== 0) throw new \Exception('Erro ao popular banco');

            $io->success('Banco de dados resetado com sucesso!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Erro ao resetar banco: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
