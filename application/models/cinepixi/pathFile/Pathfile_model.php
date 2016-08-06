<?php
class Pathfile_model extends CI_Model{

	public function __construct() {
        parent::__construct();
        $this->user_id=$this->session->userdata("user_id");
    }

    public function m_name($uri_string){

    $data=array();
    $q=$this->db->select("id,name,link")
                ->where("link",$uri_string)
                ->from("modules")
                ->get();

        if($q->result_array())
        foreach ($q->result_array() as $key => $value):
            $data=$value;
        endforeach;

    return $data;
    }

    // mismo registro
    public function record_same_pathFile($data,$id){
        $ac=false;

        // if(!empty($id))
        $this->db->where_not_in("id",$id);

        $this->db->where($data);
        $row=$this->db->get("cinepixi_pathFile");
        
        if($row->num_rows())
        $ac=true;    

        return $ac;
    }
        
    public function get_pathFile_amount($query_search){

    $this->db->select('id');
    $this->db->from('cinepixi_pathFile');

    if(!empty($query_search))
    foreach ($query_search as $k => $row)
    eval($row);

    $q=$this->db->get();

    return $q->num_rows();

    }

    public function get_pathFile($start,$end,$query_search){

    $this->db->select("id,name");
    $this->db->from("cinepixi_pathFile");

    if(!empty($query_search))
    foreach ($query_search as $k => $row)
    eval($row);

    $this->db->limit($start,$end);

    $this->db->order_by("id","asc");
	$q=$this->db->get();

    return $q->result_array();

    }

    public function get_pathFile_id($id,$pathFile_subsidiary=null){

        $data=array(
        "id"=>"",
        "name"=>"",
        );
        
    $q=$this->db->select(implode(",", array_keys($data)))
                ->where("id",$id)
                ->from("cinepixi_pathFile")
                ->get();

    if($q->result_array())
    foreach ($q->result_array() as $key => $value)
    $data=$value;
    
    return $data;

    }

    public function update_pathFile($data,$id){
        
        $this->db->where("id",$id);
        $this->db->update("cinepixi_pathFile",$data);

    return $id;
    }

    public function insert_pathFile($data){
        
        $this->db->insert("cinepixi_pathFile",$data);
        
    return $this->db->insert_id();
    }

    public function pathFile_delete_it($id){

        $this->db->where("id",$id);
        if($this->db->delete("cinepixi_pathFile"))
        return true;
    }

// <Get>
  public function select_option($array){
    $data=array("0"=>"Seleccione");
        foreach ($array as $key => $value)
        $data[$value["id"]]=$value["name"];
    return $data;    
    } 
    // <get pathFile>
    // Traer el arreglo para usarse con el token input 
    public function get_cinepixi_pathFile_token_search($var_name=null){

    $data=array();
    $this->db->select('id,name');
    $this->db->from('cinepixi_pathFile');
    $this->db->order_by('id','desc');

    if($var_name)
    eval($var_name);

    if($q=$this->db->get())
    $data =$q->result_array();

    return $data;

    }
// </Get>

    public function get_cinepixi_pathFile_to_option($flip=null,$id){

    $data=array(0=>"Seleccione un Padre");
    $this->db->select('id,name');

    if(!empty($id))
    $this->db->where_not_in("id",$id);

    $this->db->from('cinepixi_pathFile');
    $this->db->order_by('id','desc');

    if($q=$this->db->get())
    foreach ($q->result_array() as $row) {
        if($flip)
        $data[$row["name"]]=$row["id"];
        else
        $data[$row["id"]]=$row["name"];
    }
    return $data;
    }

}
?>