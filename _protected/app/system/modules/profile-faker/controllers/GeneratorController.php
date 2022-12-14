<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Profile Faker / Controller
 */

namespace PH7;

class GeneratorController extends Controller
{
    public function addMember()
    {
        $this->setTitle(t('Generate Members (without profile photo)'));
        $this->output();
    }

    public function addAffiliate()
    {
        $this->setTitle(t('Generate Fake Affiliates'));
        $this->output();
    }

    public function addSubscriber()
    {
        $this->setTitle(t('Generate Fake Subscribers'));
        $this->output();
    }

    /**
     * Set title and heading.
     *
     * @param string $sTitle
     *
     * @return void
     */
    private function setTitle($sTitle)
    {
        $this->view->page_title = $this->view->h1_title = $sTitle;
    }
}
