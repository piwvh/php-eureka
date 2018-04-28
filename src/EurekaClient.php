<?php

namespace Eureka;

use Eureka\Exceptions\DeRegisterFailureException;
use Eureka\Exceptions\InstanceFailureException;
use Eureka\Exceptions\RegisterFailureException;
use GuzzleHttp\Client as GuzzleClient;

class EurekaClient {

    /**
     * @var EurekaConfig
     */
    private $config;

    // constructor
    public function __construct($config) {
        $this->config = new EurekaConfig($config);
    }

    // getter
    public function getConfig() {
        return $this->config;
    }

    // register with eureka
    public function register() {
        $config = $this->config->getRegistrationConfig();

        $client = new GuzzleClient(['base_uri' => $this->config->getEurekaDefaultUrl()]);
        $this->output("[" . date("Y-m-d H:i:s") . "]" . " Registering...");
        $response = $client->request('POST', '/eureka/apps/' . $this->config->getAppName(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'body' => json_encode($config)
        ]);

        if($response->getStatusCode() != 204) {
            throw new RegisterFailureException("Could not register with Eureka.");
        }
    }

    // de-register from eureka
    public function deRegister() {
        $client = new GuzzleClient(['base_uri' => $this->config->getEurekaDefaultUrl()]);
        $this->output("[" . date("Y-m-d H:i:s") . "]" . " De-registering...");
        $response = $client->request('DELETE', '/eureka/apps/' . $this->config->getAppName() . '/' . $this->config->getInstanceId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new DeRegisterFailureException("Cloud not de-register from Eureka.");
        }
    }

    // send heartbeats to eureka
    public function heartbeat() {
        $client = new GuzzleClient(['base_uri' => $this->config->getEurekaDefaultUrl()]);
        $this->output("[" . date("Y-m-d H:i:s") . "]" . " Sending heartbeat...");
        $response = $client->request('PUT', '/eureka/apps/' . $this->config->getAppName() . '/' . $this->config->getInstanceId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        if($response->getStatusCode() != 200) {
            $this->output("[" . date("Y-m-d H:i:s") . "]" . " Heartbeat failed... (code: " . $response->getStatusCode() . ")");
        }
    }

    public function start() {
        $this->register();

        $counter = 0;
        while (true) {
            $this->heartbeat();
            $counter++;
            sleep($this->config->getHeartbeatInterval());
        }

        return 0;
    }

    public function fetchInstance($appName) {
        $client = new GuzzleClient(['base_uri' => $this->config->getEurekaDefaultUrl()]);
        $response = $client->request('GET', '/eureka/apps/' . $appName, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new InstanceFailureException("Could not get instances.");
        }

        $body = json_decode($response->getBody()->getContents());
        if(!isset($body->application->instance) || count($body->application->instance) == 0) {
            throw new InstanceFailureException("No instance found for '" . $appName . "'.");
        }

        return $this->config->getDiscoveryStrategy()->getInstance($body->application->instance);
    }

    private function output($message) {
        if(php_sapi_name() !== 'cli')
            return;

        echo $message . "\n";
    }
}