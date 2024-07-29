<?php 

/**
* 
*/
class M_pemasukan extends CI_model
{

private $table = 'tb_pemasukan';
private $table2 = 'tb_kegiatan';

//pemasukan
public function view($value='')
{
  $this->db->select ('*');
  $this->db->from ($this->table);
  $this->db->join ($this->table2, 'tb_pemasukan.id_kegiatan = tb_kegiatan.id_kegiatan');
  $this->db->order_by('tgl_pemasukan', 'DESC');
  return $this->db->get();
}

public function view_id($id='')
{
 return $this->db->select ('*')->from ($this->table)->where ('id_pemasukan', $id)->get ();
}

//mengambil id pemasukan urut terakhir
public function id_urut($value='')
{ 
  $this->db->select_max('id_pemasukan');
  $this->db->from ($this->table);
}

public function add($SQLinsert){
  return $this -> db -> insert($this->table, $SQLinsert);
}

public function update($id='',$SQLupdate){
  $this->db->where('id_pemasukan', $id);
  return $this->db-> update($this->table, $SQLupdate);
}

public function delete($id=''){
  $this->db->where('id_pemasukan', $id);
  return $this->db-> delete($this->table);
}

}