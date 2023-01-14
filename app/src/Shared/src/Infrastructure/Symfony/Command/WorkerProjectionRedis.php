<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Command;

use Shared\Infrastructure\Bus\Projection\Projector\Redis\ProjectorConsumer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'worker:projection-redis')]
class WorkerProjectionRedis extends Command
{
    public function __construct(
        protected readonly ProjectorConsumer $workerProjectionRedis
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->workerProjectionRedis->consume();

        return Command::SUCCESS;
    }
}
