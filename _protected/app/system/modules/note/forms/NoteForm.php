<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Note / Form
 */

declare(strict_types=1);

namespace PH7;

use PFBC\Element\Button;
use PFBC\Element\CCaptcha;
use PFBC\Element\Checkbox;
use PFBC\Element\File;
use PFBC\Element\Hidden;
use PFBC\Element\HTMLExternal;
use PFBC\Element\Radio;
use PFBC\Element\Textarea;
use PFBC\Element\Textbox;
use PFBC\Element\Token;
use PFBC\Validation\Str;
use PH7\Framework\Mvc\Model\DbConfig;
use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Session\Session;
use PH7\Framework\Url\Header;

class NoteForm
{
    private const MAX_CATEGORIES = 300;

    public static function display(): void
    {
        if (isset($_POST['submit_note'])) {
            if (\PFBC\Form::isValid($_POST['submit_note'])) {
                new NoteFormProcess();
            }

            Header::redirect();
        }

        $oCategoryData = (new NoteModel)->getCategory(
            null,
            0,
            self::MAX_CATEGORIES
        );

        $aCategoryNames = [];
        foreach ($oCategoryData as $oCategory) {
            $aCategoryNames[$oCategory->categoryId] = $oCategory->name;
        }

        $oForm = new \PFBC\Form('form_note');
        $oForm->configure(['action' => '']);
        $oForm->addElement(new Hidden('submit_note', 'form_note'));
        $oForm->addElement(new Token('note'));
        $oForm->addElement(
            new Textbox(
                t('Article name:'),
                'title',
                [
                    'validation' => new Str(2, 60),
                    'required' => 1
                ]
            )
        );
        $oForm->addElement(
            new Textbox(
                t('Article ID:'),
                'post_id',
                [
                    'description' => Uri::get('note', 'main', 'read', (new Session)->get('member_username')) . '/<strong><span class="your-address">' . t('your-address') . '</span><span class="post_id"></span></strong>',
                    'title' => t('Article ID will be the name of the URL.'),
                    'data-profile_id' => (new Session)->get('member_id'),
                    'id' => 'post_id',
                    'validation' => new Str(2, 50),
                    'required' => 1
                ]
            )
        );
        $oForm->addElement(new HTMLExternal('<div class="label_flow">'));
        $oForm->addElement(
            new Checkbox(
                t('Categories:'),
                'category_id',
                $aCategoryNames,
                [
                    'description' => t(
                        'Select a category that fits the best for your article. You can select up to three different categories'
                    ),
                    'required' => 1
                ]
            )
        );
        $oForm->addElement(new HTMLExternal('</div>'));
        $oForm->addElement(
            new Textarea(t('Body:'), 'content', ['validation' => new Str(30), 'rows' => 8, 'required' => 1])
        );
        $oForm->addElement(
            new Textbox(
                t('Language of the post:'),
                'lang_id',
                [
                    'description' => t('e.g., "en", "fr", "es", "js"'),
                    'value' => PH7_LANG_CODE,
                    'pattern' => '[a-z]{2}',
                    'validation' => new Str(2, 2),
                    'required' => 1
                ]
            )
        );
        $oForm->addElement(
            new Textbox(
                t('Slogan:'),
                'slogan',
                ['validation' => new Str(Form::MIN_STRING_FIELD_LENGTH, Form::MAX_STRING_FIELD_LENGTH)]
            )
        );
        $oForm->addElement(new File(t('Thumbnail:'), 'thumb', ['accept' => 'image/*']));
        $oForm->addElement(
            new Textbox(
                t('Tags:'),
                'tags',
                [
                    'description' => t('Separate keywords by commas and without spaces between the commas.'),
                    'validation' => new Str(Form::MIN_STRING_FIELD_LENGTH, Form::MAX_STRING_FIELD_LENGTH)
                ]
            )
        );
        $oForm->addElement(
            new Textbox(t('Title (meta tag):'), 'page_title', ['validation' => new Str(2, 100), 'required' => 1])
        );
        $oForm->addElement(
            new Textbox(
                t('Description (meta tag):'),
                'meta_description',
                ['validation' => new Str(Form::MIN_STRING_FIELD_LENGTH, Form::MAX_STRING_FIELD_LENGTH)]
            )
        );
        $oForm->addElement(
            new Textbox(
                t('Keywords (meta tag):'),
                'meta_keywords',
                [
                    'description' => t('Separate keywords by commas and without spaces between the commas.'),
                    'validation' => new Str(Form::MIN_STRING_FIELD_LENGTH, Form::MAX_STRING_FIELD_LENGTH)
                ]
            )
        );
        $oForm->addElement(new Textbox(t('Robots (meta tag):'), 'meta_robots', ['validation' => new Str(2, 50)]));
        $oForm->addElement(new Textbox(t('Author (meta tag):'), 'meta_author', ['validation' => new Str(2, 50)]));
        $oForm->addElement(new Textbox(t('Copyright (meta tag):'), 'meta_copyright', ['validation' => new Str(2, 50)]));
        $oForm->addElement(
            new Radio(
                t('Enable Comment:'),
                'enable_comment',
                ['1' => t('Enable'), '0' => t('Disable')],
                ['value' => '1', 'required' => 1]
            )
        );

        if (DbConfig::getSetting('isCaptchaNote')) {
            $oForm->addElement(
                new CCaptcha(
                    t('Captcha'),
                    'captcha',
                    [
                        'id' => 'ccaptcha',
                        'onkeyup' => 'CValid(this.value, this.id)',
                        'description' => t('Enter the below code:')
                    ]
                )
            );
            $oForm->addElement(new HTMLExternal('<span class="input_error ccaptcha"></span>'));
        }

        $oForm->addElement(new Button);
        $oForm->addElement(
            new HTMLExternal(
                '<script src="' . PH7_URL_STATIC . PH7_JS . 'validate.js"></script><script src="' . PH7_URL_TPL_SYS_MOD . 'note/' . PH7_TPL . PH7_TPL_MOD_NAME . PH7_SH . PH7_JS . 'common.js"></script>'
            )
        );
        $oForm->render();
    }
}
