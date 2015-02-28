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

        $subtitleFilename = strtolower( $subtitle->filename );
        $subtitleLanguage = strtolower( $subtitle->language );
        $subtitleExtension = pathinfo( $subtitleFilename, PATHINFO_EXTENSION );

        // language
        if ( $subtitleLanguage == 'vo' )
            $rate -= -5;

        // language
        if ( $subtitleLanguage == 'vo' )
            $rate -= -5;

        // hearing impaired
        if ( strstr( $subtitleFilename, '.hi.' ) )
            $rate -= 3;

        // type
        if ( $subtitleExtension == 'ass' )
            $rate += 2;

        // tag/notag
        if ( $this->contains( $subtitleFilename, '.tag' ) )
            $rate += 2;

        switch ( $subtitle->source )
        {
            case 'soustitres':  $rate += 2; break;
            case 'addic7ed':    $rate += 1; break;
            case 'tvsubtitles': $rate -= 2; break;
        }

        return $rate;
    }

    private function contains( $string, $substring )
    {
        return strstr( $string, $substring ) !== false;
    }
}
