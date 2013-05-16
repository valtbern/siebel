<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deliveryterms_model extends CI_Model
{

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}
	
	/* *********************************************************************
	 * Other function below this section
	 */
	
	public function getTerms($cuno, $id = FALSE) 
	{
		$dbDefault = $this->load->database('default', TRUE);
		$dbDefault->where('customernumber', $cuno);
		if($id)
		{
			$dbDefault->where('id', $id);
		}
		$dbDefault->order_by('date', 'desc');
		$results = $dbDefault->get('deliveryterms')->result();
		return $results;
	}
	
	public function saveTerms($cuno, $id)
	{
		$dbDefault = $this->load->database('default', TRUE);
		
		if($id == 'new')
		{
			$data = $_POST;
			$data['customernumber'] = $cuno;
			if($dbDefault->insert('deliveryterms', $data))
			{
				return $dbDefault->insert_id();
			}
		}
		else
		{
			$dbDefault->where('id', $id);
			if($dbDefault->update('deliveryterms', $_POST))
			{
				return $id;
			}
		}
	}
	
}

/* End of file */