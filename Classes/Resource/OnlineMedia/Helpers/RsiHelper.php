<?php
namespace Saccas\Srgssr\Resource\OnlineMedia\Helpers;

/***
 *
 * This file is part of the "Srgssr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 https://www.sac-cas.ch
 *
 ***/

class RsiHelper extends AbstractSrgssrHelper
{
    /** @var string */
    protected $channelName = 'rsi';

    /** @var string  */
    protected $siteHostname = 'www.rsi.ch';

    /** @var string */
    protected $extension = 'rsi';
}
