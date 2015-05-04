<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subberbundle\Command;

use BD\Subber\WatchList\WatchListItem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowWatchListCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('subber:watchlist:show');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $watchlist = $this->getContainer()->get('bd_subber.watchlist');

        $table = new Table($output);
        $table->setHeaders(['Release', 'Status', 'Rating', 'Has subtitles']);

        /** @var WatchListItem $item */
        foreach ($watchlist->findAllActiveItems() as $item) {
            $table->addRow(
                [
                    $item->getOriginalName(),
                    $this->getTextStatus($item->getStatus()),
                    $item->getRating(),
                    $item->hasSubtitle() ? 'Y' : 'N',
                ]
            );
        }

        $table->render();
    }

    private function getTextStatus($numericStatus)
    {
        $map = [
            WatchListItem::STATUS_NEW => 'New',
            WatchListItem::STATUS_DONE => 'Done',
            WatchListItem::STATUS_FINISHED => 'Finished',
        ];

        return $map[$numericStatus];
    }
}
