<?php

namespace IdfEditor\File;

class EmnFile
{
    /** @var string */
    private $url;

    /** @var string */
    private $title;

    /** @var string[] */
    private $boardInfo = [];

    /** @var array[] */
    private $components = [];

    /**
     * EmnFile constructor.
     * @param string $url
     * @param string $title
     */
    public function __construct($url, $title = null)
    {
        if (!is_file($url)) {
            throw new \InvalidArgumentException(sprintf('%s is not a file.', $url));
        }

        if (!is_readable($url)) {
            throw new \InvalidArgumentException(sprintf('%s is not readable.', $url));
        }

        $this->title = $title;
        $this->url = $url;

        $isComponent = false;

        $lines = file($url, FILE_IGNORE_NEW_LINES);

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            if (trim($line) == '.PLACEMENT') {
                $isComponent = true;
                continue;
            }

            if (trim($line) == '.END_PLACEMENT') {
                $isComponent = false;
                continue;
            }

            if ($isComponent) {
                $line2 = $lines[++$i];

                $this->components[] = [
                    'line1' => $line,
                    'line2' => $line2,
                    'csv' => explode(',', preg_replace('/\s+/', ',', "$line $line2")),
                ];
            } else {
                $this->boardInfo[] = $line;
            }
        }
    }

    /**
     * @return string[]
     */
    public function getBoardInfo()
    {
        return $this->boardInfo;
    }

    /**
     * @return array[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
