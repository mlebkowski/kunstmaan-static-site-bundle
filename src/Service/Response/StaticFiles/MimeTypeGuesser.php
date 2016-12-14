<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response\StaticFiles;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesserInterface;

class MimeTypeGuesser implements MimeTypeGuesserInterface
{
    /**
     * @var MimeTypeGuesserInterface
     */
    private $parent;

    /**
     * @var array
     */
    private $extensions;

    public function __construct(array $extensions, MimeTypeGuesserInterface $parent = null)
    {
        $this->extensions = array_combine(array_map('strtolower', array_keys($extensions)), $extensions);
        $this->parent = $parent ?: \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser::getInstance();
    }


    /**
     * Guesses the mime type of the file with the given path.
     *
     * @param string $path The path to the file
     *
     * @return string The mime type or NULL, if none could be guessed
     *
     * @throws FileNotFoundException If the file does not exist
     * @throws AccessDeniedException If the file could not be read
     */
    public function guess($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (isset($this->extensions[$ext])) {
            return $this->extensions[$ext];
        }

        return $this->parent->guess($path);
    }
}
