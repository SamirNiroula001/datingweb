<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Forum / Form / Processing
 */

namespace PH7;

defined('PH7') or exit('Restricted access');

use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Url\Header;

class EditForumFormProcess extends Form
{
    public function __construct()
    {
        parent::__construct();

        $iForumId = $this->httpRequest->get('forum_id', 'int');

        (new ForumModel)->updateForum(
            $iForumId, $this->httpRequest->post('category_id'),
            $this->httpRequest->post('name'),
            $this->httpRequest->post('description'),
            $this->dateTime->get()->dateTime('Y-m-d H:i:s')
        );

        Header::redirect(
            Uri::get('forum', 'forum', 'index'),
            t('Message successfully updated!')
        );
    }
}
