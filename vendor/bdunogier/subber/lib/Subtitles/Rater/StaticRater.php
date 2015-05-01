<?php
namespace BD\Subber\Subtitles\Rater;

use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\Rater;

/**
 * Rates a subtitle according to static, hardcoded preferences
 */
class StaticRater implements Rater
{
    public function rate( Subtitle $subtitle )
    {
        $rate = 0;

        // language
        if ( $subtitle->getLanguage() == 'en' )
            $rate -= 5;

        // hearing impaired
        if ( $subtitle->isHearingImpaired() )
            $rate -= 3;

        // type
        if ( $subtitle->getSubtitleFormat() == 'ass' )
            $rate += 2;

        // tag/notag
        if ( $subtitle->hasTags() )
            $rate += 1;

        switch ( $subtitle->getSource() )
        {
            case 'soustitres':  $rate += 2; break;
            case 'addic7ed':    $rate += 1; break;
            case 'tvsubtitles': $rate -= 2; break;
        }

        return $rate;
    }
}
