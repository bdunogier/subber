<?php
namespace BD\Subber\Subtitles;

/**
 * Rates a subtitle according to preferences
 */
class SimpleSubtitleRater implements SubtitleRater
{
    public function rate( Subtitle $subtitle )
    {
        $rate = 0;

        // language
        if ( $subtitle->language == 'en' )
            $rate -= 5;

        // hearing impaired
        if ( $subtitle->isHearingImpaired )
            $rate -= 3;

        // type
        if ( $subtitle->subtitleFormat == 'ass' )
            $rate += 2;

        // tag/notag
        if ( $subtitle->hasTags )
            $rate += 1;

        switch ( $subtitle->source )
        {
            case 'soustitres':  $rate += 2; break;
            case 'addic7ed':    $rate += 1; break;
            case 'tvsubtitles': $rate -= 2; break;
        }

        return $rate;
    }
}
