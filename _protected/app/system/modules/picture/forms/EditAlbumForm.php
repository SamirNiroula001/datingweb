<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Picture / Form
 */

declare(strict_types=1);

namespace PH7;

use PFBC\Element\Button;
use PFBC\Element\Hidden;
use PFBC\Element\Textarea;
use PFBC\Element\Textbox;
use PFBC\Element\Token;
use PFBC\Validation\RegExp;
use PFBC\Validation\Str;
use PH7\Framework\Config\Config;
use PH7\Framework\Mvc\Request\Http;
use PH7\Framework\Session\Session;
use PH7\Framework\Url\Header;

class EditAlbumForm
{
    public static function display(): void
    {
        if (isset($_POST['submit_edit_picture_album'])) {
            if (\PFBC\Form::isValid($_POST['submit_edit_picture_album'])) {
                new EditAlbumFormProcess();
            }

            Header::redirect();
        }

        $oAlbum = (new PictureModel)->album(
            (new Session)->get('member_id'),
            (new Http)->get('album_id'),
            '1',
            0,
            1
        );

        $sTitlePattern = Config::getInstance()->values['module.setting']['url_title.pattern'];

        $oForm = new \PFBC\Form('form_edit_picture_album');
        $oForm->configure(['action' => '']);
        $oForm->addElement(new Hidden('submit_edit_picture_album', 'form_edit_picture_album'));
        $oForm->addElement(new Token('edit_album'));
        $oForm->addElement(
            new Textbox(
                t('Album Cover Name:'),
                'name',
                [
                    'value' => $oAlbum->name,
                    'required' => 1,
                    'pattern' => $sTitlePattern,
                    'validation' => new RegExp($sTitlePattern)
                ]
            )
        );
        $oForm->addElement(
            new Textarea(
                t('Album Cover Description:'),
                'description',
                [
                    'value' => $oAlbum->description,
                    'validation' => new Str(Form::MIN_STRING_FIELD_LENGTH, Form::MAX_STRING_FIELD_LENGTH)
                ]
            )
        );
        $oForm->addElement(new Button);
        $oForm->render();
    }
}
