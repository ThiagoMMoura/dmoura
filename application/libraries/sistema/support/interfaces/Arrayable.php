<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Arrayable
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}
