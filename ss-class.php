<?php


class Section_Subnavigation {
	
	private $_curPost;
	private $_ancestors;
	private $_sectionParent;
	private $_sectionPosts;
	private $_sectionTitle;
	private $_menu;
	
	public function __construct($id = 0) {
		if ( is_object($id) ) {
			$this->_curPost = $id;
		} else {
			$this->_curPost = &get_post($id);
		}
		if ( empty($this->_curPost->ID) ) {
			return false;
		}
		
		$this->setAncestors();
		$this->setSectionParent();
		$this->setSectionTitle();
		$this->buildMenu();
	}
	
	private function setAncestors() {
		$ancestors = get_post_ancestors($this->_curPost);
		$this->_ancestors = array_reverse($ancestors);
	}
	
	private function setSectionParent() {
		if( is_array($this->_ancestors) && !empty($this->_curPost->post_parent) ) {
			// _curPost is not the top page in the section
			$this->_sectionParent = &get_post($this->_ancestors[0]);
		} elseif( !$this->_curPost->post_parent ) {
			// _curPost is the top page in the section
			$this->_sectionParent = $this->_curPost;
		}
	}
	
	private function setSectionTitle() {
		if( is_object($this->_sectionParent) && !empty($this->_sectionParent->ID) ) {
			$this->_sectionTitle = $this->_sectionParent->post_title;
		}
	}
	
	private function buildMenu() {
		
		$ss_menu = new SS_Walker();
		$ss_pages_args = array(
			'depth'        => 0,
			'show_date'    => "",
			'date_format'  => get_option('date_format'),
			'child_of'     => $this->_sectionParent->ID,
			'exclude'      => "",
			'include'      => "",
			'title_li'     => "",
			'echo'         => 0,
			'authors'      => "",
			'sort_column'  => 'menu_order, post_title',
			'link_before'  => "",
			'link_after'   => "",
			'walker'       => $ss_menu,
			'post_type'    => 'page',
			'post_status'  => 'publish' 
		);
		$this->_sectionPosts = ss_list_pages( $ss_pages_args );
		if( !empty($this->_sectionPosts) ) {
			$this->_menu  = "\n" . '<ul class="section-subnavigation menu">' . "\n";
			$this->_menu .= $this->_sectionPosts;
			$this->_menu .= "\n" . '</ul><!-- .section-subnavigation -->' . "\n";
		}
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
}