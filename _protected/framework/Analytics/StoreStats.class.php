<?php
/**
 * @title            Store Stats Class
 *
 * @author           Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright        (c) 2013-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license          MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package          PH7 / Framework / Analytics
 */

namespace PH7\Framework\Analytics;

defined('PH7') or exit('Restricted access');

use PH7\Framework\Cache\IOException;

class StoreStats
{
    const DIR = 'stats/';
    const EXT = '.txt';

    /**
     * Read cache.
     *
     * @param string $sFileName
     *
     * @return array Cache data.
     *
     * @throws IOException If the file cannot be gotten.
     */
    protected function read($sFileName)
    {
        $sFullPath = PH7_PATH_TMP . static::DIR . $sFileName . static::EXT;

        if (!$aGetData = @file_get_contents($sFullPath)) {
            throw new IOException('Cannot get cache file: ' . $sFullPath);
        }

        $aData = !empty($aGetData) ? unserialize($aGetData) : [];

        return $aData;
    }

    /**
     * Save cache.
     *
     * @param string $sFileName
     * @param string $sContents
     *
     * @return void
     *
     * @throws IOException If the file cannot be written.
     */
    protected function save($sFileName, $sContents)
    {
        $sFullPath = PH7_PATH_TMP . static::DIR . $sFileName . static::EXT;
        $aData = [];
        $iFlag = FILE_TEXT;

        if (is_file($sFullPath)) {
            $aLine = file($sFullPath);
            $aData = unserialize($aLine[0]);
            $sContents = strtolower($sContents); // Case-insensitive

            !empty($aData[$sContents]) ? $aData[$sContents]++ : $aData[$sContents] = 1;
            $iFlag = FILE_APPEND;
        }

        if (!@file_put_contents($sFullPath, serialize($aData), $iFlag)) {
            throw new IOException('Cannot write cache file: ' . $sFullPath);
        }
    }
}
