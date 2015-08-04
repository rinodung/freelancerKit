<?php

class ProjectHasTask extends ActiveRecord\Model {
    static $table_name = 'project_has_tasks';
  
   static $belongs_to = array(
     array('user')
  );
}
