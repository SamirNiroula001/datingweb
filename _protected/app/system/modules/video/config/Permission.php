<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Video / Config
 */

namespace PH7;

defined('PH7') or exit('Restricted access');

use PH7\Framework\Layout\Html\Design;
use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Url\Header;

class Permission extends PermissionCore
{
    public function __construct()
    {
        parent::__construct();

        if (!UserCore::auth() && ($this->registry->action === 'addalbum' ||
                $this->registry->action === 'addvideo' ||
                $this->registry->action === 'editalbum' || $this->registry->action === 'editvideo' ||
                $this->registry->action === 'deletevideo' || $this->registry->action === 'deletealbum')
        ) {
            $this->signInRedirect();
        }

        if ($this->isNotAdmin()) {
            if (!$this->checkMembership() || !$this->group->view_videos) {
                $this->paymentRedirect();
            } elseif (($this->registry->action === 'addalbum' || $this->registry->action === 'addvideo') && !$this->group->upload_videos) {
                $this->paymentRedirect();
            }

            if ($this->registry->controller === 'AdminController') {
                // For security reasons, we don't redirect the user to the admin panel URL
                Header::redirect(
                    Uri::get('user', 'main', 'login'),
                    $this->adminSignInMsg(),
                    Design::ERROR_TYPE
                );
            }
        }
    }
}
