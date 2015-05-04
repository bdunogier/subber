<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Betaseries;

use ZipArchive;

/**
 * Filters ZIP subtitles in a subtitles array.
 *
 * Replaces them with their contents, with parsed metadata if applicable.
 */
class ZipSubtitleFilter
{
    /**
     * @param array $subtitles The episode array as returned by betaseries
     *
     * @return array the modified array
     */
    public function filter(array $subtitles)
    {
        $newSubtitles = [];

        foreach ($subtitles as $subtitle) {
            $extension = pathinfo($subtitle['file'], PATHINFO_EXTENSION);
            if ($extension !== 'zip') {
                $newSubtitles[] = $subtitle;
                continue;
            }

            copy($subtitle['url'], $zipFilename = tempnam(sys_get_temp_dir(), 'zip'));
            $zip = new ZipArchive();
            $zip->open($zipFilename);
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = (string) $zip->getNameIndex($i);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                // @todo makde configurable
                if ($extension !== 'srt' && $extension !== 'ass') {
                    continue;
                }

                $zipUrl = $subtitle['url'];
                $separator = (strstr($zipUrl, '?') !== false) ? '&' : '?';
                $zipUrl .= $separator.'subber_zipfile='.rawurlencode($filename);
                $newSubtitles[] = array_merge(
                    $subtitle,
                    [
                        'url' => $zipUrl,
                        'file' => $filename,
                    ]
                );
            }
            unlink($zipFilename);
        }

        return $newSubtitles;
    }
}
