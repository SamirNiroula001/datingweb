<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Admin / Inc / Model
 */

namespace PH7;

use PH7\Framework\Mvc\Model\Engine\Model;
use PH7\Framework\Mvc\Model\Engine\Util\Various;

class ModuleModel extends Model
{
    /**
     * Executes sql queries for the module of the software.
     *
     * @param string $sSqlModuleFile File SQL
     *
     * @return bool|array Returns TRUE if there are no errors, otherwise returns an ARRAY of error information.
     */
    public function run($sSqlModuleFile)
    {
        return Various::execQueryFile($sSqlModuleFile);
    }
}
