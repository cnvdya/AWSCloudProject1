<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();


$autoload['libraries'] = array('form_validation','session', 'pagination');


$autoload['drivers'] = array();


$autoload['helper'] = array('url', 'form','text');


$autoload['config'] = array();


$autoload['language'] = array();


$autoload['model'] = array('post_model','category_model', 'comment_model','user_model');
