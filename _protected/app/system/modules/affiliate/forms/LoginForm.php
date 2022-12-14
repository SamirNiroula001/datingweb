<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2022, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Affiliate / Form
 */

declare(strict_types=1);

namespace PH7;

use PFBC\Element\Button;
use PFBC\Element\CCaptcha;
use PFBC\Element\Email;
use PFBC\Element\Hidden;
use PFBC\Element\HTMLExternal;
use PFBC\Element\Password;
use PFBC\Element\Token;
use PH7\Framework\Session\Session;
use PH7\Framework\Url\Header;

class LoginForm implements Authenticable
{
    public static function display($iWidth = 500): void
    {
        static::clearCurrentSessions();

        if (isset($_POST['submit_login_aff'])) {
            if (\PFBC\Form::isValid($_POST['submit_login_aff'])) {
                new LoginFormProcess();
            }

            Header::redirect();
        }

        $oForm = new \PFBC\Form('form_login_aff', $iWidth);
        $oForm->configure(['action' => '']);
        $oForm->addElement(new Hidden('submit_login_aff', 'form_login_aff'));
        $oForm->addElement(new Token('login'));
        $oForm->addElement(new Email(t('Your Email:'), 'mail', ['id' => 'email_login', 'onblur' => 'CValid(this.value, this.id,\'user\',\'' . DbTableName::AFFILIATE . '\')', 'required' => 1]));
        $oForm->addElement(new HTMLExternal('<span class="input_error email_login"></span>'));
        $oForm->addElement(new Password(t('Your Password:'), 'password', ['required' => 1]));

        if (static::isCaptchaEligible()) {
            $oForm->addElement(new CCaptcha(t('Captcha'), 'captcha', ['id' => 'ccaptcha', 'onkeyup' => 'CValid(this.value, this.id)', 'description' => t('Enter the below code:')]));
            $oForm->addElement(new HTMLExternal('<span class="input_error ccaptcha"></span>'));
        }

        $oForm->addElement(new Button(t('Login'), 'submit', ['icon' => 'key']));
        $oForm->addElement(new HTMLExternal('<script src="' . PH7_URL_STATIC . PH7_JS . 'validate.js"></script>'));
        $oForm->render();
    }

    /**
     * {@inheritDoc}
     */
    public static function isCaptchaEligible(): bool
    {
        return (new Session)->exists('captcha_aff_enabled');
    }

    /**
     * {@inheritDoc}
     */
    public static function clearCurrentSessions(): void
    {
        if (UserCore::auth() || AdminCore::auth()) {
            (new Session)->destroy();
        }
    }
}
