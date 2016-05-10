<?php

interface Event
{
    /**
     * @return array
     */
    public function getParameters();

    /**
     * @return string
     */
    public function getType();
}
