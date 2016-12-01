<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Dumper;

class FileSystemStorage implements Storage
{

    public function storeStaticSite($location, StaticSiteDumper $dumper)
    {
        $location = realpath(parse_url($location, PHP_URL_PATH));

        if ("" === $location || false === is_dir($location)) {
            throw new \InvalidArgumentException('Target path must exist and needs to be a directory');
        }

        $mainHtaccessPath = null;

        foreach ($dumper->getStaticSite() as $path => $response) {

            $flags = 0;

            $targetPath = rtrim($location . $path, '/');

            // append redirects to a .htaccess file
            if ($response->isRedirection()) {
                // don’t append the first time!
                $flags = $mainHtaccessPath ? FILE_APPEND : $flags;
                $mainHtaccessPath = $location . '/.htaccess';

                $targetPath = $response->headers->get("Location");
                $response->setContent(sprintf("Redirect %s %s %s\n", $response->getStatusCode(), $path, $targetPath));

                // whoa! treat source path as target path for beter verbosity in log files!
                // and replace file path to store the contents to a main .htaccess file
                $path = $targetPath;
                $targetPath = $mainHtaccessPath;
            }

            if ("" === pathinfo($targetPath, PATHINFO_EXTENSION)) {
                $targetPath = $targetPath . '/index.html';
            }

            $dirname = dirname($targetPath);

            if (false === is_dir($dirname) && false === $response->isRedirection()) {
                mkdir($dirname, 0750, true);
            }

            file_put_contents($targetPath, $response->getContent(), $flags);

            yield new StaticItem($response, $path, $targetPath);
        }
    }
}
