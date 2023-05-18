<?php

declare(strict_types=1);

/*
 * This file is part of Contao Culture Events.
 *
 * (c) Björn Heyser 2023 <bh@bjoernheyser.de>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/jopawu/contao-culture-events-bundle
 */

namespace Jopawu\ContaoCultureEventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

#[Route('/my_custom', name: 'jopawu_contao_culture_events_my_custom', defaults: ['_scope' => 'frontend', '_token_check' => true])]
class MyCustomController extends AbstractController
{
    private TwigEnvironment $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(): Response
    {
        $animals = [
            [
                'species' => 'dogs',
                'color' => 'white',
            ],
            [
                'species' => 'birds',
                'color' => 'black',
            ], [
                'species' => 'cats',
                'color' => 'pink',
            ], [
                'species' => 'cows',
                'color' => 'yellow',
            ],
        ];

        return new Response($this->twig->render(
            '@JopawuContaoCultureEvents/MyCustom/my_custom.html.twig',
            [
                'animals' => $animals,
            ]
        ));
    }
}
