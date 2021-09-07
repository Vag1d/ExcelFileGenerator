<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Num2WordExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('num2word', [$this, 'num2word']),
        ];
    }

    public function num2word($n, $words)
    {
        return ($words[($n = ($n = $n % 100) > 19 ? ($n % 10) : $n) == 1 ? 0 : (($n > 1 && $n <= 4) ? 1 : 2)]);
    }
}