<?php
namespace App\System;


abstract class AbstractController
{
    protected $config;

    public function __construct()
    {
        $this->config = Registry::get('config');
    }

    abstract public function index();

}