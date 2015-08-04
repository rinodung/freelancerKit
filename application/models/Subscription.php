<?php

class Subscription extends ActiveRecord\Model {
    static $belongs_to = array(
    array('company')
    );
    static $has_many = array(
    array('subscription_has_items'),
    array('invoices')
 	); 

}