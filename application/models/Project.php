<?php

class Project extends ActiveRecord\Model {
	static $belongs_to = array(
     array('company')
  );

	static $has_many = array(
    array("project_has_tasks"),
    array('project_has_files'),
    array('project_has_workers'),
    array('project_has_invoices'),
    array('project_has_activities',
           'order'    => 'datetime DESC'),
    array('messages')
    );
}
