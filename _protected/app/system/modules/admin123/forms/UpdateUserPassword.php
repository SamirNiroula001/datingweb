<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Admin / From
 */

declare(strict_types=1);

namespace PH7;

use PFBC\Element\Button;
use PFBC\Element\Hidden;
use PFBC\Element\Password;
use PFBC\Element\Token;
use PH7\Framework\Url\Header;

class UpdateUserPassword
{
    public static function display(string $sUserEmail): void
    {
        if (isset($_POST['submit_update_password'])) {
            if (\PFBC\Form::isValid($_POST['submit_update_password'])) {
                new UpdateUserPasswordFormProcess($sUserEmail);
            }

            Header::redirect();
        }

        $oForm = new \PFBC\Form('form_update_password');
        $oForm->configure(['action' => '']);
        $oForm->addElement(
            new Hidden(
                'submit_update_password',
                'form_update_password'
            )
        );
        $oForm->addElement(
            new Token('update_password')
        );
        $oForm->addElement(
            new Password(
                t('New password:'),
                'new_password',
                [
                    'required' => 1,
                    'validation' => new \PFBC\Validation\Password
                ]
            )
        );
        $oForm->addElement(
            new Password(
                t('Repeat the password:'),
                'new_password2',
                [
                    'required' => 1
                ]
            )
        );
        $oForm->addElement(
            new Button(
                t('Update the user password'),
                'submit',
                [
                    'icon' => 'key'
                ]
            )
        );
        $oForm->render();
    }
}
