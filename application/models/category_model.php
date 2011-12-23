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
  
  function all_galleries($name = TRUE)
  {
    $categories = $this->all();
    //print_r($categories);
    $final = array();
    foreach ($categories as $category)
    {
      //print_r($category);
      if($name)
      {
        $final[$category->title] = $this->galleries((int)$category->id);
      }
      else
      {
        $final[$category->id] = $this->galleries((int)$category->id);
      }
    }
    //print_r($final);
    return $final;
    
  }
  
  function id($name)
  {
    $db_result = $this->db->get_where('categories', array('title' => $name));
    if($db_result->num_rows > 0)
    {
      return $db_result->row()->id;
    }
    else
    {
      return FALSE;
    }
  }
  
  function galleries($id_or_name)
  {
    if (is_int($id_or_name))
    {
      $id = $id_or_name;
      return $this->db->get_where('galleries', array('category' => $id))->result();
    }
    elseif (is_string($id_or_name))
    {
      $name = $id_or_name;
      $id = $this->id($name);
      return $this->db->get_where('galleries', array('category' => $id))->result();
    }
  }
  
  function create($title)
  {
    $order = $this->db->select_max('`order`')->get('categories')->row()->order + 1;
    //$order = 3;
    $data = array(
      'title' => $title,
      'order' => $order
    );
    $this->db->insert('categories', $data);
    return $this->db->insert_id();
  }
  
}