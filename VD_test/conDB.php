<?php



ini_set('memory_limit', '-1');
ini_set('max_execution_time','0');


define("DB_HOST", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "unDivide");

class DBClass {

    var $conn,$flag = 0,$select_result = array(),$create_result = array(),$temp_result = array(),$tt = array();
    
    public function __construct() {
        $this->connect();
    }

    public function disconnect() {
        mysqli_close($this->conn);
    }

    public function reconnect() {
        $this->disconnect();
        $this->connect();
    }

    public function connect() {
        $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->conn->set_charset( 'utf8mb4' );
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } 
    }

    public function getinfo(){
        $sql = "SELECT * FROM `info`"; 
        $res = mysqli_query($this->conn, $sql);
        if (mysqli_num_rows($res) > 0) { 
            $count = 0;
            while ($row = mysqli_fetch_array($res)) { 
                echo "<div class='custom-control custom-checkbox'>";
                echo "<input type='checkbox' class='custom-control-input' id='defaultUnchecked".$count."'>";
                echo "<label class='custom-control-label' for='defaultUnchecked".$count."'>".$row['vdid']."</label>";
                $count+=1;
                // echo "apple";
                echo "</div>";
            }
        } 
        else { 
            echo "No matching records are found."; 
        }  
    }
    public function mysqli_field_name($result, $field_offset){
        $properties = mysqli_fetch_field_direct($result, $field_offset);
        return is_object($properties) ? $properties->name : false;
    }

    public function data_clear(){
        $temp = array();
        $temp[0] = "Number";
        $temp[1] = "Vdid";
        $temp[2] = "Time";
        $temp[3] = "Speed";
        $temp[4] = "Laneoccupy";
        $temp[5] = "Volume";
        array_push($this->tt,$temp);
        for($i=1;$i<count($this->select_result);$i++){
            $temp = array();
            $total_volume = 0;
            $total_speed = 0;
            $total_laneoccupy = 0;
            for($j=5;$j<=20;$j+=3){
                $total_volume += $this->select_result[$i][$j];
            }
            for($j=3;$j<=20;$j+=3){
                $total_speed += $this->select_result[$i][$j]*$this->select_result[$i][$j+2];
            }
            for($j=4;$j<=19;$j+=3){
                $total_laneoccupy += $this->select_result[$i][$j]*$this->select_result[$i][$j+1];
            }
            if($total_volume>0){
                $total_speed = $total_speed/$total_volume;
                $total_laneoccupy = $total_laneoccupy/$total_volume;
            }
            $temp[0] = $this->select_result[$i][0];
            $temp[1] = $this->select_result[$i][1];
            $temp[2] = $this->select_result[$i][2];
            $temp[3] = $total_speed;
            $temp[4] = $total_laneoccupy;
            $temp[5] = $total_volume;
            array_push($this->tt,$temp);
        }
    }
    public function data_clear_double(){
        $temp = array();
        $temp[0] = "Number";
        $temp[1] = "Vdid";
        $temp[2] = "Time";
        $temp[3] = "Speed";
        $temp[4] = "Laneoccupy";
        $temp[5] = "Volume";
        array_push($this->tt,$temp);
        for($i=1;$i<count($this->select_result[1]);$i++){
            $temp = array();
            $total_volume = 0;
            $total_speed = 0;
            $total_laneoccupy = 0;
            for($j=5;$j<=20;$j+=3){
                $total_volume += $this->select_result[$i][$j];
            }
            for($j=3;$j<=20;$j+=3){
                $total_speed += $this->select_result[$i][$j]*$this->select_result[$i][$j+2];
            }
            for($j=4;$j<=19;$j+=3){
                $total_laneoccupy += $this->select_result[$i][$j]*$this->select_result[$i][$j+1];
            }
            if($total_volume>0){
                $total_speed = $total_speed/$total_volume;
                $total_laneoccupy = $total_laneoccupy/$total_volume;
            }
            $temp[0] = $this->select_result[$i][0];
            $temp[1] = $this->select_result[$i][1];
            $temp[2] = $this->select_result[$i][2];
            $temp[3] = $total_speed;
            $temp[4] = $total_laneoccupy;
            $temp[5] = $total_volume;
            array_push($this->tt,$temp);
        }
    }
    public function queryforsinglemonth($table,$startday,$howmanyday){

        $data = '';
        // $flag = 0;
        $header = array();
        
        while($howmanyday>=0){
            $sql = "SELECT * FROM `".$table."` WHERE `time` LIKE '".$startday."%'"; 
            $res = mysqli_query($this->conn, $sql);
            if ($this->flag ==0){
                $num_fields = mysqli_num_fields($res);
            
                for ($i = 0; $i < $num_fields; $i++) {
                    $header[] = $this->mysqli_field_name( $res , $i ) . "\t";   
                }
                array_push($this->select_result,$header);
                $this->flag = 1;
            }
            if ($res) {
                
                while ($row = mysqli_fetch_row($res)) {
                    array_push($this->select_result,$row);
                    // fputcsv($fp, array_values($row));
                }
            }
            $startday = date('Y/m/d',strtotime($startday . "+1 days"));
            $howmanyday = $howmanyday - 1;
        } 
        
        // fclose($fp);
    }
    public function clear(){
        $this->select_result = array();
        $this->flag = 0;
        $this->create_result = array();
        $this->tt = array();
    }
    public function get_diff_array_by_filter($arr1,$arr2){
        try{
            return array_filter($arr1,function($v) use ($arr2){
                return !in_array($v,$arr2);
            });
        }catch (\Exception $exception){
            return $arr1;
        }
    }

    public function queryformutlimonth($table,$year,$firstday,$startday,$endday,$howmanymonth,$howmanyday){

        $data = '';
        // $flag = 0;
        $header = array();
        $this->temp_result = array();
        $temp = $year;
        if ($firstday!="01"){
            $howmanyday_forfirst = (int)$firstday-1;
            while($howmanymonth>0) {
                $sql = "SELECT * FROM `".$year."-".$table."`"; 
                $res = mysqli_query($this->conn, $sql);
                if ($res) {
                    while ($row = mysqli_fetch_row($res)) {
                        array_push($this->temp_result,$row);
                    }
                }
                $year = date('Y-m',strtotime($year . "+1 months"));
                $howmanymonth = $howmanymonth - 1;
            }
            while($howmanyday>=0) {
                $newyear = str_replace("-","/",$year.$endday);
                $sql = "SELECT * FROM `".$year."-".$table."` WHERE `time` LIKE '".$newyear."%'";
                $res = mysqli_query($this->conn, $sql);
                if ($res) {
                    while ($row = mysqli_fetch_row($res)) {
                        array_push($this->temp_result,$row);
                    }
                }
                $newyear = date('Y/m/d',strtotime($newyear . "+1 days"));
                $howmanyday = $howmanyday - 1;
            }
            while($howmanyday_forfirst>0) {
                $deleteday = str_replace("-","/",$temp.$endday);
                $sql = "SELECT * FROM `".$temp."-".$table."` WHERE `time` LIKE '".$deleteday."%'";
                $res = mysqli_query($this->conn, $sql);
                if ($res) {
                    while ($row = mysqli_fetch_row($res)) {
                        array_push($this->create_result,$row);
                    }
                }
                $deleteday = date('Y/m/d',strtotime($deleteday . "+1 days"));
                $howmanyday_forfirst = $howmanyday_forfirst - 1;
            }
            $this->temp_result = $this->get_diff_array_by_filter($this->temp_result,$this->create_result);
            sort($this->temp_result);
            if ($this->flag ==0){
                $num_fields = mysqli_num_fields($res);
            
                for ($i = 0; $i < $num_fields; $i++) {
                    $header[] = $this->mysqli_field_name( $res , $i ) . "\t";   
                }
                array_push($this->select_result,$header);
                $this->flag = 1;
            }
            $this->select_result = array_merge($this->select_result,$this->temp_result);
        }else{
            while($howmanymonth>0){
                $sql = "SELECT * FROM `".$year."-".$table."`"; 
                $res = mysqli_query($this->conn, $sql);
                if ($this->flag ==0){
                    $num_fields = mysqli_num_fields($res);
                
                    for ($i = 0; $i < $num_fields; $i++) {
                        $header[] = $this->mysqli_field_name( $res , $i ) . "\t";   
                    }
                    array_push($this->select_result,$header);
                    $this->flag = 1;
                }
                if ($res) {
                    
                    while ($row = mysqli_fetch_row($res)) {
                        array_push($this->select_result,$row);
                    }
                }
                $year = date('Y-m',strtotime($year . "+1 months"));
                $howmanymonth = $howmanymonth - 1;
            }
            while($howmanyday>=0){
                $newyear = str_replace("-","/",$year.$endday);
                $sql = "SELECT * FROM `".$year."-".$table."` WHERE `time` LIKE '".$newyear."%'";
                $res = mysqli_query($this->conn, $sql);
                if ($res) {
                    while ($row = mysqli_fetch_row($res)) {
                        array_push($this->select_result,$row);
                    }
                }
                $newyear = date('Y/m/d',strtotime($newyear . "+1 days"));
                $howmanyday = $howmanyday - 1;
            }
        }
    }
}
?>