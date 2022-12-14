<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Video / Form / Processing
 */

namespace PH7;

defined('PH7') or exit('Restricted access');

use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Url\Header;

class EditAlbumFormProcess extends Form
{
    public function __construct()
    {
        parent::__construct();

        $iAlbumId = (int)$this->httpRequest->get('album_id');

        (new VideoModel)->updateAlbum(
            $this->session->get('member_id'),
            $iAlbumId,
            MediaCore::cleanTitle($this->httpRequest->post('name')),
            $this->httpRequest->post('description'),
            $this->dateTime->get()->dateTime('Y-m-d H:i:s')
        );

        Video::clearCache();

        Header::redirect(
            Uri::get(
                'video',
                'main',
                'albums',
                $this->session->get('member_username'),
                $iAlbumId
            ),
            t('Your album has been successfully updated!')
        );
    }
}
