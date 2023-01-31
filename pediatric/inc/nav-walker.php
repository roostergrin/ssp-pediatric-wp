<?
// Create ADA-friendly submenu + elements for mobile
class ADA_Mobile extends Walker_Nav_Menu {

	public function start_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\n";
		$output .= '<ul class="sub-menu">';
	}

	public function end_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\n";
		$output .= '</ul>';
	}

	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		// Initialize
		$has_children = in_array('menu-item-has-children', $item->classes);
		$indent = $depth ? str_repeat("\t", $depth) : '';
		$output .= $indent;

		// Class
		$class = empty($item->classes) ? [] : array_filter($item->classes);
		$class[] = 'menu-item-'.$item->ID;
		$class = implode(' ', apply_filters('nav_menu_css_class', $class, $item, $args));
		$class = empty($class) ? '' : ' class="'.esc_attr($class).'"';

		// ID
		$id = apply_filters('nav_menu_item_id', 'menu-item'.$item->ID, $item, $args);
		$id = $id ? ' id="'.esc_attr($id).'"' : '';

		// Attributes
		$attributes = empty($item->attr_title) ? '' : ' title="'.esc_attr($item->attr_title).'"';
		$attributes .= empty($item->target) ? '' : ' target="'.esc_attr($item->target).'"';
		$attributes .= empty($item->xfn) ? '' : ' rel="'.esc_attr($item->xfn).'"';
		$attributes .= empty($item->url) ? '' : ' href="'.esc_attr($item->url).'"';

		// HTML
		if ($has_children) {
			if ($depth === 0) {
				// Top-level
				$html = $args->before;
				$html .= '<li'.$class.'>';
				$html .= '<a'.$attributes.'>';
				$html .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
				$html .= '<span class="dropdown mobile icon-plus" aria-disabled="true"></span>';
				$html .= '</a>';
				$html .= $args->after;
			} else {
				// The rest
				$html = $args->before;
				$html .= '<li'.$class.'>';
				$html .= '<a'.$attributes.'>';
				$html .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
				$html .= '</a>';
				$html .= $args->after;
			}
		} else {
			// The rest
			$html = $args->before;
			$html .= '<li'.$class.'>';
			$html .= '<a'.$attributes.'>';
			$html .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
			$html .= '</a>';
			$html .= $args->after;
		}

		$output .= apply_filters('walker_nav_menu_start_el', $html, $item, $depth, $args);
	}

	public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$has_children = in_array('menu-item-has-children', $item->classes) ? true : false;
		$output .= "</li>\n";
	}
}

// Create plain list of <a> tags from a nav menu
class Plain_Links extends Walker_Nav_Menu {

	public function start_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\n";
	}

	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		// Initialize
		$has_children = in_array('menu-item-has-children', $item->classes);
		$indent = $depth ? str_repeat("\t", $depth) : '';
		$output .= $indent;

		// Class
		$class = empty($item->classes) ? [] : $item->classes;
		$class[] = 'menu-item-'.$item->ID;
		$class = implode(' ', apply_filters('nav_menu_css_class', array_filter($class), $item, $args));
		$class = empty($class) ? '' : ' class="'.esc_attr($class).'"';

		// ID
		$id = apply_filters('nav_menu_item_id', 'menu-item'.$item->ID, $item, $args);
		$id = $id ? ' id="'.esc_attr($id).'"' : '';

		// Attributes
		$attributes = empty($item->attr_title) ? '' : ' title="'.esc_attr($item->attr_title).'"';
		$attributes .= empty($item->target) ? '' : ' target="'.esc_attr($item->target).'"';
		$attributes .= empty($item->xfn) ? '' : ' rel="'.esc_attr($item->xfn).'"';
		$attributes .= empty($item->url) ? '' : ' href="'.esc_attr($item->url).'"';

		// HTML
		$html = $args->before;
		$html .= '<a'.$attributes.'>';
		$html .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
		if ($has_children) $html .= '<span class="dropdown mobile icon-plus"></span>';
		$html .= '</a>';
		$html .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $html, $item, $depth, $args);

		if (0 == $depth && $has_children) {
			$output .= '<li class="'.implode(' ', $item->classes).'"><a href="'.$item->url.'" title="'.esc_attr($item->title).'">'.strip_tags($item->title)."<span class=\"dropdown icon-plus\"></span></a>\n";
		}
	}

	public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$output .= "\n";
	}
}