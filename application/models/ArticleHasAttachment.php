<?php

class ArticleHasAttachment extends ActiveRecord\Model {
    static $table_name = 'article_has_attachments';

    static $belongs_to = array(
     array('TicketHasArticle', 'foreign_key' => 'article_id')
  	);
}
