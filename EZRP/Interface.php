<?php

interface EZRP_Interface
{
    public function prepare(array $options = array());
    public function verify(array $options);
    public function getProfileData(array $options);
    public function getMap(array $options);
    public function addMap(array $options);
}
?>
