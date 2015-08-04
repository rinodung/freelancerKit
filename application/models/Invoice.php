<?php

class Invoice extends ActiveRecord\Model {
    static $belongs_to = array(
    array('company'),
    array('project')
    );
    static $has_many = array(
    array('invoice_has_items'),
    array('items', 'through' => 'invoice_has_items')
 	);

}

class InvoiceHasItem extends ActiveRecord\Model {
   	static $belongs_to = array(
    array('invoice'),
    array('item')
    );
}

class Item extends ActiveRecord\Model {
   	static $has_many = array(
    array('invoice_has_items')
    );
} 