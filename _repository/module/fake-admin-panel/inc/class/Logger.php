<?php
/**
 * @author         Pierre-Henry Soria <hi@ph7.me>
 * @copyright      (c) 2012-2022, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License <http://www.gnu.org/licenses/gpl.html>
 * @package        PH7 / App / Module / Fake Admin Panel / Inc / Class
 */

declare(strict_types=1);

namespace PH7;

use PH7\Framework\Http\Http;
use PH7\Framework\Ip\Ip;
use PH7\Framework\Mail\Mail;
use PH7\Framework\Mail\Mailable;
use PH7\Framework\Security\Ban\Ban;

class Logger extends Core
{
    /**
     * Folder of the information logs files.
     */
    public const ATTACK_DIR = '_attackers/';

    private array $aData;

    private string $sIp;

    private string $sContents;

    public function init(array $aData): void
    {
        // Add form data in the variable.
        $this->aData = $aData;

        // Creates the log message and adds it to the list of logs.
        $this->setLogMsg()->writeFile();

        if ($this->config->values['module.setting']['report_email.enabled']) {
            $this->sendMessage();
        }

        if ($this->config->values['module.setting']['auto_banned_ip.enabled']) {
            $this->blockIp();
        }

    }

    /**
     * Build the log message.
     */
    protected function setLogMsg(): self
    {
        $sReferer = (null !== ($mReferer = $this->browser->getHttpReferer())) ? $mReferer : 'NO HTTP REFERER';
        $sAgent = (null !== ($mAgent = $this->browser->getUserAgent())) ? $mAgent : 'NO USER AGENT';
        $sQuery = (null !== ($mQuery = (new Http)->getQueryString())) ? $mQuery : 'NO QUERY STRING';

        $this->sIp = Ip::get();

        $this->sContents =
            t('Date: %0%', $this->dateTime->get()->dateTime()) . "\n" .
            t('IP: %0%', $this->sIp) . "\n" .
            t('QUERY: %0%', $sQuery) . "\n" .
            t('Agent: %0%', $sAgent) . "\n" .
            t('Referer: %0%', $sReferer) . "\n" .
            t('LOGIN - Email: %0% - Username: %1% - Password: %2%', $this->aData['mail'], $this->aData['username'], $this->aData['password']) . "\n\n\n";

        return $this;
    }

    /**
     * Send an email to admin.
     */
    private function sendMessage(): bool
    {
        $aInfo = [
            'to' => $this->config->values['logging']['bug_report_email'],
            'subject' => t('Reporting of the Fake Admin Honeypot')
        ];

        return (new Mail)->send(
            $aInfo,
            $this->sContents,
            Mailable::TEXT_FORMAT
        );
    }

    /**
     * Blocking IP address.
     */
    private function blockIp(): self
    {
        $sFullPath = PH7_PATH_APP_CONFIG . Ban::DIR . Ban::IP_FILE;
        file_put_contents($sFullPath, $this->sIp . "\n", FILE_APPEND);

        return $this;
    }

    /**
     * Write a log file with the hacker information.
     */
    private function writeFile(): self
    {
        $sFullPath = $this->registry->path_module_inc . static::ATTACK_DIR . $this->sIp . '.log';
        file_put_contents($sFullPath, $this->sContents, FILE_APPEND);

        return $this;
    }
}
