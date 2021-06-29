<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use Faker\Generator;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

class HomeController
{

    /** @var ViewsInterface */
    private $views;
    /**
     * @param ViewsInterface $views
     */
    public function __construct(ViewsInterface $views)
    {
        $this->views = $views;
    }
    /**
     * @Route(route="/", name="index", methods={"GET"})
     */
    public function index(Generator $generator): string
    {
        $sentence = $generator->sentence(128);

        return $this->views->render('home.dark.php', [
            'sentence' => $sentence,
        ]);
    }
}
