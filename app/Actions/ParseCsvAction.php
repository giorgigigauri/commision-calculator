<?php

namespace App\Actions;

use App\Data\UserTransactionData;
use App\Enums\Currency;
use App\Enums\TransactionType;
use App\Enums\UserType;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ParseCsvAction
{
    /**
     * Parses a CSV file into an array of objects.
     *
     * @param string $filePath The path to the CSV file.
     *
     * @return Collection The parsed CSV data.
     *
     * @throws FileException If the file cannot be read.
     * @throws Exception If the CSV data cannot be parsed.
     */

    private $csv;

    public function setCsv($csv)
    {
        $this->csv = $csv;
        return $this;
    }

    private function getCsv()
    {
        return $this->csv;
    }

    public function execute(): array
    {
        $csv = $this->getCsv();
        $lines = explode("\n", $csv);

        $transaction_data = [];

        $index = 0;
        foreach ($lines as $line) {
            // parse the line
            $data = str_getcsv($line);
            if (is_null($data[0])) {
                continue;
            }
            $index++;
            $transaction_data[] = UserTransactionData::from([
                'id' => $index,
                'date' => $data[0] . ' 00:00:00',
                'userId' => $data[1],
                'userType' => UserType::from($data[2]),
                'transactionType' => TransactionType::from($data[3]),
                'amount' => $data[4],
                'currency' => Currency::from($data[5]),
            ]);
        }

        return ($transaction_data);
    }
}
