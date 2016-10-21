<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Database\WpDatabase;

class WpDatabaseTest extends TestCase {

  public function setUp() {
    $this->wpdb = $this->getMockBuilder('wpdb')
        ->setMethods(['update', 'delete', 'get_results', 'insert', 'select'])
        ->getMock();
    $this->wpdb->prefix = 'prefix';
    $this->db = new WpDatabase($this->wpdb);

    if(!function_exists('current_time')):
      function current_time($type) {
        return '0000';
      }
    endif;
  }

  public function testUpdate() {
    $this->wpdb->method('update')->will($this->returnArgument(0));
    $this->assertEquals('prefixupdate_arg', $this->db->update('update_arg', [], []));
  }

  public function testDelete() {
    $this->wpdb->method('delete')->will($this->returnArgument(0));
    $this->assertEquals('prefixdelete_arg', $this->db->delete('delete_arg', 'b'));
  }

  public function testGetResults() {
    $this->wpdb->method('get_results')->will($this->returnArgument(0));
    $this->assertEquals('SELECT * FROM prefixget_results_arg', $this->db->all('get_results_arg'));
  }

  public function testInsertResults() {
    $this->wpdb->method('insert')->will($this->returnArgument(0));
    $this->assertEquals('prefixinsert_arg', $this->db->insert('insert_arg', []));
  }

  public function testSelectResults() {
    $this->wpdb->method('get_results')->will($this->returnArgument(0));
    $this->assertEquals('SELECT * FROM prefixselect_arg WHERE a = \'b\';', $this->db->select('select_arg', 'a', 'b'));
  }

  public function testMySqlTime() {
    $this->assertEquals('0000', $this->db->mySqlTime());
  }

  public function testUpdateOption() {
    $this->assertEquals('a b', $this->db->updateOption('a', 'b'));
  }

}
