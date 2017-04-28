<?php

namespace R2A\Repository;

use Gitonomy\Git\Admin;
use Gitonomy\Git\Repository;
use R2A\Export\Card;
use R2A\Export\Deck;
use R2A\Parser\CardParser;
use Symfony\Component\Finder\Finder;

class RepositoryManager
{
    /** @var string */
    private $storage;

    /** @var string */
    private $url;

    /**
     * RepositoryManager constructor.
     *
     * @param $storage
     * @param $url
     */
    public function __construct($storage, $url)
    {
        $this->storage = $storage;
        $this->url = $url;

        if (!Admin::isValidRepository($this->url)) {
            throw new \LogicException(sprintf(
                'The repository url doesn\'t seem right or reachable, given: %s.',
                $this->url
            ));
        }
    }

    /**
     * @return Repository
     */
    private function getRepository()
    {
        return new Repository($this->storage);
    }

    /**
     * Clone the targeted repository to the local storage
     *
     * @return \Gitonomy\Git\Repository
     */
    public function cloneRepository()
    {
        return Admin::cloneTo($this->storage, $this->url, false);
    }

    /**
     * Update the targeted repository
     *
     * @return string
     */
    public function update()
    {
        return $this->getRepository()->run('pull');
    }

    /**
     * Convert the repository to a CSV file for Anki
     *
     * @param string $exportDirPath
     * @param string $repositoryName
     * @param string $csvDelimiter
     * @param string $csvEnclosure
     * @return int
     */
    public function export($exportDirPath, $repositoryName, $csvDelimiter, $csvEnclosure)
    {
        $finder = new Finder();

        $files = $finder->files()->in($this->storage)->name('*.card');

        $deck = new Deck();

        foreach ($files as $file) {
            $deck->addCard(CardParser::fromfile($file, $this->storage));
        }

        return file_put_contents(
            $exportDirPath.'/'.$repositoryName,
            $this->deckToCsv($deck, $csvDelimiter, $csvEnclosure)
        );
    }

    /**
     * @param Deck $deck
     * @param string $csvDelimiter
     * @param string $csvEnclosure
     * @return string
     */
    public function deckToCsv(Deck $deck, $csvDelimiter, $csvEnclosure)
    {
        $handle = fopen('php://memory', 'r+');

        foreach ($deck->getCards() as $card) {
            fputcsv($handle, $this->cardToArray($card), $csvDelimiter, $csvEnclosure);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }

    /**
     * @param Card $card
     * @return array
     */
    private function cardToArray(Card $card)
    {
        $line = [];

        $line[] = $card->getQuestion();
        $line[] = $card->getContent();
        $line[] = implode(' ', $card->getTags());

        return $line;
    }
}
