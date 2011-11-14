<?php
/**
* Provides access to the categories table, accessing galleries of a specific category and so on.
* 
* @todo Add methods to deal with ordering elements.
*/
class Category_model extends CI_Model {
  
  function all()
  {
    return $this->db->get('categories')->result();
  }
  
  function galleries($id)
  {
    return $this->db->get_where('galleries', array('category' => $id))->result();
  }
  
  function create($title)
  {
    $data = array(
      'title' => $title,
      'order' => $this->db->select_max('order')->get('catergory')->row()->order + 1
    );
    $this->db->insert('categories', $data);
  }
  
}