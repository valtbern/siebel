
<?php

class Translate {

	// Pull customerdata from customers-database.
	// => this function remains in Siabel library because there is no related model
	public function getCustomerdata($customernumber, $column = FALSE) {
		$ci = get_instance();
		$dbAsw = $ci->load->database('asw', TRUE);
		$dbAsw->where(param('param_asw_database_column_customernumber'), $customernumber);
		$return = $dbAsw->get(param('param_asw_database_table_customer'))->row();
		return ($column) ? $return->$column : $return;
	}
	
	
	
	/*
	 * Languages from database
	 */
	public function getLang($short = FALSE, $language = FALSE, $id = FALSE) {
		$ci = get_instance();
		$dbDefault = $ci->load->database('default', TRUE);
	
		$language = ($language) ? strtolower(trim($language)) : strtolower($ci->ion_auth->getUserdata('lang'));
		$dbDefault->select(strtolower($language));
		
		if ($id == FALSE && $short != FALSE) {
			$dbDefault->where('short', $short);
		} elseif ($id != FALSE) {
			$dbDefault->where('id', $id);
		};
		
		
		$result = $dbDefault->get('labels')->row();
		return $result->$language;
	}

	public function getDepartmentLang($department, $state) {
		if ($state != 0) {
			$return = $this->getLang($department);
		} else {
			$return = FALSE;
		}

		return $return;
	}
	
	
	
	/*
	 * Prices
	 */
	public function math($expression) {
		eval('$math = ' . preg_replace('/[^0-9\+\-\*\/\(\)\.]/', '', $expression) . ';');
		return $math;
	}

	
	
	/*
	 * Date transformations
	 */
	public function date_to_mysql($date, $time = TRUE) {
		$date = explode('/', $date);
		$time = $time ? '000000' : '';
		return $date[2] . $date[1] . $date[0] . $time;
	}

	public function date_to_mysql_human($date, $time = TRUE) {
		$date = explode('/', $date);
		$time = $time ? ' 00:00:00' : '';
		return $date[2] . '-' . $date[1] . '-' . $date[0] . $time;
	}

	
	
	/*
	 * Price formulas transformations
	 */
	public function formula_to_array($formula) {
		$formula = explode('||', $formula);

		foreach ($formula as $field) {
			$field = explode(':', $field);
			$fields[] = $field;
		}

		unset($fields[count($fields) - 1]);

		return $fields;
	}

	public function formula_to_plain($formula, $index = 1) {
		$plainFromula = '';

		$fields = $this->formula_to_array($formula);

		foreach ($fields as $field) {
			$plainFromula .= $field[$index] . ' ';
		}

		return $plainFromula;
	}

	public function getFormula($id) {
		$ci = get_instance();
		$dbDefault = $ci->load->database('default', TRUE);
		$dbDefault->where('id', $id);
		$result = $dbDefault->get('formulas')->result();
		return $result[0];
	}

	// Used in holidays + lme, but moved to its model
	public function getColumns($table) {
		$ci = get_instance();
		$dbDefault = $ci->load->database('default', TRUE);

		foreach ($dbDefault->list_fields($table) as $key => $value) {
			$return->$value = '';
		}

		return $return;
	}
	
}

?>
