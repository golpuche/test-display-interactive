<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Infrastructure\Service\CustomerPersister;
use App\Infrastructure\Service\OrderPersister;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'ugo:orders:import',
    description: 'Import all customers and their associated orders.'
)]
final class ImportOrdersCommand extends Command
{
    private const DATA_FOLDER_PATH = 'data/import';
    private const CUSTOMERS_DATA_FILE_NAME = 'customers.csv';
    private const PURCHASES_DATA_FILE_NAME = 'purchases.csv';

    private array $customers = [];

    public function __construct(
        private readonly CustomerPersister $customerPersister,
        private readonly OrderPersister $orderPersister,
    ) {
        parent::__construct('ugo:orders:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info(['', 'Import started...']);

        try {
            $this->import($io, self::CUSTOMERS_DATA_FILE_NAME, fn (array $data) => $this->persistCustomer($data));
            $this->import($io, self::PURCHASES_DATA_FILE_NAME, fn (array $data) => $this->persistOrder($data));
        } catch (\LogicException $e) {
            return Command::FAILURE;
        }

        $io->success(['Orders import has been successfully completed', '']);

        return Command::SUCCESS;
    }

    private function import(SymfonyStyle $io, string $dataFilename, callable $persistCallable): void
    {
        $finder = new Finder();
        $finder->files()->in(self::DATA_FOLDER_PATH)->name($dataFilename);

        if (0 === $finder->count()) {
            $io->error('Required csv file missing');

            return;
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();

        $csv = $iterator->current();

        if (($handle = fopen($csv->getRealPath(), 'r')) !== false) {
            $i = 0;

            while (($data = fgetcsv($handle, null, ';')) !== false) {
                if (0 === $i) {
                    ++$i;

                    continue;
                }

                $persistCallable($data);

                ++$i;
            }

            fclose($handle);
        }
    }

    private function persistCustomer(array $data): void
    {
        $customer = $this->customerPersister->persist($data);

        $this->customers[$data[CustomerPersister::CUSTOMER_DATA_ID]] = $customer;
    }

    private function persistOrder(array $data): void
    {
        $customer = $this->customers[$data[OrderPersister::ORDER_DATA_CUSTOMER_ID]];

        $this->orderPersister->persist($data, $customer);
    }
}
