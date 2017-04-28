<?php

namespace R2A\Export;

class Deck
{
    /** @var array */
    protected $cards;

    public function __construct()
    {
        $this->cards = [];
    }

    /**
     * @return array
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param Card $card
     *
     * @return $this
     */
    public function addCard(Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }
}
