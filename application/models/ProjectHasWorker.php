<?php

class ProjectHasWorker extends ActiveRecord\Model {
    static $table_name = 'project_has_workers';

   	
    static $belongs_to = array(
     array('user')
  );
  
}
