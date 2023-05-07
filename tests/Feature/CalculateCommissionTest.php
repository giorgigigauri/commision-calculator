<?php

namespace Tests\Feature;

use App\Actions\CalculateCommissionAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CalculateCommissionTest extends TestCase
{
    /** @test */
    public function it_calculate()
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
        $transactionCommissions = [
            0.60,
            3.00,
            0.00,
            0.06,
            1.50,
            0,
            0.70,
            0.30,
            0.30,
            3.00,
            0.00,
            0.00,
            8612
        ];

        $commissions = (new CalculateCommissionAction())->execute();
        $this->assertInstanceOf(Collection::class, $commissions);
        foreach($commissions as $index => $transaction) {
            //dump([$transactionCommissions[$index], $transaction->commission]);
            $this->assertEquals($transactionCommissions[$index], $transaction->commission);
        }
    }
}
