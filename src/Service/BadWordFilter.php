<?php

namespace App\Service;


// src/Service/BadWordFilter.php

namespace App\Service;

class BadWordFilter
{
   
    public function filterBadWords(string $text): string
{
    // Escape special characters in the bad words for regex
    $badWords = ['ahmed', 'amineghassen', 'badword3'];
    $escapedBadWords = array_map('preg_quote', $badWords);

    // Create a regex pattern to match the bad words as whole words or as part of a word
    $pattern = '/\b(?:' . implode('|', $escapedBadWords) . ')\w*\b/i';

    // Replace matched bad words with '*' repeated based on the length of the bad word
    $filteredText = preg_replace_callback($pattern, function ($matches) {
        return str_repeat('*', mb_strlen($matches[0]));
    }, $text);

    return $filteredText;
}

    

    
}