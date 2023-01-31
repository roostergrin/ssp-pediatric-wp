<?
class VirtualTours
{
	public
		$is_rebuilding = false,
		$brands = null,

		$___ ## DUMMY ##
	;

    public function __construct() {
        $this->registerACF();
    }

    private function registerACF() {
		////////////////////////////// POST TYPE FIELDS //////////////////////////////
        if(function_exists('acf_add_local_field_group')) acf_add_local_field_group([
            'key' => 'virtual_tours_settings',
			'title' => 'Virtual Tour Settings',
			'fields' => [
                [
                    'key'   => 'virtual_tour_embed_source',
					'name'  => 'virtual_tour_embed_source',
					'label' => 'Virtual Tour Embed Source',
					'type' => 'wysiwyg',
                    'toolbar' => 'simple',
                    'media_upload' => 0,
                ]
            ],
            'location' => [
                [[
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'templates/virtual-tour.php',
                ]],
            ],
        ]);
    }
}