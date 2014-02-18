<?php
class api extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('db_dcs_log');
		$this->load->model('db_gcs_log');
		$this->load->model('db_scs_log');
		$this->load->model('db_wms_log');
	}
	
	public function dcs_status(){
		$status = (read_file("assets/device/dcs/status.txt") == 1 ? "Armed" : "Disarmed");
		$password_attempts = read_file("assets/device/dcs/password_attempts.txt");
		$condition = (read_file("assets/device/dcs/condition.txt") == 1 ? "Locked" : "Unlocked");
		$val = array($status, $password_attempts, $condition);
		echo json_encode($val);
	}
	
	public function dcs_today_log(){
		$data['today_log'] = $this->db_dcs_log->get_today();
		$this->load->view('dcs/dcs_today_log', $data);
	}
	
	public function dcs_insert_log(){
		$name = $this->input->post('name');
		$data = array('name'=>$name);
		$this->db_dcs_log->insert($data);
	}
	
	public function gcs_temperature(){
		header("Content-type: text/json");
		$log = $this->db_gcs_log->get_last_log();
		$x = strtotime($log->time)*1000;
		$y = intval($log->temperature);
		$ret = array($x, $y);
		echo json_encode($ret);
	}
	
	public function gcs_lux(){
		header("Content-type: text/json");
		$log = $this->db_gcs_log->get_last_log();
		$x = strtotime($log->time)*1000;
		$y = intval($log->lux);
		$ret = array($x, $y);
		echo json_encode($ret);
	}
	
	public function hcs_lamp_value($param){
		header("Content-type: text/json");
		$val = intval(read_file("assets/device/hcs/".$param.".txt"));
		echo json_encode($val);
	}
	
	public function scs_today_temperature(){
		header("Content-type: text/json");
		$ret = array();
		foreach($this->db_scs_log->get_today() as $row){
			$log[0] = strtotime($row->time)*1000;
			$log[1] = floatval($row->temperature);
			array_push($ret, $log);
		}
		echo json_encode($ret);
	}
	
	public function scs_today_smoke(){
		header("Content-type: text/json");
		$ret = array();
		foreach($this->db_scs_log->get_today() as $row){
			$log[0] = strtotime($row->time)*1000;
			$log[1] = intval($row->smoke);
			array_push($ret, $log);
		}
		echo json_encode($ret);
	}
	
	public function scs_realtime_value(){
		header("Content-type: text/json");
		$smoke = intval(read_file("assets/device/scs/smoke.txt"));
		$temp = floatval(read_file("assets/device/scs/temp.txt"));
		$val = array($smoke, $temp);
		echo json_encode($val);
	}
	
	public function scs_temperature(){
		header("Content-type: text/json");
		$log = $this->db_scs_log->get_last_log();
		$x = strtotime($log->time)*1000;
		$y = floatval($log->temperature);
		$ret = array($x, $y);
		echo json_encode($ret);
	}
	
	public function scs_smoke(){
		header("Content-type: text/json");
		$log = $this->db_scs_log->get_last_log();
		$x = strtotime($log->time)*1000;
		$y = intval($log->smoke);
		$ret = array($x, $y);
		echo json_encode($ret);
	}
	
	public function scs_insert_log(){
		$temp = $this->input->post('temp');
		$smoke = $this->input->post('smoke');
		write_file("assets/device/scs/temp.txt", $temp);
		write_file("assets/device/scs/smoke.txt", $smoke);
		$data = array(
			'temperature'=>$temp,
			'smoke'=>$smoke
		);
		//$this->db_scs_log->insert($data);
	}
	
	public function wms_today_turbidity(){
		header("Content-type: text/json");
		$ret = array();
		foreach($this->db_wms_log->get_today() as $row){
			$log[0] = strtotime($row->time)*1000;
			$log[1] = intval($row->turbidity);
			array_push($ret, $log);
		}
		echo json_encode($ret);
	}
	
	public function wms_realtime_value(){
		header("Content-type: text/json");
		$turbidity = intval(read_file("assets/device/wms/lux.txt"));
		$water_level = intval(read_file("assets/device/wms/water_level.txt"));
		$val = array($turbidity, $water_level);
		echo json_encode($val);
	}
	
	public function wms_water_level(){
		header("Content-type: text/json");
		$log = $this->db_wms_log->get_last_log();
		$y = intval($log->water_level);
		echo json_encode($y);
	}
	
	public function wms_turbidity(){
		header("Content-type: text/json");
		$log = $this->db_wms_log->get_last_log();
		$x = strtotime($log->time)*1000;
		$y = intval($log->turbidity);
		$ret = array($x, $y);
		echo json_encode($ret); 
	}
	
	public function wms_insert_log(){
		$water_level = 250-($this->input->post('var1'));
		if($this->input->post('var2') > 1000){
			$turbidity = 0;
		}else{
			$turbidity = 100-(($this->input->post('var2'))/10);
		}
		$old_turbidity = read_file("assets/device/wms/lux.txt");
		write_file("assets/device/wms/water_level.txt", $water_level);
		write_file("assets/device/wms/lux.txt", $turbidity);
		if($old_turbidity-$turbidity > 2 || $turbidity-$old_turbidity > 2){
			$data = array(
					'water_level'=>$water_level,
					'turbidity'=>$turbidity
			);
			$this->db_wms_log->insert($data);
		}
	}
}
