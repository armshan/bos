<?php
/*
* Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may not
* use this file except in compliance with the License. You may obtain a copy of
* the License at
*
* Http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
* WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
* License for the specific language governing permissions and limitations under
* the License.
*/

namespace BaiduBce\Services\Cdn;

use BaiduBce\Auth\BceV1Signer;
use BaiduBce\BceBaseClient;
use BaiduBce\Exception\BceClientException;
use BaiduBce\Http\BceHttpClient;
use BaiduBce\Http\HttpContentTypes;
use BaiduBce\Http\HttpHeaders;
use BaiduBce\Http\HttpMethod;

class CdnClient extends BceBaseClient
{
    /**
     * @var \BaiduBce\Auth\SignerInterface
     */
    private $signer;
    private $httpClient;
    private $prefix = '/v2';

    /**
     * The CdnClient constructor
     *
     * @param array $config The client configuration
     */
    function __construct(array $config)
    {
        parent::__construct($config, 'CdnClient');
        $this->signer = new BceV1Signer();
        $this->httpClient = new BceHttpClient();
    }

    /**
     * List all domains of current user.
     *
     * @param array $options None
     * @return domain list the server response.
     * @throws BceClientException
     */
    public function listDomains($options = array())
    {
        list($config) = $this->parseOptions($options, 'config');
        $params = array();

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/domain'
        );
    }

    /**
     * create domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param array $origin [<the origin address list>]
     * @return response
     * @throws BceClientException
     */
    public function createDomain($domain, $origin, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        if (empty($origin)) {
            throw new BceClientException("The parameter origin should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = $options;

        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode(array('origin' => $origin)),
            ),
            '/domain/'.$domain
        );
    }

    /**
     * delete a domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @return response
     * @throws BceClientException
     */
    public function deleteDomain($domain, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = $options;

        return $this->sendRequest(
            HttpMethod::DELETE,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/domain/'.$domain
        );
    }

    /**
     * enable a domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @return response
     * @throws BceClientException
     */
    public function enableDomain($domain, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array('enable' => '');

        return $this->sendRequest(
            HttpMethod::POST,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/domain/'.$domain
        );
    }

    /**
     * disable a domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @return response
     * @throws BceClientException
     */
    public function disableDomain($domain, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array('disable' => '');

        return $this->sendRequest(
            HttpMethod::POST,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/domain/'.$domain
        );
    }

    /**
     * update origin address of the domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param array $origin [<the origin address list>]
     * @return response
     * @throws BceClientException
     */
    public function setDomainOrigin($domain, $origin, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        if (empty($origin)) {
            throw new BceClientException("The parameter origin should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array('origin' => '');

        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode(array('origin' => $origin)),
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * get configuration of the domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainConfig($domain, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = $options;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * get cache rules of a domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainCacheTTL($domain, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array('cacheTTL' => '');

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * set cache rules of a domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param array $rules [<cache ruless>]
     * @return response
     * @throws BceClientException
     */
    public function setDomainCacheTTL($domain, $rules, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }
        if (empty($rules) || !is_array($rules)) {
            throw new BceClientException("The parameter rules should be a non empty array");
        }
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }

        $params = array('cacheTTL' => '');

        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode(array('cacheTTL' => $rules)),
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * set if use the full url as cache key
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param bool $flag [<if use the full url as cache key>]
     * @return response
     * @throws BceClientException
     */
    public function setDomainCacheFullUrl($domain, $flag, $options = array())
    {
        if (empty($domain) || empty($flag)) {
            throw new BceClientException("The parameter domain or flag should NOT be null");
        }
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }

        $params = array('cacheFullUrl' => '');

        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode(array('cacheFullUrl' => $flag)),
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * set request ip access control
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param array $blackList [<ip black list>]
     * @param array $whiteList [<ip white list>]
     * @return response
     * @throws BceClientException
     */
    public function setDomainIpAcl($domain, $flag, $aclList, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }
        if (empty($flag) || (($flag != 'black') && ($flag != 'white'))) {
            throw new BceClientException("The parameter flag should be black or white");
            
        }
        if (empty($aclList)) {
            throw new BceClientException("Acl list is empty, please check your input");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array('ipACL' => '');

        $acl = array();
        if ($flag == 'white') {
            $acl['whiteList'] = $aclList;
        }
        if ($flag == 'black') {
            $acl['blackList'] = $aclList;
        }

        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode(array('ipACL' => $acl)),
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * set request referer access control
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param array $blackList [<referer black list>]
     * @param array $whiteList [<referer white list>]
     * @return response
     * @throws BceClientException
     */
    public function setDomainRefererAcl($domain, $flag, $allowEmpty,
                                $aclList, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }
        if (empty($flag) || (($flag != 'black') && ($flag != 'white'))) {
            throw new BceClientException("The parameter flag should be black or white");
            
        }
        if (empty($aclList)) {
            throw new BceClientException("Acl list is empty, please check your input");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array('refererACL' => '');

        $acl = array();
        $acl['allowEmpty'] = $allowEmpty;
        if ($flag == 'white') {
            $acl['whiteList'] = $aclList;
        }
        if ($flag == 'black') {
            $acl['blackList'] = $aclList;
        }

        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode(array('refererACL' => $acl)),
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * set limit rate
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param int $rate [<limit rate value (Byte/s)>]
     * @return response
     * @throws BceClientException
     */
    public function setDomainLimitRate($domain, $rate, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }
        if (empty($rate) || !is_int($rate)) {
            throw new BceClientException("The parameter rate should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array('limitRate' => '');

        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode(array('limitRate' => $rate)),
            ),
            '/domain/'.$domain.'/config'
        );
    }

    /**
     * query pv and qps of the domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @param int $withRegion [<if need client region distribution>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainPvStat($domain=null, $startTime=null, $endTime=null,
                                $period=300, $withRegion=null, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;
        $params['withRegion'] = $withRegion;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/pv'
        );
    }

    /**
     * query the total number of client of a domain or all domains of the user
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainUvStat($domain=null, $startTime=null, $endTime=null,
                                $period=3600, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/uv'
        );
    }

    /**
     * query average of the domain or all domains of the user
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainAvgSpeedStat($domain=null, $startTime=null, $endTime=null,
                                $period=300, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/avgspeed'
        );
    }

    /**
     * query bandwidth of the domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @param int $withRegion [<if need client region distribution>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainFlowStat($domain=null, $startTime=null, $endTime=null,
                                $period=300, $withRegion=null, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;
        $params['withRegion'] = $withRegion;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/flow'
        );
    }

    /**
     * @param $options array
     * @return response
     */
    public function getDomainSrcFlowStat($domain=null, $startTime=null, $endTime=null,
                                $period=300, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/srcflow'
        );
    }

    /**
     * query hit rate of the domain
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainHitRateStat($domain=null, $startTime=null, $endTime=null,
                                $period=300, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/hitrate'
        );
    }

    /**
     * query http response code of a domain or all domains of the user
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @param int $withRegion [<if need client region distribution>]
     * @return response
     * @throws BceClientException
     */    
    public function getDomainHttpCodeStat($domain=null, $startTime=null, $endTime=null,
                                $period=300, $withRegion=null, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;
        $params['withRegion'] = $withRegion;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/httpcode'
        );
    }

    /**
     * query top n url of the domain or all domains of the user
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainTopUrlStat($domain=null, $startTime=null, $endTime=null,
                                $period=3600, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/topn/url'
        );
    }

    /**
     * query top n referer of the domain or all domains of the user
     * @param array $options None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $period [<time interval of query result>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainTopRefererStat($domain=null, $startTime=null, $endTime=null,
                                $period=3600, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['domain'] = $domain;
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        $params['period'] = $period;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/stat/topn/referer'
        );
    }

    /**
     * purge the cache of specified url or directory
     * @param array $tasks The task list
     *      {
     *          url: The url to be purged.
     *          type: 'file' or 'directory'
     *      }
     * @param array $options None
     * @return task id
     * @throws BceClientException
     */
    public function purge(array $tasks, $options = array())
    {
        if (empty($tasks)) {
            throw new BceClientException("The parameter tasks " 
                ."should NOT be null or empty array");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');
        if (!empty($config)) {
            unset($options['config']);
        }
        $params = $options;

        return $this->sendRequest(
            HttpMethod::POST,
            array(
                'config' => $config,
                'body' => json_encode(array(
                    'tasks' => $tasks
                )),
                'params' => $params,
            ),
            '/cache/purge'
        );
    }

    /**
     * Get status of specified purge task.
     *
     * @param array $options None
     * @param string $taskId [<purge task id to query>]
     * @param string $url [<purge url to query>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $marker [<'nextMarker' get from last query>]
     * @throws BceClientException
     * @return response
     */
    public function listPurgeStatus($taskId = null, $url = null, $startTime = null,
                                $endTime = null, $marker = null, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();

        if (!empty($taskId)) {
            $params['id'] = $taskId;
        }
        if (!empty($url)) {
            $params['url'] = $url;
        }
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        if (!empty($marker)) {
            $params['marker'] = $marker;
        }

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/cache/purge'
        );
    }

    /**
     * prefetch the source of specified url from origin
     * @param array $options None
     * @param array $tasks The task list
     *      {
     *          url: The url to be prefetch.
     *          speed: The flowrate limit of this prefetch task.
     *          startTime: Schedule the prefetch task to specified time.
     *      }
     * @return task id
     * @throws BceClientException
     */
    public function prefetch(array $tasks, $options = array())
    {
        if (empty($tasks)) {
            throw new BceClientException("The parameter tasks " 
                ."should NOT be null or empty array");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');
        if (!empty($config)) {
            unset($options['config']);
        }
        $params = $options;

        return $this->sendRequest(
            HttpMethod::POST,
            array(
                'config' => $config,
                'body' => json_encode(array(
                    'tasks' => $tasks
                )),
                'params' => $params,
            ),
            '/cache/prefetch'
        );
    }

    /**
     * query the status of prefetch tasks
     * @param array $options None
     * @param string $taskId [<purge task id to query>]
     * @param string $url [<purge url to query>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @param int $marker [<'nextMarker' get from last query>]
     * @throws BceClientException
     * @return response
     */
    public function listPrefetchStatus($taskId = null, $url = null, $startTime = null,
                                $endTime = null, $marker = null, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();

        if (!empty($taskId)) {
            $params['id'] = $taskId;
        }
        if (!empty($url)) {
            $params['url'] = $url;
        }
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }
        if (!empty($marker)) {
            $params['marker'] = $marker;
        }

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/cache/prefetch'
        );
    }

    /**
     * query purge quota of the user
     * @param $options array None
     * @throws BceClientException
     * @return response
     */
    public function listQuota($options = array())
    {
        list($config) = $this->parseOptions($options, 'config');
        $params = array();

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/cache/quota'
        );
    }

    /**
     * get log of the domain in specified period of time
     * @param $options array None
     * @param string $domain [<the domain name>]
     * @param timestamp $startTime [<query start time>]
     * @param timestamp $endTime [<query end time>]
     * @return response
     * @throws BceClientException
     */
    public function getDomainLog($domain, $startTime = null, $endTime = null, $options = array())
    {
        if (empty($domain)) {
            throw new BceClientException("The parameter domain should NOT be null");
        }

        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        if (!empty($startTime)) {
            $params['startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $params['endTime'] = $endTime;
        }

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/log/'.$domain.'/log'
        );
    }

    /**
     * check specified ip if belongs to Baidu CDN
     * @param $options array None
     * @param string $ip [<specified ip>]
     * @return response
     * @throws BceClientException
     */
    public function ipQuery($action, $ip, $options = array())
    {
        list($config) = $this->parseOptionsIgnoreExtra($options, 'config');

        if (!empty($config)) {
            unset($options['config']);
        }
        $params = array();
        $params['action'] = $action;
        $params['ip'] = $ip;

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/utils'
        );
    }

    /**
     * Create HttpClient and send request
     * @param string $httpMethod The Http request method
     * @param array $varArgs The extra arguments
     * @param string $requestPath The Http request uri
     * @return mixed The Http response and headers.
     */
    private function sendRequest($httpMethod, array $varArgs, $requestPath = '/')
    {
        $defaultArgs = array(
            'config' => array(),
            'body' => null,
            'headers' => array(),
            'params' => array(),
        );

        $args = array_merge($defaultArgs, $varArgs);
        if (empty($args['config'])) {
            $config = $this->config;
        } else {
            $config = array_merge(
                array(),
                $this->config,
                $args['config']
            );
        }
        if (!isset($args['headers'][HttpHeaders::CONTENT_TYPE])) {
            $args['headers'][HttpHeaders::CONTENT_TYPE] = HttpContentTypes::JSON;
        }
        $path = $this->prefix . $requestPath;
        $response = $this->httpClient->sendRequest(
            $config,
            $httpMethod,
            $path,
            $args['body'],
            $args['headers'],
            $args['params'],
            $this->signer
        );

        $result = $this->parseJsonResult($response['body']);
        $result->metadata = $this->convertHttpHeadersToMetadata($response['headers']);
        return $result;
    }
}
