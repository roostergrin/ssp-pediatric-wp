(function($) {
	let
		within_miles = [5, 10, 15, 20, 25, 30, 40, 50, 60],
		within_max_offices = 3,
		map_padding = $('body').hasClass('page-template-bio') ? 0 : 46,
		map_object = null,
		info_box = {
			opened: null,
			opening: false,
			entered: false
		},
		isIdle = false,
		coordinates = null,
		last_search_args = null,
		internal_marker = false,
		location_marker = 92,
		location_marker_small = 20,
		miles_to_meters = 1609.34,
		info_boxes = [],
		map_markers = [],
		active_offices = [],
		current_zoom = 0,
		initial = {
			searched: false,
			selected_location: map_data.selected_location || false,
			// panBy_x: $('body').hasClass('page-template-bio') ? 0 : -300,
			// panBy_y: $('body').hasClass('page-template-bio') ? 0 : -70,
			panBy_x: $('body').hasClass('page-template-bio') ? 0 : -100,
			panBy_y: $('body').hasClass('page-template-bio') ? 0 : -80,
			zoom: 7.5
		},
		mouse = {
			x: false,
			y: false,
			timer: false,
			moved: false,
			info_box_entered: false
		},
		lat_long_bounds = new google.maps.LatLngBounds(),
		geocoder = new google.maps.Geocoder,
		autocomplete = false,
		autocomplete_element = $('#location_search_address'),
		current_location = $('.current-location'),
		initial_coordinates = false,
		throttle_scroll_position = 0,
		is_throttling = false,
		interaction = false,
		pan_by_search = (map_data.pan_x_search || false)
	;

	function RichMarker(a){this.ready_=!1,this.dragging_=!1,a.visible==void 0&&(a.visible=!0),a.shadow==void 0&&(a.shadow='7px -3px 5px rgba(88,88,88,0.7)'),a.anchor==void 0&&(a.anchor=RichMarkerPosition.BOTTOM),this.setValues(a||{})}RichMarker.prototype=new google.maps.OverlayView,window.RichMarker=RichMarker,RichMarker.prototype.getVisible=function(){return this.get('visible')},RichMarker.prototype.getVisible=RichMarker.prototype.getVisible,RichMarker.prototype.setVisible=function(a){this.set('visible',a)},RichMarker.prototype.setVisible=RichMarker.prototype.setVisible,RichMarker.prototype.visible_changed=function(){this.ready_&&(this.markerWrapper_.style.display=this.getVisible()?'':'none',this.draw())},RichMarker.prototype.visible_changed=RichMarker.prototype.visible_changed,RichMarker.prototype.setFlat=function(a){this.set('flat',!!a)},RichMarker.prototype.setFlat=RichMarker.prototype.setFlat,RichMarker.prototype.getFlat=function(){return this.get('flat')},RichMarker.prototype.getFlat=RichMarker.prototype.getFlat,RichMarker.prototype.getWidth=function(){return this.get('width')},RichMarker.prototype.getWidth=RichMarker.prototype.getWidth,RichMarker.prototype.getHeight=function(){return this.get('height')},RichMarker.prototype.getHeight=RichMarker.prototype.getHeight,RichMarker.prototype.setShadow=function(a){this.set('shadow',a),this.flat_changed()},RichMarker.prototype.setShadow=RichMarker.prototype.setShadow,RichMarker.prototype.getShadow=function(){return this.get('shadow')},RichMarker.prototype.getShadow=RichMarker.prototype.getShadow,RichMarker.prototype.flat_changed=function(){this.ready_&&(this.markerWrapper_.style.boxShadow=this.markerWrapper_.style.webkitBoxShadow=this.markerWrapper_.style.MozBoxShadow=this.getFlat()?'':this.getShadow())},RichMarker.prototype.flat_changed=RichMarker.prototype.flat_changed,RichMarker.prototype.setZIndex=function(a){this.set('zIndex',a)},RichMarker.prototype.setZIndex=RichMarker.prototype.setZIndex,RichMarker.prototype.getZIndex=function(){return this.get('zIndex')},RichMarker.prototype.getZIndex=RichMarker.prototype.getZIndex,RichMarker.prototype.zIndex_changed=function(){this.getZIndex()&&this.ready_&&(this.markerWrapper_.style.zIndex=this.getZIndex())},RichMarker.prototype.zIndex_changed=RichMarker.prototype.zIndex_changed,RichMarker.prototype.getDraggable=function(){return this.get('draggable')},RichMarker.prototype.getDraggable=RichMarker.prototype.getDraggable,RichMarker.prototype.setDraggable=function(a){this.set('draggable',!!a)},RichMarker.prototype.setDraggable=RichMarker.prototype.setDraggable,RichMarker.prototype.draggable_changed=function(){this.ready_&&(this.getDraggable()?this.addDragging_(this.markerWrapper_):this.removeDragListeners_())},RichMarker.prototype.draggable_changed=RichMarker.prototype.draggable_changed,RichMarker.prototype.getPosition=function(){return this.get('position')},RichMarker.prototype.getPosition=RichMarker.prototype.getPosition,RichMarker.prototype.setPosition=function(a){this.set('position',a)},RichMarker.prototype.setPosition=RichMarker.prototype.setPosition,RichMarker.prototype.position_changed=function(){this.draw()},RichMarker.prototype.position_changed=RichMarker.prototype.position_changed,RichMarker.prototype.getAnchor=function(){return this.get('anchor')},RichMarker.prototype.getAnchor=RichMarker.prototype.getAnchor,RichMarker.prototype.setAnchor=function(a){this.set('anchor',a)},RichMarker.prototype.setAnchor=RichMarker.prototype.setAnchor,RichMarker.prototype.anchor_changed=function(){this.draw()},RichMarker.prototype.anchor_changed=RichMarker.prototype.anchor_changed,RichMarker.prototype.htmlToDocumentFragment_=function(a){var b=document.createElement('DIV');if(b.innerHTML=a,1==b.childNodes.length)return b.removeChild(b.firstChild);for(var c=document.createDocumentFragment();b.firstChild;)c.appendChild(b.firstChild);return c},RichMarker.prototype.removeChildren_=function(a){if(a)for(var b;b=a.firstChild;)a.removeChild(b)},RichMarker.prototype.setContent=function(a){this.set('content',a)},RichMarker.prototype.setContent=RichMarker.prototype.setContent,RichMarker.prototype.getContent=function(){return this.get('content')},RichMarker.prototype.getContent=RichMarker.prototype.getContent,RichMarker.prototype.content_changed=function(){if(this.markerContent_){this.removeChildren_(this.markerContent_);var a=this.getContent();if(a){'string'==typeof a&&(a=a.replace(/^\s*([\S\s]*)\b\s*$/,'$1'),a=this.htmlToDocumentFragment_(a)),this.markerContent_.appendChild(a);for(var f,b=this,c=this.markerContent_.getElementsByTagName('IMG'),d=0;f=c[d];d++)google.maps.event.addDomListener(f,'mousedown',function(g){b.getDraggable()&&(g.preventDefault&&g.preventDefault(),g.returnValue=!1)}),google.maps.event.addDomListener(f,'load',function(){b.draw()});google.maps.event.trigger(this,'domready')}this.ready_&&this.draw()}},RichMarker.prototype.content_changed=RichMarker.prototype.content_changed,RichMarker.prototype.setCursor_=function(a){if(this.ready_){var b='';-1===navigator.userAgent.indexOf('Gecko/')?(('dragging'==a||'dragready'==a)&&(b='move'),'draggable'==a&&(b='pointer')):('dragging'==a&&(b='-moz-grabbing'),'dragready'==a&&(b='-moz-grab'),'draggable'==a&&(b='pointer')),this.markerWrapper_.style.cursor!=b&&(this.markerWrapper_.style.cursor=b)}},RichMarker.prototype.startDrag=function(a){if(this.getDraggable()&&!this.dragging_){this.dragging_=!0;var b=this.getMap();this.mapDraggable_=b.get('draggable'),b.set('draggable',!1),this.mouseX_=a.clientX,this.mouseY_=a.clientY,this.setCursor_('dragready'),this.markerWrapper_.style.MozUserSelect='none',this.markerWrapper_.style.KhtmlUserSelect='none',this.markerWrapper_.style.WebkitUserSelect='none',this.markerWrapper_.unselectable='on',this.markerWrapper_.onselectstart=function(){return!1},this.addDraggingListeners_(),google.maps.event.trigger(this,'dragstart')}},RichMarker.prototype.stopDrag=function(){!this.getDraggable()||this.dragging_&&(this.dragging_=!1,this.getMap().set('draggable',this.mapDraggable_),this.mouseX_=this.mouseY_=this.mapDraggable_=null,this.markerWrapper_.style.MozUserSelect='',this.markerWrapper_.style.KhtmlUserSelect='',this.markerWrapper_.style.WebkitUserSelect='',this.markerWrapper_.unselectable='off',this.markerWrapper_.onselectstart=function(){},this.removeDraggingListeners_(),this.setCursor_('draggable'),google.maps.event.trigger(this,'dragend'),this.draw())},RichMarker.prototype.drag=function(a){if(!this.getDraggable()||!this.dragging_)return void this.stopDrag();var b=this.mouseX_-a.clientX,c=this.mouseY_-a.clientY;this.mouseX_=a.clientX,this.mouseY_=a.clientY;var d=parseInt(this.markerWrapper_.style.left,10)-b,f=parseInt(this.markerWrapper_.style.top,10)-c;this.markerWrapper_.style.left=d+'px',this.markerWrapper_.style.top=f+'px';var g=this.getOffset_(),h=new google.maps.Point(d-g.width,f-g.height),j=this.getProjection();this.setPosition(j.fromDivPixelToLatLng(h)),this.setCursor_('dragging'),google.maps.event.trigger(this,'drag')},RichMarker.prototype.removeDragListeners_=function(){this.draggableListener_&&(google.maps.event.removeListener(this.draggableListener_),delete this.draggableListener_),this.setCursor_('')},RichMarker.prototype.addDragging_=function(a){if(a){var b=this;this.draggableListener_=google.maps.event.addDomListener(a,'mousedown',function(c){b.startDrag(c)}),this.setCursor_('draggable')}},RichMarker.prototype.addDraggingListeners_=function(){var a=this;this.markerWrapper_.setCapture?(this.markerWrapper_.setCapture(!0),this.draggingListeners_=[google.maps.event.addDomListener(this.markerWrapper_,'mousemove',function(b){a.drag(b)},!0),google.maps.event.addDomListener(this.markerWrapper_,'mouseup',function(){a.stopDrag(),a.markerWrapper_.releaseCapture()},!0)]):this.draggingListeners_=[google.maps.event.addDomListener(window,'mousemove',function(b){a.drag(b)},!0),google.maps.event.addDomListener(window,'mouseup',function(){a.stopDrag()},!0)]},RichMarker.prototype.removeDraggingListeners_=function(){if(this.draggingListeners_){for(var b,a=0;b=this.draggingListeners_[a];a++)google.maps.event.removeListener(b);this.draggingListeners_.length=0}},RichMarker.prototype.getOffset_=function(){var a=this.getAnchor();if('object'==typeof a)return a;var b=new google.maps.Size(0,0);if(!this.markerContent_)return b;var c=this.markerContent_.offsetWidth,d=this.markerContent_.offsetHeight;switch(a){case RichMarkerPosition.TOP_LEFT:break;case RichMarkerPosition.TOP:b.width=-c/2;break;case RichMarkerPosition.TOP_RIGHT:b.width=-c;break;case RichMarkerPosition.LEFT:b.height=-d/2;break;case RichMarkerPosition.MIDDLE:b.width=-c/2,b.height=-d/2;break;case RichMarkerPosition.RIGHT:b.width=-c,b.height=-d/2;break;case RichMarkerPosition.BOTTOM_LEFT:b.height=-d;break;case RichMarkerPosition.BOTTOM:b.width=-c/2,b.height=-d;break;case RichMarkerPosition.BOTTOM_RIGHT:b.width=-c,b.height=-d;}return b},RichMarker.prototype.onAdd=function(){if(this.markerWrapper_||(this.markerWrapper_=document.createElement('DIV'),this.markerWrapper_.style.position='absolute'),this.getZIndex()&&(this.markerWrapper_.style.zIndex=this.getZIndex()),this.markerWrapper_.style.display=this.getVisible()?'':'none',!this.markerContent_){this.markerContent_=document.createElement('DIV'),this.markerWrapper_.appendChild(this.markerContent_);var a=this;google.maps.event.addDomListener(this.markerContent_,'click',function(c){google.maps.event.trigger(a,'click',c)}),google.maps.event.addDomListener(this.markerContent_,'mouseover',function(c){google.maps.event.trigger(a,'mouseover',c)}),google.maps.event.addDomListener(this.markerContent_,'mouseout',function(c){google.maps.event.trigger(a,'mouseout',c)})}this.ready_=!0,this.content_changed(),this.flat_changed(),this.draggable_changed();var b=this.getPanes();b&&b.overlayMouseTarget.appendChild(this.markerWrapper_),google.maps.event.trigger(this,'ready')},RichMarker.prototype.onAdd=RichMarker.prototype.onAdd,RichMarker.prototype.draw=function(){if(this.ready_&&!this.dragging_){var a=this.getProjection();if(a){var b=this.get('position'),c=a.fromLatLngToDivPixel(b),d=this.getOffset_();this.markerWrapper_.style.top=c.y+d.height+'px',this.markerWrapper_.style.left=c.x+d.width+'px';var f=this.markerContent_.offsetHeight,g=this.markerContent_.offsetWidth;g!=this.get('width')&&this.set('width',g),f!=this.get('height')&&this.set('height',f)}}},RichMarker.prototype.draw=RichMarker.prototype.draw,RichMarker.prototype.onRemove=function(){this.markerWrapper_&&this.markerWrapper_.parentNode&&this.markerWrapper_.parentNode.removeChild(this.markerWrapper_),this.removeDragListeners_()},RichMarker.prototype.onRemove=RichMarker.prototype.onRemove;var RichMarkerPosition={TOP_LEFT:1,TOP:2,TOP_RIGHT:3,LEFT:4,MIDDLE:5,RIGHT:6,BOTTOM_LEFT:7,BOTTOM:8,BOTTOM_RIGHT:9};window.RichMarkerPosition=RichMarkerPosition;

	String.prototype.format_phone = function(type) {
		switch (type ?? 'dot') {
			default:
			case 'dot':
				return this.replace(/(\d{3})(\d{3})(\d{4})/, '$1.$2.$3');
				break;
			case 'dash':
				return this.replace(/(\d{3})(\d{3})(\d{4})/, '$1.$2.$3');
				break;
			case 'parentheses':
				return this.replace(/(\d{3})(\d{3})(\d{4})/, '$1.$2.$3');
				break;
		}
	}

	function select_first_on_enter(input) {
		var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;
		function addEventListenerWrapper(type, listener) {
			if (type == "keydown") {
				var orig_listener = listener;
				listener = function(event) {
					var suggestion_selected = $(".pac-item-selected").length > 0;
					if (event.which == 13 && !suggestion_selected) {
						var simulated_downarrow = $.Event("keydown", {
							keyCode: 40,
							which: 40
						});
						orig_listener.apply(input, [simulated_downarrow]);
					}
					orig_listener.apply(input, [event]);
				};
			}
			_addEventListener.apply(input, [type, listener]);
		}
		if (input.addEventListener) {
			input.addEventListener = addEventListenerWrapper;
		} else if (input.attachEvent) {
			input.attachEvent = addEventListenerWrapper;
		}
	}
	
	function get_pan_x_from_search_box() {
		let selector = $('.widget.search .container');
		if(selector.length == 0) return 0;
		return $('.widget.search .container').outerWidth() + $('.widget.search .container').get(0).getBoundingClientRect().left - 0.5*$(window).width();
	}

	function get_distance(a, b) { return google.maps.geometry.spherical.computeDistanceBetween(a, b) / miles_to_meters; }

	function set_office_cookie(office_id) {
		set_data('user-office', office_id);
	}

	function get_office_cookie() {
		let office_id = get_data('user-office') || null;
		return (office_id === null ? null : parseInt(office_id));
	}

	function set_coordinate_cookie(lat, lng, query) {
		delete_data('user-office');
		query = query || '';

		set_data('user-coordinates', {
			lat: lat,
			lng: lng,
			query: query
		});
	}

	function get_coordinate_cookie() {
		let coords = get_data('user-coordinates');
		return (typeof coords === 'object' && coords !== null && coords.hasOwnProperty('lat') && coords.hasOwnProperty('lng') && coords.hasOwnProperty('query')) ? coords : null;
	}

	function geocode_autocomplete() {
		if (autocomplete_element.val().trim().length === 0) {
			default_view();
		} else {
			let addr,
				types = ['(cities)'];

			if ($.isNumeric(autocomplete_element.val())) {
				if (autocomplete_element.val().length > 2 && autocomplete_element.val().length < 6) types = ['(regions)'];
				addr = autocomplete_element.val();
			} else {
				addr = autocomplete_element.val()+' WI';
			}
			geocoder.geocode({
				'address': addr
			}, function(results, status) {
				if(status == 'OK') {
					let place = results[0];
					set_coordinate_cookie(place.geometry.location.lat(), place.geometry.location.lng(), autocomplete_element.val());
					on_location_search_success({
						lat: place.geometry.location.lat(),
						lng: place.geometry.location.lng()
					}, false);
				} else {
					on_location_search_error('Please try again by entering a valid city or ZIP code, or use the “Use Current Location” link. You may also browse all of our locations by state below.');
				}
			});
		}
	}

	// Autocomplete field place_changed event
	function autocomplete_place_changed() {
		let place = autocomplete.getPlace();
		if(typeof place !== 'undefined' && place.geometry) {
			autocomplete_element.val(place.name);

			set_coordinate_cookie(place.geometry.location.lat(), place.geometry.location.lng(), place.name);

			on_location_search_success({
				lat: place.geometry.location.lat(),
				lng: place.geometry.location.lng()
			}, false);

			if(window.dataLayer||false) window.dataLayer.push({event:'location-search'});

			// setTimeout(function() { autocomplete_element.blur(); }, 100);
		} else {
			geocode_autocomplete();
		}
	}

	function get_nearby_offices(offices, coordinates) {
		let nearby_offices = [];

		$.each(within_miles, function(a, distance) {
			$.each(offices, function(b, office) {
				let office_minimum_count = 3;
				if(window.location.hostname.indexOf('prairiegroveortho') > -1) office_minimum_count = 1;
				if(nearby_offices.length >= office_minimum_count) return nearby_offices.map(function(office) { delete office.added; });

				office.distance = null;

				let office_distance = get_distance(
					new google.maps.LatLng(office.latitude, office.longitude),
					new google.maps.LatLng(coordinates.lat, coordinates.lng)
				);

				if(office_distance <= distance) {
					office.distance = office_distance;
					if(!office.added) nearby_offices.push(office);
					office.added = true;
				}
			});
		});

		// nearby offices.filter(function(a) { return a.added; });
		$.each(nearby_offices, function(key, office) { delete office.added; });
		return nearby_offices;
	}

	function get_all_offices(offices, coordinates) {
		$.each(offices, function(b, office) {
			office.distance = null;

			let office_distance = get_distance(
				new google.maps.LatLng(office.latitude, office.longitude),
				new google.maps.LatLng(coordinates.lat, coordinates.lng)
			);

			if(office_distance <= distance) {
				office.distance = office_distance;
			}
		});

		return offices;
	}

	window.on_form_dependency_loaded = function() {
		if (navigator.geolocation) {
			$('.current-location:parent.hidden').removeClass('hidden');
			navigator.geolocation.getCurrentPosition(function(position) {
				set_coordinate_cookie(position.coords.latitude, position.coords.longitude, '');
				on_location_search_success({
					lat: position.coords.latitude,
					lng: position.coords.longitude
				}, true);
			},
			function(error) {

			});
		}
	}

	function clear_error() {
		$('.current-location').addClass('hidden').html('');
	}

	window.on_initial_search_coordinates = function(coordinates) {
		let urlParams = new URLSearchParams(window.location.search);
		return on_initial_search(map_data.offices, coordinates);
	}

	function is_external_url(url) {
		return false;
	}

	function sort_offices_by_distance(offices) {
		// Sort nearby offices by distance
		offices.sort(function(a, b) {
			return (+(a.distance > b.distance) || +(a.distance === b.distance) - 1);
		});
		return offices;
	}

	function scroll_to_find_location() {
		scrollToPosition($('section.locations-preview').offset().top - $('section.header').outerHeight());
	}

	window.on_location_search_error = function(message, close) {
		message = message ?? '';
		close = close ?? true;
		default_view();
		$('.office-results').html(message);
		if (close) {
			close_search();
		} else {
			$('a.current-location').replaceWith($('<p class="current-location">').text(message));
		}
	}

	window.on_location_search_success = function(coordinates, is_geolocation) {
		if(window.dataLayer||false) window.dataLayer.push({event:'location-search'});

		// Initialize
		let nearby_offices = get_nearby_offices(map_data.offices, coordinates),
			searched_office = is_geolocation ? 'you' : autocomplete_element.val();

		close_info_box();
		// autocomplete_element.val('');

		if(nearby_offices.length === 0) {
			if (is_geolocation) {
				delete_data('user-office');
				delete_data('user-coordinates');
			}
			on_location_search_error('No offices found near '+searched_office+'. Please search again.', false);
		} else {
			clear_error();
			on_nearby_offices_found(nearby_offices, coordinates);
			close_search();
			// $('.location-count').html(nearby_offices.length+' office'+(nearby_offices.length == 1 ? '' : 's')+' near '+searched_office);
			// $('.location-count').html(get_markers_in_bounds().length+' office'+(nearby_offices.length == 1 ? '' : 's')+' near '+searched_office);
		}

		last_search_args = {
			query: searched_office,
			coordinates: coordinates,
			is_geolocation: is_geolocation,
			is_initial_load: initial.searched
		};
	}

	function on_initial_search(offices, coordinates) {
		if(offices.length == 0) return false;
		offices = sort_offices_by_distance(offices);
		reset_markers();
		close_info_box();
		initial.searched = true;
		on_nearby_offices_found(offices, coordinates);
		last_search_args = {
			offices: offices,
			coordinates: coordinates
		};

		let query = 'you';
		if (autocomplete_element.val().length) query = autocomplete_element.val();
		$('.office-results').html('We found '+offices.length+' location'+(offices.length == 1 ? '' : 's')+' near '+query);
		autocomplete_element.closest('.container.active').removeClass('active');
		$('#nearby-locations > li').first().addClass('active');
		$('#office').val($('#nearby-locations > li').first().data('office'));
		// $('#find-location').addClass('hidden');
		$('#detected-office').removeClass('hidden');
		$('#detected-office > div').html($('#nearby-locations li.active').html());
		return true;
	}

	function on_nearby_offices_found(offices, coordinates) {
		offices = sort_offices_by_distance(offices);
		internal_marker.setPosition(new google.maps.LatLng(coordinates.lat , coordinates.lng));
		$('body').removeClass('default-map-view');
		update_markers(offices);


		let query = 'you',
			in_bounds = get_markers_in_bounds();
		if (autocomplete_element.val().length) query = autocomplete_element.val();
		autocomplete_element.closest('.container.active').removeClass('active');
		$('.office-results').html('We found '+offices.length+' location'+(offices.length == 1 ? '' : 's')+' near '+query);
		// $('.location-count').html()
	}

	function add_marker(marker) {
		map_markers.setMap(map_object);
	}

	function get_markers_in_bounds() {
		let bounds = map_object.getBounds(),
			in_bounds = [];

		for (i in map_markers) {
			if (bounds.contains(map_markers[i].getPosition())) {
				in_bounds.push(map_markers[i]);
			}
		}

		return in_bounds;
	}

	function get_markers_visible() {
		let markers = [];
		for (i in map_markers) {
			if (map_markers[i].visible) markers.push(map_markers[i]);
		}
		return markers;
	}

	function update_marker_colors(marker) {
		let marker_content = false,
			info_content = false;

		if ((marker || false) && marker.getContent) {
			let marker_content = $(marker.getContent()).toggleClass('disabled', !marker.active).get(0).outerHTML,
				info_content = $(marker.info.getContent()).toggleClass('disabled', !marker.active).get(0).outerHTML;
			// console.log('update_marker_colors - marker.setContent');
			marker.setContent(marker_content);
			// console.log('update_marker_colors - info.setContent');
			marker.info.setContent(info_content);
		} else {
			for (i in map_markers) {
				let marker_content = $(map_markers[i].getContent()).toggleClass('disabled', !map_markers[i].active).get(0).outerHTML,
					info_content = $(map_markers[i].info.getContent()).toggleClass('disabled', !map_markers[i].active).get(0).outerHTML;

				// console.log('update_marker_colors - map_markers[i].setContent');
				map_markers[i].setContent(marker_content);
				// console.log('update_marker_colors - map_markers[i].info.setContent');
				map_markers[i].info.setContent(info_content);
			}
		}
	}

	function update_marker_sizes(marker) {
		let small = map_object.getZoom() < 10,
			content = false;

		if ((marker || false) && marker.getContent) {
			content = $(marker.getContent());
			if (small) {
				content.addClass('small').find('[class^="circle"]').removeClass('circle').addClass('circle-small');
			} else {
				content.removeClass('small').find('[class^="circle"]').removeClass('circle-small').addClass('circle');
			}
			marker.setContent(content.get(0).outerHTML);
		} else {
			for (i in map_markers) {
				content = $(map_markers[i].getContent());
				if (small) {
					content.addClass('small').find('[class^="circle"]').removeClass('circle').addClass('circle-small');
				} else {
					content.removeClass('small').find('[class^="circle"]').removeClass('circle-small').addClass('circle');
				}
				map_markers[i].setContent(content.get(0).outerHTML);
			}
		}
	}

	window.open_info_windows = function() {
		// if (log) console.log('open_info_windows()', initial.searched);
		if (initial.searched) return;
		for (i in info_boxes) {
			// if (log) console.log('info_boxes['+i+'].start_opened', info_boxes[i].start_opened);
			if (!info_box.entered && info_boxes[i].start_opened) {
				open_info_box(info_boxes[i]);
				initial.searched = true;
			}
		}
		initial.searched = true;
	}

	function reset_markers(active) {
		active = active ?? false;
		// if (log) console.log('reset_markers()', map_markers);

		for (i in map_markers) {
			map_markers[i].active = active;
			update_marker_colors(map_markers[i]);
			map_markers[i].setVisible(true);
		}
	}

	function update_markers(offices) {
		reset_markers();
		if (!offices.length) return false;
		$('#'+map_data.selector).addClass('no-pointer-events');
		let bounds = new google.maps.LatLngBounds();
		if (info_box.opened !== null) close_info_box();

		// Toggle markers active/disabled state
		$.each(offices, function(k, v) {
			for (i in map_markers) {
				if (parseInt(map_markers[i].office_id) == parseInt(v.ID)) {
					map_markers[i].active = true;
					update_marker_colors(map_markers[i]);
				} else {
					map_markers[i].active = false;
				}
			}
			bounds.extend(new google.maps.LatLng(parseFloat(v.latitude), parseFloat(v.longitude)));
		});
		// update_marker_colors();

		// Reposition internal marker
		internal_marker.setVisible(true);
		// internal_marker.setPosition(bounds.getCenter());
		bounds.extend(internal_marker.getPosition());

		let boundPadding = (offices.length === 3 ? map_padding+46 : map_padding); // 46 = pin width (92) / 2
		if(isIdle) {
			map_object.fitBounds(bounds, boundPadding);
			map_object.setCenter(bounds.getCenter());
			if(map_object.getZoom() > 12) map_object.setZoom(12);
			if(window.location.hostname.indexOf('prairiegroveortho') > -1) google.maps.event.addListenerOnce(map_object, 'idle', function() {

			});
		}
		else google.maps.event.addListenerOnce(map_object, 'idle', function() {
			map_object.fitBounds(bounds, boundPadding);
			map_object.setCenter(bounds.getCenter());
			if(map_object.getZoom() > 12) map_object.setZoom(12);
			if(window.location.hostname.indexOf('prairiegroveortho') > -1) google.maps.event.addListenerOnce(map_object, 'idle', function() {
				if(map_object.getZoom() > 12) map_object.setZoom(12);
			});
		});

		$('.location-count').html('We found '+offices.length+' but '+get_markers_in_bounds().length+' are visible');
		google.maps.event.addListenerOnce(map_object, 'idle', function() {
			$('#'+map_data.selector).removeClass('no-pointer-events');
		});
	}

	function default_view() {
		reset_markers(true);
		$('body').addClass('default-map-view');
		if (info_box.opened !== null) close_info_box();
		internal_marker.setVisible(false);
		update_marker_sizes();

		if(isIdle) {
			map_object.setCenter(initial.bounds.getCenter());
			map_object.fitBounds(initial.bounds, initial.padding);
			$('#'+map_data.selector).removeClass('no-pointer-events');
		}
		else google.maps.event.addListenerOnce(map_object, 'idle', function() {
			map_object.setCenter(initial.bounds.getCenter());
			map_object.fitBounds(initial.bounds, initial.padding);
			$('#'+map_data.selector).removeClass('no-pointer-events');
		});
	}

	window.close_info_box = function() {
		if (info_box.opened !== null) {
			for(var i in map_markers) {
				$('.info-window .slide').css('left', -200);
			}
			info_box.opened.close();
			info_box.opened = null;
		}
	}

	function delete_markers() {
		for (m in map_markers) {
			map_markers[m].map(null);
			map_markers[m].info.map(null);
		}
	}

	function init_maps() {
		// if (log) console.log('init_maps()', map_data);

		// Initialize
		let offices = sort_offices_by_distance(map_data.offices);
		map_object = new google.maps.Map($('#'+map_data.selector)[0], {
			disablePanMomentum: true,
			scrollwheel: false,
			clickableIcons: false,
			styles: [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.land_parcel","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"administrative.locality","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.province","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}],
			mapTypeControl: false,
			streetViewControl: false,
			fullscreenControl: false,
			panControl: false,
			zoomControl: true,
			minZoom: null,
			maxZoom: 15,
			// disableDefaultUI: true,
			zoomControlOptions: {
				position: google.maps.ControlPosition.RIGHT_TOP
			}
		});
		$('#'+map_data.selector).data('map', map_object);

		map_object.addListener('idle', function() {
		    isIdle = true;
		});
		map_object.addListener('bounds_changed', function() {
		    isIdle = false;
		});

		// Zoom changed event for maps object
		map_object.addListener('zoom_changed', function() {
			if (current_zoom != map_object.getZoom()) {
				current_zoom = map_object.getZoom();
				$('#'+map_data.selector).removeClass(function(index, class_name) {
					return (class_name.match(/(^|\s)zoom-\S+/g) || []).join(' ');
				}).addClass('zoom-'+map_object.getZoom()).addClass('no-pointer-events');

				// Idle event to restore pointer-events on map after zoom/plot/etc. animation
				google.maps.event.addListenerOnce(map_object, 'idle', function() {
					$('#'+map_data.selector).removeClass('no-pointer-events');
					update_marker_sizes();
				});
			}
			if (!initial.hasOwnProperty('zoom')) initial.zoom = current_zoom;
			//if (log) console.log('zoom_changed()', current_zoom);
		});

		if (!initial.hasOwnProperty('center')) initial.center = map_object.getCenter();
		if (!initial.hasOwnProperty('panBy_x')) initial.panBy_x = -260;
		if (!initial.hasOwnProperty('panBy_y')) initial.panBy_y = -70;
		plot_markers(offices);
		window.map_markers = map_markers;
		window.info_boxes = info_boxes;
		window.map_object = map_object;
	}
	
	function coordinates_to_screen(coordinates) {
	  var topRight = map_object.getProjection().fromLatLngToPoint(map_object.getBounds().getNorthEast());
	  var bottomLeft = map_object.getProjection().fromLatLngToPoint(map_object.getBounds().getSouthWest());
	  var scale = Math.pow(2, map_object.getZoom());
	  var worldPoint = map_object.getProjection().fromLatLngToPoint(coordinates);
	  return new google.maps.Point((worldPoint.x - bottomLeft.x) * scale, (worldPoint.y - topRight.y) * scale);
	}

	function plot_markers(offices) {
		// Idle event to restore pointer-events on map after zoom/plot/etc. animation
		google.maps.event.addListenerOnce(map_object, 'idle', function() {
			$('#'+map_data.selector).removeClass('no-pointer-events');
		});

		// if (log) console.log('plot_markers(offices, initial.panBy_x, initial.panBy_y)', offices, initial.panBy_x, initial.panBy_y);
		if (!offices.length) return false;

		// Initialize
		let bounds = new google.maps.LatLngBounds(),
			small = offices.length > 3,
			marker_class = 'location-marker'+(small ? ' small' : ''),
			panBy_x = initial.panBy_x || 0,
			panBy_y = initial.panBy_y || 0,
			active_offices = initial.active || [];

		// Plot markers and info boxes from offices
		$.each(offices, function(k, v) {
			let coordinates = new google.maps.LatLng(parseFloat(v.latitude), parseFloat(v.longitude)),
				info_box_content = map_data.show_detail ? '<p><a href="tel:+1'+v.phone+'" class="white">'+v.phone.format_phone()+'</a></p>' : '<p><a href="/'+v.relative_url+'/" class="cta text white">View Details</a></p>',
				info_box_html = '<div class="info-window"><div class="slide" id="slide-'+v.ID+'"><a class="close-info-box" href="javascript:close_info_box()"><i class="icon-close"></i></a><p>'+v.post_title+'</p>'+info_box_content+'</div></div>';
			bounds.extend(coordinates);

			if ($(window).width() > 782) map_padding = 0;
			// if (log) console.log('plotting office:', v.post_title, parseFloat(v.latitude), parseFloat(v.longitude));
			// if (log) console.log('small', small);

			let info = new InfoBox({
				content: info_box_html,
				disableAutoPan: false,
				maxWidth: 400,
				pixelOffset: new google.maps.Size(-92/2, -92/2),
				closeBoxURL: '',
				isHidden: false,
				pane: 'floatPane',
				enableEventPropagation: true,
				zIndex: 4,
				office_id: parseInt(v.ID)
			});

			let marker = new RichMarker({
				map: map_object,
				position: coordinates,
				draggable: false,
				flat: true,
				anchor: RichMarkerPosition.MIDDLE,
				content: '<div data-office="'+parseInt(v.ID)+'" class="'+marker_class+'"><div class="circle-small"><div class="center-mark"></div></div></div>',
				visible: true,
				scaledSize: new google.maps.Size(20, 20),
				office_id: parseInt(v.ID)
			});

			marker.start_opened = (parseInt(v.ID) === parseInt(initial.selected_location));
			info.start_opened = (parseInt(v.ID) === parseInt(initial.selected_location));
			info.marker = marker;
			marker.info = info;
			map_markers.push(marker);
			info_boxes.push(info);

			(function(marker, info, map) {
				google.maps.event.addListener(marker, 'mouseover', function() {
					// if (log) console.log('mouseover', info);
					interaction = true;
					close_info_box();
					open_info_box(info);
					info_box.entered = true;
				});
			})(marker, info, map_object);
		});

		// Set map bounds from offices
		let boundPadding = (offices.length === 3 ? map_padding+46 : map_padding); // 46 = pin width (92) / 2
		if(isIdle) {
			map_object.fitBounds(bounds, boundPadding);
			map_object.setCenter(bounds.getCenter());
		}
		else google.maps.event.addListenerOnce(map_object, 'idle', function() {
			map_object.fitBounds(bounds, boundPadding);
			map_object.setCenter(bounds.getCenter());
		});

		if (!initial.hasOwnProperty('bounds')) initial.bounds = bounds;
		if (!initial.hasOwnProperty('padding')) initial.padding = boundPadding;

		// Plot internal marker at average center of offices plotted
		internal_marker = new RichMarker({
			map: map_object,
			position: bounds.getCenter(),
			draggable: false,
			flat: true,
			anchor: RichMarkerPosition.MIDDLE,
			content: '<div id="internal-marker" class="animating"></div><div id="internal-marker-effect"></div>',
			visible: false
		});
		internal_marker.setZIndex(800);

		// Initialize triggers
		if (initial_coordinates !== false) on_initial_search_coordinates(initial_coordinates);
		else if (on_form_dependency_loaded) on_form_dependency_loaded();
		
		window.map_object = map_object;

		if ($(window).width() > 782 && (panBy_x != 0 || panBy_y != 0)) {
			// if (log) console.log('map_object.panBy(panBy_x, panBy_y)', panBy_x, panBy_y);
			
			if(pan_by_search) {
				let maximum_lat = -180;
				let maximum_marker = null;
				for(var i in map_markers) {
					if(map_markers[i].getPosition().lat() > maximum_lat) {
						maximum_lat = map_markers[i].getPosition().lat();
						maximum_marker = map_markers[i];
					}
				}
				if(maximum_marker) {
					google.maps.event.addListenerOnce(map_object, 'idle', function() {
						let screen = coordinates_to_screen(maximum_marker.getPosition());
						panBy_x = $('.widget.search .container').outerWidth() + $('.widget.search .container').get(0).getBoundingClientRect().left - screen.x;
						map_object.panBy(-panBy_x - 80, panBy_y);
					});
					

					google.maps.event.addListenerOnce(map_object, 'idle', function() {
						let minimum_lat = 180;
						let minimum_marker = null;
						for(var i in map_markers) {
							if(map_markers[i].getPosition().lat() < minimum_lat) {
								minimum_lat = map_markers[i].getPosition().lat();
								minimum_marker = map_markers[i];
								
								let screen = coordinates_to_screen(minimum_marker.getPosition());
								if(screen.x > $(window).width() && map_markers.length < 6) {
									map_object.setZoom(map_object.getZoom() - 1);
								}
							}
						}
					});
				}
			}
			else {
				map_object.panBy(panBy_x, panBy_y);
			}
		}
		
		if((map_data.is_bio || false)) {
			google.maps.event.addListenerOnce(map_object, 'idle', function() {
				let maximum_lat = -180;
				let maximum_marker = null;
				for(var i in map_markers) {
					if(map_markers[i].getPosition().lat() > maximum_lat) {
						maximum_lat = map_markers[i].getPosition().lat();
						maximum_marker = map_markers[i];
					}
				}
				
				let minimum_lat = 180;
				let minimum_marker = null;
				for(var i in map_markers) {
					if(map_markers[i].getPosition().lat() < minimum_lat) {
						minimum_lat = map_markers[i].getPosition().lat();
						minimum_marker = map_markers[i];
					}
				}
				
				let zoom_map = false;
				if(maximum_marker) {
					let screen = coordinates_to_screen(maximum_marker.getPosition());
					
					if((screen.x + 240) > $(window).width()) {
						zoom_map = true;
					}
				}
				if(minimum_marker) {
					let screen = coordinates_to_screen(minimum_marker.getPosition());
					if((screen.x - 120) < $(window).width()) {
						zoom_map = true;
					}
				}
				
				if(minimum_marker && map_markers.length < 3) {
					map_object.setZoom(map_object.getZoom() - 1);
				}
			});
		}
		
		$(window).trigger('scroll');

		return true;
	}

	window.open_info_box = function(info, marker, map) {
		info = info ?? info_box.opened;
		marker = marker ?? info.marker;
		map = map ?? map_object;
		// if (log) console.log('open_info_box', info, marker, map);
		// Prevent document mousemove event from firing until info window has had a chance to open

		info_box.opening = true;
		setTimeout(function() {
			// info_box.opening = true;
			$('.info-window .slide').css('left', 0);
			// Prevent document mousemove event from firing until info window has had a chance to open
			setTimeout(function() {
				info_box.opened = info;
				info_box.opening = false;
			}, 400);
		}, 20);

		info.open && info.open(map, marker);
		$('.info-window .slide').css('left', 0);

		setTimeout(function() {
			info_box.opened = info;
			info_box.opening = false;
		}, 300);
	}

	window.close_info_box = function(info) {
		info = info ?? info_box.opened;
		$('.info-window .slide').css('left', -200);
		for (i in map_markers) {
			map_markers[i].info.close();
		}
		info_box.opened = null;
	}

	$(document).on('keydown', function(e) {
		if(e.keyCode == 27) close_info_box();
	});

	// Set mouse X and Y position on mousemove
	$(document).on('mousemove', function(e) {
		// if (log) console.log('mousemove', info_box.opened);
		if (!info_box.opening && info_box.entered && info_box.opened) {
			info_box.opened.close();
			info_box.opened = null;
			info_box.opening = false;
		}
		mouse.x = e.clientX;
		mouse.y = e.clientY;
	});

	// Standard event delegation
	$(document).on('mousemove', '.info-window', function(e) {
		// if (log) console.log('.info-window mouse MOVE!');
		info_box.entered = true;
		info_box.opening = false;
		interaction = true;
		e.stopPropagation();
	});

	// Intialize map and autocomplete
	if($('#'+map_data.selector).length) {
		window.addEventListener('load', function() {
			init_maps();

			if (autocomplete_element.length) {
				autocomplete = new google.maps.places.Autocomplete(autocomplete_element.get(0), {
					types: ['(regions)'],
					componentRestrictions: {
						country: 'US'
					}
				});
				autocomplete.addListener('place_changed', autocomplete_place_changed);
			}
		});
	}

	$(window).on('scroll', function(e) {
		throttle_scroll_position = $(window).scrollTop();
		if(!is_throttling) {
			window.requestAnimationFrame(function() {
				//updateScrollPosition();
				is_throttling = false;
			});
		}

		is_throttling = true;
	});

	if (navigator.geolocation) {
		$('.current-location:parent.hidden').removeClass('hidden');
	}
	select_first_on_enter(autocomplete_element[0]);

	// Find a location click
	autocomplete_element.closest('.widget.search').on('click', function() {
		if (!$(this).hasClass('active')) $(this).addClass('active');
	});

	// Toggle search-string class when value is present in search field to prevent animation on hover out
	autocomplete_element.on('keyup', function() {
		if (!$(this).closest('.widget.search').hasClass('search-string')) {
			$(this).closest('.widget.search').toggleClass('search-string', $(this).val().trim().length);
		} else if (!$(this).val().trim().length) {
			$(this).closest('.widget.search').removeClass('search-string');
		}
	});

	// Find a location focus outline = 'inherit'
	autocomplete_element.on('blur', function() {
		if ($(this).val().trim().length) {
			$(this).closest('.widget.search.active').removeClass('active');
		}
	});

	// Find a location form submit
	autocomplete_element.closest('form').on('submit', function(e) {
		e.preventDefault();
		// if (log) console.log('autocomplete_element.submit()');
		autocomplete_element.closest('.widget.search').addClass('searched');
		$('#'+map_data.selector).addClass('no-pointer-events');
		if (last_search_args === null || last_search_args.query != autocomplete_element.val().trim()) {
			geocode_autocomplete();
		}
		if (autocomplete_element.val().trim().length) close_search();
	});

	function close_search() {
		if (autocomplete_element.closest('.widget.search').hasClass('active')) {
			autocomplete_element.closest('.widget.search').addClass('animating').removeClass('active');
			setTimeout(function() {
				autocomplete_element.closest('.widget.search').removeClass('animating');
				$('#'+map_data.selector).removeClass('no-pointer-events');
			}, 300);
		}
	}

	// Use current location click
	current_location.on('click', function(e) {
		e.preventDefault();

		navigator.geolocation.getCurrentPosition(function(position) {
			set_coordinate_cookie(position.coords.latitude, position.coords.longitude, '');
			on_location_search_success({
				lat: position.coords.latitude,
				lng: position.coords.longitude
			}, true);
		},
		function(error) {
			on_location_search_error('We were unable to find your current location. Please enter an address, city, or ZIP code.');
		});
	});
	window.max_scroll_progress = 0;

	function get_marker_offset(map, marker) {
		map = map ?? map_object;
		marker = marker ?? map_markers.filter(function(a) { return a.start_opened; })[0];
		if (marker.getPosition === undefined || map.getBounds() === undefined) return false;
		let scale = Math.pow(2, map.getZoom()),
			nw = new google.maps.LatLng(
				map.getBounds().getNorthEast().lat(),
				map.getBounds().getSouthWest().lng()
			),
			world_nw = map.getProjection().fromLatLngToPoint(nw),
			world = map.getProjection().fromLatLngToPoint(marker.getPosition()),
			point = new google.maps.Point(
				Math.floor((world.x - world_nw.x) * scale),
				Math.floor((world.y - world_nw.y) * scale)
			);
		return {
			x: Math.floor((world.x - world_nw.x) * scale),
			y: Math.floor((world.y - world_nw.y) * scale)
		};
	}

	let scroll_position = $(window).scrollTop();
	$(window).on('scroll', function() {
		let marker = map_markers.filter(function(a) { return a.start_opened; })[0];
		if (marker === undefined) return;
		let marker_offset = get_marker_offset(map_object, marker),
			marker_height = map_object.getZoom() > 9 ? 92 : 20,
			marker_top = $('#'+map_data.selector).offset().top + marker_offset.y,
			marker_btm = marker_top + marker_height,
			win_top = $(window).scrollTop(),
			win_btm = $(window).scrollTop() + $(window).height(),
			direction = $(window).scrollTop() > scroll_position ? 'down' : 'up';
		if (
			(win_top <= marker_btm && win_btm >= marker_top && direction == 'up')
			|| (win_btm >= marker_top && win_top <= marker_btm && direction == 'down')
		) {
			marker.scrolled_in = true;
			open_info_windows();
		} else if (!info_box.entered && marker.scrolled_in && marker.info.getVisible()) {
			delete marker.scrolled_in;
			initial.searched = false;
			close_info_box(marker.info);
		}
	});

	if (window.Stickyfill || null) Stickyfill.add($('#'+map_data.selector));
})(jQuery);
