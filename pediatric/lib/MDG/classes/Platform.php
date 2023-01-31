<?
namespace MDG;

class Platform {
	
	public function __construct() {
		$this->action_hooks();
	}

	private function action_hooks() {
		add_action('mu_plugins_loaded', function() {
			global $platform_entity = \Platform\Object\Entity::create($lead->sourceEntityID);
		});
	}

}