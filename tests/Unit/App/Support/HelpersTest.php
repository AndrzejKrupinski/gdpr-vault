<?php

namespace Tests\Unit\App\Support;

class HelpersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider str_conceal_provider
     */
    public function str_conceal($string, $expected, $visible, $character, $pattern)
    {
        $result = str_conceal($string, $visible, $character, $pattern);

        $this->assertSame($expected, $result);
    }

    public static function str_conceal_provider()
    {
        return [
            ['this is a valid 12@email.me', '---------------------------', 0, '-', '/./'],
            ['this is a valid 12@email.me', 'this is a valid **@email.me', 3, '*', '/[0-9]/'],
            ['this is a valid 12@email.me', 'this is a xxxxx xx@xxxxx.xx', 10, 'x', null],
            ['this is a valid 12@email.me', 'thi* ** a val*d 12@ema*l.me', 3, '*', '/[is]/'],
        ];
    }
}
