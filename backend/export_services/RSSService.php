<?php

class RssService
{
    public function generateTopDrinks(array $drinks, string $baseUrl): string
    {
        $rss = '<?xml version="1.0" encoding="UTF-8"?>';
        $rss .= '<rss version="2.0">';
        $rss .= '<channel>';

        $rss .= '<title>Soft Drinks - Top Drinks</title>';
        $rss .= '<description>Ranking of the most popular drinks</description>';
        $rss .= '<link>' . $baseUrl . '</link>';

        foreach ($drinks as $drink) {
            $rss .= '<item>';
            $rss .= '<title>' . htmlspecialchars($drink["name"], ENT_XML1, "UTF-8") . '</title>';
            $rss .= '<description>Popularity: ' . htmlspecialchars((string)$drink["popularity"], ENT_XML1, "UTF-8") . '</description>';
            $rss .= '<link>' . $baseUrl . '/pages/drink-details.html?id=' . htmlspecialchars((string)$drink["id"], ENT_XML1, "UTF-8") . '</link>';
            $rss .= '<guid>drink-' . htmlspecialchars((string)$drink["id"], ENT_XML1, "UTF-8") . '</guid>';
            $rss .= '</item>';
        }

        $rss .= '</channel>';
        $rss .= '</rss>';

        return $rss;
    }
}