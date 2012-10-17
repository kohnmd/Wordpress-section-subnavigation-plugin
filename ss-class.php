<?php


class Section_Subnavigation {
	
	private $_curPost;
	private $_ancestors;
	private $_sectionParent;
	private $_sectionPosts;
	private $_sectionTitle;
	private $_menu;
	
	
	public function __construct($id = 0) {
		if( is_admin() )
			return;
	
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
		if( is_single( $this->_curPost ) ) {
			$ancestors = $this->getAncestorsForSingle();
		} else {
			$ancestors = get_post_ancestors($this->_curPost);
		}
		$this->_ancestors = is_array($ancestors) ?
			array_reverse($ancestors) : "";
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
			'post_status'  => 'publish',
			'current_page' => $this->_curPost->ID
		);
		
		$this->_sectionPosts = ss_list_pages( $ss_pages_args );
		if( !empty($this->_sectionPosts) ) {
			$this->_menu  = "\n" . '<ul class="section-subnavigation menu">' . "\n";
			$this->_menu .= $this->_sectionPosts;
			$this->_menu .= "\n" . '</ul><!-- .section-subnavigation -->' . "\n";
		}
	}
	
	
	/**
	 * Tries to build the menu on single post pages. It assumes
	 * there is a page with the same slug as one of the post
	 * categories or post type, and if so it uses that page to
	 * build the menu.
	 */
	private function getAncestorsForSingle() {
		$page_titles_to_check = array();
		$post_parent = "";
		$post_type = get_post_type( $this->_curPost );
		if( $post_type == 'post' ) {
			$post_categories = wp_get_post_categories( $this->_curPost->ID, array('fields'=>'all') );
			foreach($post_categories as $cat) {
				$page_titles_to_check[] = $cat->name;
				// include category ancestors in titles to check
				if($cat->parent != 0) {
					$cat_ancestors = get_ancestors( $cat->term_id, 'category' );
					foreach($cat_ancestors as $cat_ancestor_id) {
						$cat_ancestor_obj = get_category( $cat_ancestor_id );
						$page_titles_to_check[] = $cat_ancestor_obj->name;
					}
				}
			}
		} else {
			global $wp_post_types;
			$post_type_obj = $wp_post_types[ $post_type ];
			$page_titles_to_check[] = $post_type->labels->singular_name;
		}
		
		// Search for pages with titles matching post categories or post type.
		// Stop after first match, and use that page as the ancestor.
		foreach($page_titles_to_check as $page_title_to_check) {
			$page_obj = get_page_by_title( $page_title_to_check );
			if( is_object($page_obj) )
				break;
		}
		if( is_object($page_obj) ) {
			$this->_curPost = $page_obj;
			return get_post_ancestors( $page_obj->ID );
		}
		return false;
	}
	
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
}