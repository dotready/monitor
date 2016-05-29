<?php

namespace monitor\service;

use mail\service\MailService;
use monitor\exception\MonitorException;
use monitor\exception\MonitorUrlException;
use monitor\library\callback\CallbackInterface;
use monitor\library\monitorurl\MonitorUrl;
use monitor\library\monitorurl\MonitorUrlInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class MonitorService
{
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var string
     */
    private $panicEmailAddress;

    /**
     * @var string
     */
    private $sessionName = 'session_panic_attack';

    /**
     * MonitorService constructor.
     * @param MailService $mailService
     * @param $panicEmailAddress
     * @param Session $session
     */
    public function __construct(MailService $mailService, $panicEmailAddress, Session $session)
    {
        $this->mailService = $mailService;
        $this->panicEmailAddress = $panicEmailAddress;
        $this->session = $session;
    }

    /**
     * @param $config
     * @return MonitorUrl
     * @throws MonitorUrlException
     */
    public function createMonitorUrl($config)
    {
        $monitorUrl = new MonitorUrl();
        $monitorUrl->setHost($config->host);
        $monitorUrl->setSsl($config->ssl);
        $monitorUrl->setPath($config->path);

        return $monitorUrl;
    }

    /**
     * @param MonitorUrlInterface $monitorUrl
     * @param CallbackInterface $callback
     */
    public function monitor(MonitorUrlInterface $monitorUrl, CallbackInterface $callback)
    {
        try {

            $data = $monitorUrl->getStatus();
            $callback->execute($data);

            // set status to defcon 5
            if ((int) $this->session->get($this->sessionName) === 1) {
                $this->session->set($this->sessionName, 0);
                $this->mailService->sendMail($this->panicEmailAddress, 'Monit is ok', 'System back to normal');
            }

        } catch (MonitorException $me) {
            $this->sendMail($this->panicEmailAddress, 'error', 'monit detected an error');
        } catch (MonitorUrlException $mu) {
            $this->sendMail($this->panicEmailAddress, '404', 'monit detected a 404');
        } catch (\Exception $e) {
            $this->sendMail($this->panicEmailAddress, 'General failure', 'monit detected a general failure');
        }
    }

    /**
     * @param $recipient
     * @param $subject
     * @param $message
     */
    public function sendMail($recipient, $subject, $message)
    {
        if ((int) $this->session->get($this->sessionName) === 1) {
            return;
        }

        $this->mailService->sendMail($recipient, $subject, $message);
        $this->session->set($this->sessionName, 1);
    }
}
