<?php

namespace Nassau\KunstmaanStaticSiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpStaticSiteCommand extends ContainerAwareCommand
{
    const ARGUMENT_TARGET = 'target';

    protected function configure()
    {
        $this->setName('nassau:static-site:dump')
            ->addArgument(self::ARGUMENT_TARGET, InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dumper = $this->getContainer()->get('nassau.static_site.dumper');
        $storage = $this->getContainer()->get('nassau.static_site.storage');

        $target = $input->getArgument(self::ARGUMENT_TARGET);

        foreach ($storage->storeStaticSite($target, $dumper) as $item) {
            $output->writeln(sprintf('Writing <comment>%s</comment>', $item->getPath()));
        }
    }

}
