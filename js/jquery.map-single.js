(function($) {
	var log = false,
		last_window = null,
		mousemove_timer = false,
		coordinates = new google.maps.LatLng(map_data.latitude, map_data.longitude),
		lat_long_bounds = new google.maps.LatLngBounds(),
		map_style = [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
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

	function centerMap() {
		// console.log('Centering...');
		google.maps.event.trigger(map_object, 'resize');
		map_object.setCenter(marker.getPosition());
		last_window = info_window;
		map_object.setZoom(17);
	}

	map_data.latitude = parseFloat(map_data.latitude);
	map_data.longitude = parseFloat(map_data.longitude);

	var map_object = new google.maps.Map(document.getElementById('map'), {
		center: { lat: map_data.latitude, lng: map_data.longitude},
		zoom: 17,
		styles: map_style,
		scrollwheel: false,
		mapTypeControl: false
	});

	lat_long_bounds.extend(coordinates);

	var info_window = new InfoBox({
		content: '<div class="info-window"><div class="slide"><p>' + map_data.name + '</p><p><a href="tel:+1'+map_data.phone_number+'" class="white">'+map_data.phone_number.format_phone('dot')+'</a></p><p><a href="' + map_data.directions + '" class="cta text white" target="_blank">Directions</a></p><a class="close-info-box" href="javascript:close_info_box()"><i class="icon-close"></i></a></div></div>',
		disableAutoPan: true,
		maxWidth: 400,
		pixelOffset: new google.maps.Size(-0.5*92, -0.5*92),
		closeBoxURL: '',
		isHidden: false,
		pane: 'floatPane',
		enableEventPropagation: true,
		zIndex: 4,
	});

	var marker = new RichMarker({
		map: map_object,
		position: coordinates,
		draggable: false,
		flat: true,
		anchor: RichMarkerPosition.MIDDLE,
		content: '<div class="location-marker"><div class="circle"><div class="center-mark"></div></div></div>',
		visible: true,
		scaledSize: new google.maps.Size(92, 92),
	});
	info_window.marker = marker;

	(function (marker, info, map) {
		google.maps.event.addListener(marker, 'mouseover', function() {
			// if (log) console.log('mouseover', info);
			close_info_window(); 
			info.open(map, marker);
			last_window = info;
			mousemove_timer = setTimeout(function() {
				$('.info-window .slide').css('left', 0);
				// Prevent document mousemove event from firing until info window has had a chance to open
				setTimeout(function() {
					mousemove_timer = false;
				}, 300);
			}, 10);
		});
        /*google.maps.event.addListener(marker, 'mouseout', function() {
			deactivate_marker();
		});*/
	})(marker, info_window, map_object);

	// Set mouse X and Y position on mousemove
	$(document).on('mousemove', function(e) {
		// if (log) console.log('mousemove', last_window);
		if (!mousemove_timer && (last_window || false)) {
			last_window.close && last_window.close();
			last_window = null;
			mousemove_timer = false;
		}
	});

	// Standard event delegation
	$(document).on('mousemove', '.info-window', function(e) {
		// if (log) console.log('.info-window mouse MOVE!');
		e.stopPropagation();
	});

	$(window).on('resize', function() { centerMap(); });
	centerMap();

	window.close_info_window = function() {
		if (last_window !== null) {
			$('.info-window .slide').css('left', -200);
			setTimeout(function() {
				last_window.close();
				last_window = null;
			}, 300);
		}
	}
})(jQuery);
