<?php

class LogXML{
	
	public $date;
	
	public $action;
	
	public $step;
	public $component;
	public $field;
	public $attribute;
	public $value_old;
	public $value_new;
	public $label;
	
	public $orderComponent_old;
	public $orderStep_old;
	public $orderComponent_new;
	public $orderStep_new;
	
	function __construct()
	{
		$this->date = time();
	}
	
	function setLabel($val,$component)
	{
		if(empty($val))
		{
			$label=t('BOFORMS_LABEL_'.$component);
			
			if(strpos($label,'[cle1')===false)
			{
				$this->label = $label;
			}else{
				$this->label = $component;
			}
		}else{
			$this->label = strip_tags(trim($val));
		}
		
	}
	
	function setLogEditComponent($component,$field,$value_old,$value_new,$label)
	{
		$this->action ="BOFORMS_TRACE_EDIT_COMPONENT";
		
		$this->component = $component;
		$this->field = $field;
		$this->value_old = $value_old;
		$this->value_new = $value_new;
		
		$this->setLabel($label,$component);
				
	}
	
	function setLogEditHTML($component,$field,$value_old,$value_new)
	{
		$this->action ="BOFORMS_TRACE_EDIT_COMPONENT";
	
		$this->component = $component;
		$this->field = $field;
		$this->value_old = $value_old;
		$this->value_new = $value_new;
	
		$this->setLabel($label,$component);
	
	}
	
	function setLogAttributeComponent($component,$field,$attribute,$value_old,$value_new,$label)
	{
		$this->action ="BOFORMS_TRACE_EDIT_COMPONENT";
	
		$this->component = $component;
		$this->field = $field;
		$this->attribute = $attribute;
		$this->value_old = $value_old;
		$this->value_new = $value_new;
		
		$this->setLabel($label,$component);
	
	}
	
	function setLogAddComponent($component,$label)
	{
		$this->action ="BOFORMS_TRACE_ADD_COMPONENT";
		$this->component = $component;
		$this->setLabel($label,$component);
	}
	
	function setLogRemoveComponent($component,$label)
	{
		$this->action ="BOFORMS_TRACE_REMOVE_COMPONENT";
		$this->component = $component;
		$this->setLabel($label,$component);

	}
	
	function setLogMoveFieldStep($component,$old_step,$new_step,$label)
	{
		$this->action ="BOFORMS_TRACE_CHANGE_STEP_COMPONENT";
	
		$this->component = $component;
		$this->orderStep_old = $old_step;
		$this->orderStep_new = $new_step;
		$this->setLabel($label,$component);
	
	
	}
	
	function setLogMoveComponent($orderComponent_old,$orderComponent_new)
	{
		$this->action ="BOFORMS_TRACE_MOVE_COMPONENT";
		
		$this->orderComponent_old = $orderComponent_old;
		$this->orderComponent_new = $orderComponent_new;
	}
	
	function setLogEditStep($step,$value_old,$value_new)
	{
		$this->action ="BOFORMS_TRACE_EDIT_STEP_COMPONENT";
		
		$this->step = $step;
		$this->value_old = $value_old;
		$this->value_new = $value_new;
				
	}
	
	function setLogMoveStep($orderStep_old,$orderStep_new)
	{
		$this->action ="BOFORMS_TRACE_MOVE_STEP";
		
		$this->orderStep_old = $orderStep_old;
		$this->orderStep_new = $orderStep_new;
	}
	
}
