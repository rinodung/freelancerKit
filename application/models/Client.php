<?php

class Client extends ActiveRecord\Model {
	static $has_many = array(
    array('projects'),
    array('invoices')
    );

    static $belongs_to = array(
    array('company')
    );
}