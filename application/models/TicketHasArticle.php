<?php

class TicketHasArticle extends ActiveRecord\Model {
    static $table_name = 'ticket_has_articles';

    static $has_many = array(
    array("article_has_attachments"),
    );
   	
    static $belongs_to = array(
     array('ticket'),
     array(
           	'client',
            'foreign_key' => 'email',
            'primary_key' => 'from',
        ),
  );
  
}
