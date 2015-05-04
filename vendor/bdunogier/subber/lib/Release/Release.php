<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Release;

interface Release
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return bool
     */
    public function isRepack();

    /**
     * @param bool $isRepack
     */
    public function setIsRepack($isRepack);

    /**
     * @return bool
     */
    public function isProper();

    /**
     * @param bool $isProper
     */
    public function setIsProper($isProper);

    /**
     * @return string
     */
    public function getGroup();

    /**
     * @return string
     */
    public function getSource();

    /**
     * @param string $source
     */
    public function setSource($source);

    /**
     * @return string
     */
    public function getResolution();

    /**
     * @param string $resolution
     */
    public function setResolution($resolution);

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @param string $format
     */
    public function setFormat($format);

    /**
     * @param string $group
     */
    public function setGroup($group);

    public function toArray();
}
