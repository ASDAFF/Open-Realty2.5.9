<?php
// Generic iterator Module

eval('class iterator_EXTENDER extends ' . $last_module . '_ADOConnection { }');

class iterator_ADOConnection extends iterator_EXTENDER
{
}

eval('class iterator_resultset_EXTENDER extends ' . $last_module . '_ResultSet { }');

class iterator_ResultSet extends iterator_resultset_EXTENDER
{
	function &GetToFieldArray($fieldname = false) 
	{
		$results = false;
		if(!$fieldname)
		{
			return $results; 
		}

		while (!$this->EOF) {
			$results[$this->fields[$fieldname]] = $this->fields;
			$this->MoveNext();
		}
		return $results; 
	}

}
?>