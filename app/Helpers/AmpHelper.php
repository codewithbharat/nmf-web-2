<?php

namespace App\Helpers; // <-- This namespace MUST be correct

class AmpHelper
{
    /**
     * Finds non-AMP embeds and converts them to AMP-HTML.
     *
     * @param string|null $html The raw HTML content from the database.
     * @return string The converted AMP-compatible HTML.
     */
    public static function convertEmbedsToAmp(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        $convertedHtml = $html;

        // 1. Convert Twitter
        // Finds <blockquote class="twitter-tweet">...<a href=".../status/TWEET_ID">...</a></blockquote>
        $twitterPattern = '/<blockquote class="twitter-tweet".*?<a href=".*?twitter\.com\/.*\/status\/(\d+)"[^>]*>.*?<\/blockquote>/is';
        $twitterReplacement = '<amp-twitter width="375" height="472" layout="responsive" data-tweetid="$1"></amp-twitter>';
        $convertedHtml = preg_replace($twitterPattern, $twitterReplacement, $convertedHtml);

        // 2. Convert Instagram
        // Finds <blockquote class="instagram-media"... data-instgrm-permalink=".../p/SHORTCODE/">...</blockquote>
        $instaPattern = '/<blockquote class="instagram-media".*?data-instgrm-permalink=".*?instagram\.com\/p\/([\w\-]+)\/.*?".*?<\/blockquote>/is';
        $instaReplacement = '<amp-instagram data-shortcode="$1" width="400" height="400" layout="responsive"></amp-instagram>';
        $convertedHtml = preg_replace($instaPattern, $instaReplacement, $convertedHtml);

        // 3. Convert Facebook
        // Finds <div class="fb-post" data-href="URL">...</div>
        $fbPostPattern = '/<div class="fb-post".*?data-href="([^"]+)"[^>]*>.*?<\/div>/is';
        $fbPostReplacement = '<amp-facebook width="552" height="310" layout="responsive" data-href="$1"></amp-facebook>';
        $convertedHtml = preg_replace($fbPostPattern, $fbPostReplacement, $convertedHtml);

        // You can add more conversions here if needed

        return $convertedHtml;
    }
}