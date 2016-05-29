<?php

namespace monitor\library\callback;

class CallbackAgenda implements CallbackInterface
{
    public function execute()
    {
        // get all the passed arguments
        $args = func_get_args();

        // parse the sent data
        $body = $this->parse($args[0]);

        // assume body is ok
        $json = json_decode($body, false);

        // create dates for comparison from the first encountered
        $dateDoc = new \DateTime($json->debates[0]->startsAt);
        $dateNow = new \DateTime();

        // assume non updated file
        if ($dateDoc->format('Y-m-d') !== $dateNow->format('Y-m-d')) {
            return false;
        }

        if (!empty($json->debates)) {
            foreach ($json->debates as $debate) {

                $date = new \DateTime($debate->startsAt);
                $day = strtolower($date->format('l'));

                switch ($day) {
                    case 'monday':
                    case 'tuesday':
                    case 'wednesday':
                    case 'thursday':

                        break;
                    default:

                        break;
                }
            }
        }
    }

    /**
     * @param $str
     * @return mixed
     */
    public function parse($str)
    {
        // body is separated with \r\n\r\n according to RFC
        $parts = explode("\r\n\r\n", $str);

        return $parts[1];
    }
}