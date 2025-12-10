<?php

namespace Lkt\Users\Instances;

use donatj\UserAgent\UserAgentParser;
use Lkt\Users\Enums\PerformedAuthAction;
use Lkt\Users\Generated\GeneratedLktAuthenticationLog;

class LktAuthenticationLog extends GeneratedLktAuthenticationLog
{
    const COMPONENT = 'lkt-authentication-log';


    final public static function logInvalidSignInAttempt(string $attemptedCredential, string $attemptedPassword): static
    {
        $now = new \DateTime();
        $now->sub(\DateInterval::createFromDateString('10 minutes'));

        $dateLimit = $now->format('Y-m-d H:i:s');
        $counterQuery = static::getQueryCaller()->andCreatedAtGreaterOrEqualThan($dateLimit);

        $previousAttempts = static::getMany($counterQuery);

        $parser = new UserAgentParser();
        $ua = $parser->parse();

        return static::getInstance()
            ->setCreatedAt(time())
            ->setPerformedAction(PerformedAuthAction::SignIn->value)
            ->setAttemptedSuccessfully(false)
            ->setClientProtocol($_SERVER['SERVER_PROTOCOL'])
            ->setClientIPAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setClientBrowser($ua->browser())
            ->setClientBrowserVersion($ua->browserVersion())
            ->setClientOS($ua->platform())
            ->setAttemptedCredential($attemptedCredential)
            ->setAttemptedPassword($attemptedPassword)
            ->setAttemptsCounter(count($previousAttempts))
            ->save();
    }

    final public static function logSuccessSignInAttempt(string $attemptedCredential, LktUser|null $user = null): static
    {
        $parser = new UserAgentParser();
        $ua = $parser->parse();

        $r = static::getInstance()
            ->setCreatedAt(time())
            ->setPerformedAction(PerformedAuthAction::SignIn->value)
            ->setAttemptedSuccessfully(true)
            ->setClientProtocol($_SERVER['SERVER_PROTOCOL'])
            ->setClientIPAddress($_SERVER['REMOTE_ADDR'])
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setClientBrowser($ua->browser())
            ->setClientBrowserVersion($ua->browserVersion())
            ->setClientOS($ua->platform())
            ->setAttemptedCredential($attemptedCredential)
            ->setAttemptedPassword('');

        if ($user) $r->setUserId($user->getId());

        return $r->save();
    }


//    /** @see https://stackoverflow.com/questions/18070154/get-operating-system-info */
//    private static function getOS(string $user_agent) {
//
//
//        $os_platform  = "Unknown OS Platform";
//
//        $os_array     = array(
//            '/windows nt 10/i'      =>  'Windows 10',
//            '/windows nt 6.3/i'     =>  'Windows 8.1',
//            '/windows nt 6.2/i'     =>  'Windows 8',
//            '/windows nt 6.1/i'     =>  'Windows 7',
//            '/windows nt 6.0/i'     =>  'Windows Vista',
//            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
//            '/windows nt 5.1/i'     =>  'Windows XP',
//            '/windows xp/i'         =>  'Windows XP',
//            '/windows nt 5.0/i'     =>  'Windows 2000',
//            '/windows me/i'         =>  'Windows ME',
//            '/win98/i'              =>  'Windows 98',
//            '/win95/i'              =>  'Windows 95',
//            '/win16/i'              =>  'Windows 3.11',
//            '/macintosh|mac os x/i' =>  'Mac OS X',
//            '/mac_powerpc/i'        =>  'Mac OS 9',
//            '/linux/i'              =>  'Linux',
//            '/ubuntu/i'             =>  'Ubuntu',
//            '/iphone/i'             =>  'iPhone',
//            '/ipod/i'               =>  'iPod',
//            '/ipad/i'               =>  'iPad',
//            '/android/i'            =>  'Android',
//            '/blackberry/i'         =>  'BlackBerry',
//            '/webos/i'              =>  'Mobile',
//
//        );
//
//        foreach ($os_array as $regex => $value)
//            if (preg_match($regex, $user_agent))
//                $os_platform = $value;
//
//        return $os_platform;
//    }
//
//    private static function getBrowser(string $user_agent) {
//
//        $browser        = "Unknown Browser";
//
//        $browser_array = array(
//            '/msie/i'      => 'Internet Explorer',
//            '/firefox/i'   => 'Firefox',
//            '/safari/i'    => 'Safari',
//            '/chrome/i'    => 'Chrome',
//            '/edge/i'      => 'Edge',
//            '/opera/i'     => 'Opera',
//            '/netscape/i'  => 'Netscape',
//            '/maxthon/i'   => 'Maxthon',
//            '/konqueror/i' => 'Konqueror',
//            '/mobile/i'    => 'Handheld Browser'
//        );
//
//        foreach ($browser_array as $regex => $value)
//            if (preg_match($regex, $user_agent))
//                $browser = $value;
//
//        return $browser;
//    }
}