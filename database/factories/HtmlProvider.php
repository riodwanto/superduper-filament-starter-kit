<?php

namespace Database\Factories;

use Faker\Provider\Base as BaseProvider;

class HtmlProvider extends BaseProvider
{
    public function randomHtml($tags = ['p', 'a', 'ul', 'ol', 'li', 'div'])
    {
        $html = '';
        $numTags = random_int(3, 7); // Number of tags to generate

        for ($i = 0; $i < $numTags; $i++) {
            $tag = $tags[array_rand($tags)];
            $content = $this->generator->words(random_int(3, 10), true);
            $html .= "<$tag>$content</$tag>";
        }

        return $html;
    }
}
