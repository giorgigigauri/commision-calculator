<?php

namespace Tests\Feature;

use App\Actions\GetTransactions;
use app\Enums\Currency;
use app\Enums\TransactionType;
use app\Enums\UserType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ParseCsvActionTest extends TestCase
{
    /** @test */
    public function it_parses_csv_file()
    {

        $storage = Storage::fake('local');
        $storage->put('input.csv', '2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.00,EUR
2016-01-06,1,private,withdraw,30000,JPY
2016-01-07,1,private,withdraw,1000.00,EUR
2016-01-07,1,private,withdraw,100.00,USD
2016-01-10,1,private,withdraw,100.00,EUR
2016-01-10,2,business,deposit,10000.00,EUR
2016-01-10,3,private,withdraw,1000.00,EUR
2016-02-15,1,private,withdraw,300.00,EUR
2016-02-19,5,private,withdraw,3000000,JPY');
        $result = (new GetTransactions())->execute();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(13, $result->count());

        $firstRow = $result->first();
        $this->assertIsObject($firstRow);
        $this->assertEquals('2014-12-31 00:00:00', $firstRow->date);
        $this->assertEquals(4, $firstRow->userId);
        $this->assertEquals(UserType::from('private'), $firstRow->userType);
        $this->assertEquals(TransactionType::from('withdraw'), $firstRow->transactionType);
        $this->assertEquals(1200.00, $firstRow->amount);
        $this->assertEquals(Currency::from('EUR'), $firstRow->currency);
    }
}
