<?php
function los_scripts() {
wp_register_script( 'script-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery'), '3.3.7', true );
wp_register_script( 'moment', get_template_directory_uri() . '/js/moment.min.js', array(), '2.17.1', true );
wp_register_script( 'datetimepicker', get_template_directory_uri() . '/js/datetimepicker.min.js', array('jquery', 'moment'), '2.17.1', true );
wp_enqueue_script( 'script-bootstrap' );
wp_enqueue_script( 'datetimepicker' );
}

add_action( 'wp_enqueue_scripts', 'los_scripts' );



if ( function_exists( 'add_theme_support' ) ) {
add_theme_support( 'post-thumbnails' );
}

if(!get_option("medium_crop"))
add_option("medium_crop", "1");
else
update_option("medium_crop", "1");

  // Custom User Role
  add_action('after_switch_theme', 'municipality_role');

  function municipality_role(){
    $result = add_role(
      'municipality',
      'Municipalidad',
      array(
        'read'           => true,
        'edit_posts'     => true,
        'delete_posts'   => true,
      )
    );
  }

  // Manage POST
  function post($key, $type, $i) {
    if(array_key_exists($key, $_POST)) {
        $out = isset($i) ? $_POST[$key][$i] : $_POST[$key];
        if ($type == 'int') { $out = intval($out); }
        if ($type == 'text') { $out = htmlentities($out, ENT_QUOTES); }
        if ($type == 'date') {
          $d = date_parse($out);
          $out = $d['year'].'-'.$d['month'].'-'.$d['day'];
        }
        if ($out) { return $out; }
    }
    return '';
  }

  // Uploader
  function upload_image($name){
    $file = $_FILES[$name];
    if (!$file['error'] && $file['name']) {
      $upload = wp_handle_upload($file, array('test_form' => false));
      if ($upload) {
        $attach_id = wp_insert_attachment(array(
          'post_mime_type' => $file['type'],
          'post_title' => $file['name'],
          'post_content' => '',
          'post_status' => 'inherit'
        ), $upload['file']);
        $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
        wp_update_attachment_metadata($attach_id,  $attach_data);
        return $attach_id;
      }
    }
    return 0;
  }

  // Project Caller
  global $_proj;
  function get_the_project($id = 0){
    global $post, $wpdb, $_proj;
    $id = $id? $id : get_post_meta($post->ID, 'project_id')[0];
    if($id){
      $project = $wpdb->get_row("SELECT * FROM 227_projects WHERE id = $id");
      $project->hum_start_date = esp_hum_date($project->start_date);
      $project->hum_end_date = esp_hum_date($project->end_date);
      $project->hum_delivery_date = esp_hum_date($project->delivery_date);
      $dimensions = ['media', 'budgets', 'phases', 'managers'];
      foreach ($dimensions as $name) {
        $table = '227_proj_'.$name;
        $project->$name = $wpdb->get_results("SELECT * FROM $table WHERE proj_id = $id");
        add_hum_date($project->$name);
      }
      $_proj = $project;
      return $project;
    }
    return null;
  }

  function add_hum_date(&$table){
    foreach ($table as $d) {
      if ($d->start_date) $d->hum_start_date = esp_hum_date($d->start_date);
      if ($d->end_date) $d->hum_end_date = esp_hum_date($d->end_date);
      if ($d->opening_date) $d->hum_end_date = esp_hum_date($d->opening_date);
      if ($d->delivery_date) $d->hum_delivery_date = esp_hum_date($d->delivery_date);
    }
  }

  function esp_hum_date($str){
    $date = date_parse($str);
    $months = array(
      '1'  => 'enero',
      '2'  => 'febrero',
      '3'  => 'marzo',
      '4'  => 'abril',
      '5'  => 'mayo',
      '6'  => 'junio',
      '7'  => 'julio',
      '8'  => 'agosto',
      '9'  => 'septiembre',
      '10' => 'octubre',
      '11' => 'noviembre',
      '12' => 'diciembre',
    );
    return $date['day'].' de '.$months[$date['month']].' del '.$date['year'];
    return 'fuu';
  }

  // Project Media Caller
  function get_project_media($name, $size, $attrs){
    global $_proj;
    if($_proj && $_proj->media){
      for ($i = 0; $i<= count($_proj->media); $i++) {
        $m = $_proj->media[$i];
        if ($m->type == $name){
          $img = wp_get_attachment_image($m->wp_media_id, $size, false, $attrs);
          return $img ? $img : null;
        }
      }
    }
    return null;
  }

  // Project Object
  if (!function_exists('wp_handle_upload')) {
    require_once(ABSPATH.'wp-admin/includes/file.php');
  }
  
  if (!function_exists('wp_generate_attachment_metadata')) {
    require_once(ABSPATH.'wp-admin/includes/image.php');
  }

  class Project{
    private $up;
    private $last;
    public $id;
    public $right;
    public $post;
    
    function __construct($table, $data){
      global $wpdb;
      $this->up = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
      $this->last = '';
      if ($this->up) {
        $this->right = $wpdb->update($table, $data, array('id' => $this->up));
        if ($this->right) {
          wp_update_post(array(
            'ID'         => $_POST['project_post_id'],
            'post_title' => post('theTitle', 'text'),
            'post_date' => date('Y-m-d H:i:s')
          ));
        }
        $project->post = 0;
        $this->id = $this->up;
      }
      else {
        $this->right = $wpdb->insert($table, $data);
        $this->id = $wpdb->insert_id;
        if ($this->right) {
          $this->post = wp_insert_post(array(
            'post_title'   => post('theTitle', 'text'),
            'post_type'    => 'projects',
            'post_status'  => 'publish'
          ));
        }
        if ($this->post) add_post_meta($this->post, 'project_id', $this->id);
        else $this->right = $this->post;
      }
    }
    
    public function write($table, $data){
      $is_last = $this->last != $table;
      $this->last = $table;
      if ($this->right){
        global $wpdb;
        $where = array('proj_id' => $this->id);
        if ($this->up && $is_last) $this->right = $wpdb->delete($table, $where);
        $data = array_merge($data, $where);
        $this->right = $wpdb->insert($table, $data);
      }
    }
  }

?>
