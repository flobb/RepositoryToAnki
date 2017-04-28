<?php

namespace R2A\Export;

class Card
{
    /** @var array */
    protected $headers;

    /** @var array */
    protected $tags;

    /** @var string */
    protected $question;

    /** @var string */
    protected $content;

    public function __construct()
    {
        $this->headers = [];
        $this->tags = [];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tag
     *
     * @return $this
     */
    public function addTag($tag)
    {
        // Replace all char by - except for alphanum (Anki use spaces for tag separator)
        $tag = preg_replace('#[^a-z0-9]#i', '-', $tag);

        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getHeader($key)
    {
        return $this->headers[$key] ?: null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $question
     *
     * @return $this
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
