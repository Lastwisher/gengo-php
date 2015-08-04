<?php
/**
 * Gengo API Client
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that came
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL:
 * http://gengo.com/services/api/dev-docs/gengo-code-license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@gengo.com so we can send you a copy immediately.
 *
 * @category   Gengo
 * @package    API Client Library
 * @copyright  Copyright (c) 2009-2012 Gengo, Inc. (http://gengo.com)
 * @license    http://gengo.com/services/api/dev-docs/gengo-code-license   New BSD License
 */

class Gengo_Api_Order extends Gengo_Api
{
    public function __construct($api_key = null, $private_key = null)
    {
        parent::__construct($api_key, $private_key);
    }

    /**
     * translate/order/{id} (GET)
     *
     * Retrieves a specific order and return various information and statistics.
     *
     * @param int $id The id of the job to retrieve
     * @param string $format The response format, xml or json
     * @param array|string $params If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getOrder($id = null, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/order/{$id}/";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/order/{id} (DELETE)
     *
     * Cancels all jobs in an order that can be cancelled (available jobs)
     *
     * This feature is EXPERIMENTAL
     *
     * @param string $format The response format, xml or json
     * @param array|string $params If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function cancel($id = null, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/order/{$id}";
        $this->response = $this->client->delete($baseurl, $format, $params);
    }

    /**
     * translate/order/{id}/comments (GET)
     *
     * Retrieves the comment thread for a order
     *
     * @param int $id The id of the order to retrieve
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function getComments($id, $format = null, $params = null)
    {
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/order/{$id}/comments";
        $this->response = $this->client->get($baseurl, $format, $params);
    }

    /**
     * translate/order/{id}/comment (POST)
     *
     * Submits a new comment to the order's comment thread.
     *
     * @param int $id The id of the order to comment on
     * @param string $body The comment's actual contents.
     * @param string $format The OPTIONAL response format: xml or json (default).
     * @param array|string $params (DEPRECATED) If passed should contain all the
     * necessary parameters for the request including the api_key and
     * api_sig
     */
    public function postComment($id, $body, $format = null, $params = null)
    {
        if (!(is_null($id) || is_null($body))) // If nor the id or the body are null, we override params.
        {
            $data = array('body' => $body);

            $ts = gmdate('U');
            // create the query
            $params = array('api_key' => $this->config->get('api_key', null, true), '_method' => 'post',
                            'ts'      => $ts,
                            'data'    => json_encode($data),
                            'api_sig' => Gengo_Crypto::sign($ts, $this->config->get('private_key', null, true)),
            );
        }

        if (empty($params))
        {
            throw new Gengo_Exception(
                sprintf('In method %s: "params" must contain a valid "body" parameter as the comment', __METHOD__)
                );
        }
        $this->setParams($id, $format, $params);
        $baseurl = $this->config->get('baseurl', null, true);
        $baseurl .= "v2/translate/order/{$id}/comment";
        $this->response = $this->client->post($baseurl, $format, $params);
    }
}

