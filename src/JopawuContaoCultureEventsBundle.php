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

namespace Jopawu\ContaoCultureEventsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JopawuContaoCultureEventsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
