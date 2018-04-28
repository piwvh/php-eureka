<?php

namespace Eureka\Interfaces;


interface InstanceProvider {

    /**
     * @param $appName string
     * @return array
     */
    public function getInstances($appName);

}