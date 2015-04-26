<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

use BD\Subber\Release\Release;

interface Subtitle extends Release
{
    /**
     * @return mixed
     */
    public function getLanguage();

    /**
     * @param mixed $language
     */
    public function setLanguage( $language );

    /**
     * @return string
     */
    public function getAuthor();

    /**
     * @param string $author
     */
    public function setAuthor( $author );

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     */
    public function setUrl( $url );

    /**
     * @return boolean
     */
    public function isHearingImpaired();

    /**
     * @param boolean $isHearingImpaired
     */
    public function setIsHearingImpaired( $isHearingImpaired );

    /**
     * @return string
     */
    public function getSubtitleFormat();

    /**
     * @param string $subtitleFormat
     */
    public function setSubtitleFormat( $subtitleFormat );

    /**
     * @return boolean
     */
    public function hasTags();

    /**
     * @param boolean $hasTags
     */
    public function setHasTags( $hasTags );
}
