<?php

namespace R2A\Parser;

use R2A\Export\Card;

class CardParser
{
    /**
     * @param Card $card
     * @param string $string
     *
     * @return array
     */
    protected static function parseHeaders($card, $string)
    {
        $matches = [];
        if (!preg_match_all('#[\s]*(.*):(.*)[\s]*#mu', $string, $matches, PREG_SET_ORDER)) {
            throw new \LogicException(sprintf('Malformed headers card: %s', $string));
        }

        $headers = [];
        foreach ($matches as $match) {
            $match = array_map('trim', $match);
            if ($match[1] === 'tags') {
                $tags = array_map('trim', explode(',', $match[2]));
                foreach ($tags as $tag) {
                    $card->addTag($tag);
                }
            }
            else {
                $card->setHeader($match[1], $match[2]);
            }
        }

        return $headers;
    }

    /**
     * Read a file and return a card with the infos
     *
     * @param \SplFileInfo $file
     * @return Card
     */
    public static function fromfile(\SplFileInfo $file, $deckPath)
    {
        $fileContent = explode('-----', file_get_contents($file->getPathname()));

        if (count($fileContent) !== 3) {
            throw new \LogicException(sprintf('Malformed structure card: %s', $file->getPathname()));
        }

        $fileContent = array_map('trim', $fileContent);

        $card = new Card();

        if (!empty($fileContent[0])) {
            self::parseHeaders($card, $fileContent[0]);
        }

        // Add the folder names to the tag list
        $folders = explode(DIRECTORY_SEPARATOR, mb_substr($file->getPathInfo()->getRealPath(), mb_strlen($deckPath) + 1));
        foreach ($folders as $tag) {
            $card->addTag($tag);
        }

        $card->setQuestion($fileContent[1]);
        $card->setContent($fileContent[2]);

        return $card;
    }
}
