<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Blog / Form
 */

namespace PH7;

use PFBC\Element\Button;
use PFBC\Element\Search;
use PFBC\Element\Select;
use PH7\Framework\Mvc\Router\Uri;

class SearchBlogForm
{
    public static function display($iWidth = null)
    {
        $aOptions = !empty($iWidth) ? ['style' => 'width:' . ((int)$iWidth * 1.09) . 'px'] : null;

        $oForm = new \PFBC\Form('form_search', $iWidth);
        $oForm->configure(['action' => Uri::get('blog', 'main', 'result') . PH7_SH, 'method' => 'get']);
        $oForm->addElement(new Search(t('Name, Keyword of posts, or ID of a blog post:'), 'looking', $aOptions));
        $oForm->addElement(new Select(t('Browse By:'), 'order', [SearchCoreModel::TITLE => t('Title'), SearchCoreModel::VIEWS => t('Popular'), SearchCoreModel::RATING => t('Rated'), SearchCoreModel::CREATED => t('Created Date'), SearchCoreModel::UPDATED => t('Updated Date')]));
        $oForm->addElement(new Select(t('Direction:'), 'sort', [SearchCoreModel::ASC => t('Ascending'), SearchCoreModel::DESC => t('Descending')], ['value' => SearchCoreModel::DESC]));
        $oForm->addElement(new Button(t('Search'), 'submit', ['icon' => 'search']));
        $oForm->render();
    }
}
