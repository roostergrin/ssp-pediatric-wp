(function($) {
	var
		loc_lat = 26.3683864,
		loc_lng = -80.1254578,
		loc_zoom = 17,
		last_window = null,
		coordinates = new google.maps.LatLng(loc_lat, loc_lng),
		lat_long_bounds = new google.maps.LatLngBounds(),
        locations = [
            ['Baldwin', 26.3683664, -80.1254578, 4],
			['New Richmond', 26.3673664, -80.1264578, 5],
			['Rice Lake', 26.3663664, -80.1244578, 3],
            ['Black River Falls',26.3673464, -80.1300310, 1]
        ];

    map_style = [
			{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"landscape.natural.terrain","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi","stylers":[{"visibility":"off"}]},
			{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.attraction","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.attraction","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.business","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.government","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.government","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.medical","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.place_of_worship","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.place_of_worship","elementType":"labels.icon","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.place_of_worship","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.school","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.school","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"poi.sports_complex","elementType":"labels.icon","stylers":[{"color":"#8e959c"}]},
			{"featureType":"poi.sports_complex","elementType":"labels.text.fill","stylers":[{"color":"#8e959c"}]},
			{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#ffffff"}]},
			{"featureType":"transit.station","stylers":[{"visibility":"off"}]},
			{"featureType":"transit.station","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"transit.station.bus","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"transit.station.rail","elementType":"geometry.fill","stylers":[{"color":"#f6f6f6"}]},
			{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#9ba1aa"}]}
		]
	;

	function RichMarker(a){this.ready_=!1,this.dragging_=!1,a.visible==void 0&&(a.visible=!0),a.shadow==void 0&&(a.shadow='7px -3px 5px rgba(88,88,88,0.7)'),a.anchor==void 0&&(a.anchor=RichMarkerPosition.BOTTOM),this.setValues(a||{})}RichMarker.prototype=new google.maps.OverlayView,window.RichMarker=RichMarker,RichMarker.prototype.getVisible=function(){return this.get('visible')},RichMarker.prototype.getVisible=RichMarker.prototype.getVisible,RichMarker.prototype.setVisible=function(a){this.set('visible',a)},RichMarker.prototype.setVisible=RichMarker.prototype.setVisible,RichMarker.prototype.visible_changed=function(){this.ready_&&(this.markerWrapper_.style.display=this.getVisible()?'':'none',this.draw())},RichMarker.prototype.visible_changed=RichMarker.prototype.visible_changed,RichMarker.prototype.setFlat=function(a){this.set('flat',!!a)},RichMarker.prototype.setFlat=RichMarker.prototype.setFlat,RichMarker.prototype.getFlat=function(){return this.get('flat')},RichMarker.prototype.getFlat=RichMarker.prototype.getFlat,RichMarker.prototype.getWidth=function(){return this.get('width')},RichMarker.prototype.getWidth=RichMarker.prototype.getWidth,RichMarker.prototype.getHeight=function(){return this.get('height')},RichMarker.prototype.getHeight=RichMarker.prototype.getHeight,RichMarker.prototype.setShadow=function(a){this.set('shadow',a),this.flat_changed()},RichMarker.prototype.setShadow=RichMarker.prototype.setShadow,RichMarker.prototype.getShadow=function(){return this.get('shadow')},RichMarker.prototype.getShadow=RichMarker.prototype.getShadow,RichMarker.prototype.flat_changed=function(){this.ready_&&(this.markerWrapper_.style.boxShadow=this.markerWrapper_.style.webkitBoxShadow=this.markerWrapper_.style.MozBoxShadow=this.getFlat()?'':this.getShadow())},RichMarker.prototype.flat_changed=RichMarker.prototype.flat_changed,RichMarker.prototype.setZIndex=function(a){this.set('zIndex',a)},RichMarker.prototype.setZIndex=RichMarker.prototype.setZIndex,RichMarker.prototype.getZIndex=function(){return this.get('zIndex')},RichMarker.prototype.getZIndex=RichMarker.prototype.getZIndex,RichMarker.prototype.zIndex_changed=function(){this.getZIndex()&&this.ready_&&(this.markerWrapper_.style.zIndex=this.getZIndex())},RichMarker.prototype.zIndex_changed=RichMarker.prototype.zIndex_changed,RichMarker.prototype.getDraggable=function(){return this.get('draggable')},RichMarker.prototype.getDraggable=RichMarker.prototype.getDraggable,RichMarker.prototype.setDraggable=function(a){this.set('draggable',!!a)},RichMarker.prototype.setDraggable=RichMarker.prototype.setDraggable,RichMarker.prototype.draggable_changed=function(){this.ready_&&(this.getDraggable()?this.addDragging_(this.markerWrapper_):this.removeDragListeners_())},RichMarker.prototype.draggable_changed=RichMarker.prototype.draggable_changed,RichMarker.prototype.getPosition=function(){return this.get('position')},RichMarker.prototype.getPosition=RichMarker.prototype.getPosition,RichMarker.prototype.setPosition=function(a){this.set('position',a)},RichMarker.prototype.setPosition=RichMarker.prototype.setPosition,RichMarker.prototype.position_changed=function(){this.draw()},RichMarker.prototype.position_changed=RichMarker.prototype.position_changed,RichMarker.prototype.getAnchor=function(){return this.get('anchor')},RichMarker.prototype.getAnchor=RichMarker.prototype.getAnchor,RichMarker.prototype.setAnchor=function(a){this.set('anchor',a)},RichMarker.prototype.setAnchor=RichMarker.prototype.setAnchor,RichMarker.prototype.anchor_changed=function(){this.draw()},RichMarker.prototype.anchor_changed=RichMarker.prototype.anchor_changed,RichMarker.prototype.htmlToDocumentFragment_=function(a){var b=document.createElement('DIV');if(b.innerHTML=a,1==b.childNodes.length)return b.removeChild(b.firstChild);for(var c=document.createDocumentFragment();b.firstChild;)c.appendChild(b.firstChild);return c},RichMarker.prototype.removeChildren_=function(a){if(a)for(var b;b=a.firstChild;)a.removeChild(b)},RichMarker.prototype.setContent=function(a){this.set('content',a)},RichMarker.prototype.setContent=RichMarker.prototype.setContent,RichMarker.prototype.getContent=function(){return this.get('content')},RichMarker.prototype.getContent=RichMarker.prototype.getContent,RichMarker.prototype.content_changed=function(){if(this.markerContent_){this.removeChildren_(this.markerContent_);var a=this.getContent();if(a){'string'==typeof a&&(a=a.replace(/^\s*([\S\s]*)\b\s*$/,'$1'),a=this.htmlToDocumentFragment_(a)),this.markerContent_.appendChild(a);for(var f,b=this,c=this.markerContent_.getElementsByTagName('IMG'),d=0;f=c[d];d++)google.maps.event.addDomListener(f,'mousedown',function(g){b.getDraggable()&&(g.preventDefault&&g.preventDefault(),g.returnValue=!1)}),google.maps.event.addDomListener(f,'load',function(){b.draw()});google.maps.event.trigger(this,'domready')}this.ready_&&this.draw()}},RichMarker.prototype.content_changed=RichMarker.prototype.content_changed,RichMarker.prototype.setCursor_=function(a){if(this.ready_){var b='';-1===navigator.userAgent.indexOf('Gecko/')?(('dragging'==a||'dragready'==a)&&(b='move'),'draggable'==a&&(b='pointer')):('dragging'==a&&(b='-moz-grabbing'),'dragready'==a&&(b='-moz-grab'),'draggable'==a&&(b='pointer')),this.markerWrapper_.style.cursor!=b&&(this.markerWrapper_.style.cursor=b)}},RichMarker.prototype.startDrag=function(a){if(this.getDraggable()&&!this.dragging_){this.dragging_=!0;var b=this.getMap();this.mapDraggable_=b.get('draggable'),b.set('draggable',!1),this.mouseX_=a.clientX,this.mouseY_=a.clientY,this.setCursor_('dragready'),this.markerWrapper_.style.MozUserSelect='none',this.markerWrapper_.style.KhtmlUserSelect='none',this.markerWrapper_.style.WebkitUserSelect='none',this.markerWrapper_.unselectable='on',this.markerWrapper_.onselectstart=function(){return!1},this.addDraggingListeners_(),google.maps.event.trigger(this,'dragstart')}},RichMarker.prototype.stopDrag=function(){!this.getDraggable()||this.dragging_&&(this.dragging_=!1,this.getMap().set('draggable',this.mapDraggable_),this.mouseX_=this.mouseY_=this.mapDraggable_=null,this.markerWrapper_.style.MozUserSelect='',this.markerWrapper_.style.KhtmlUserSelect='',this.markerWrapper_.style.WebkitUserSelect='',this.markerWrapper_.unselectable='off',this.markerWrapper_.onselectstart=function(){},this.removeDraggingListeners_(),this.setCursor_('draggable'),google.maps.event.trigger(this,'dragend'),this.draw())},RichMarker.prototype.drag=function(a){if(!this.getDraggable()||!this.dragging_)return void this.stopDrag();var b=this.mouseX_-a.clientX,c=this.mouseY_-a.clientY;this.mouseX_=a.clientX,this.mouseY_=a.clientY;var d=parseInt(this.markerWrapper_.style.left,10)-b,f=parseInt(this.markerWrapper_.style.top,10)-c;this.markerWrapper_.style.left=d+'px',this.markerWrapper_.style.top=f+'px';var g=this.getOffset_(),h=new google.maps.Point(d-g.width,f-g.height),j=this.getProjection();this.setPosition(j.fromDivPixelToLatLng(h)),this.setCursor_('dragging'),google.maps.event.trigger(this,'drag')},RichMarker.prototype.removeDragListeners_=function(){this.draggableListener_&&(google.maps.event.removeListener(this.draggableListener_),delete this.draggableListener_),this.setCursor_('')},RichMarker.prototype.addDragging_=function(a){if(a){var b=this;this.draggableListener_=google.maps.event.addDomListener(a,'mousedown',function(c){b.startDrag(c)}),this.setCursor_('draggable')}},RichMarker.prototype.addDraggingListeners_=function(){var a=this;this.markerWrapper_.setCapture?(this.markerWrapper_.setCapture(!0),this.draggingListeners_=[google.maps.event.addDomListener(this.markerWrapper_,'mousemove',function(b){a.drag(b)},!0),google.maps.event.addDomListener(this.markerWrapper_,'mouseup',function(){a.stopDrag(),a.markerWrapper_.releaseCapture()},!0)]):this.draggingListeners_=[google.maps.event.addDomListener(window,'mousemove',function(b){a.drag(b)},!0),google.maps.event.addDomListener(window,'mouseup',function(){a.stopDrag()},!0)]},RichMarker.prototype.removeDraggingListeners_=function(){if(this.draggingListeners_){for(var b,a=0;b=this.draggingListeners_[a];a++)google.maps.event.removeListener(b);this.draggingListeners_.length=0}},RichMarker.prototype.getOffset_=function(){var a=this.getAnchor();if('object'==typeof a)return a;var b=new google.maps.Size(0,0);if(!this.markerContent_)return b;var c=this.markerContent_.offsetWidth,d=this.markerContent_.offsetHeight;switch(a){case RichMarkerPosition.TOP_LEFT:break;case RichMarkerPosition.TOP:b.width=-c/2;break;case RichMarkerPosition.TOP_RIGHT:b.width=-c;break;case RichMarkerPosition.LEFT:b.height=-d/2;break;case RichMarkerPosition.MIDDLE:b.width=-c/2,b.height=-d/2;break;case RichMarkerPosition.RIGHT:b.width=-c,b.height=-d/2;break;case RichMarkerPosition.BOTTOM_LEFT:b.height=-d;break;case RichMarkerPosition.BOTTOM:b.width=-c/2,b.height=-d;break;case RichMarkerPosition.BOTTOM_RIGHT:b.width=-c,b.height=-d;}return b},RichMarker.prototype.onAdd=function(){if(this.markerWrapper_||(this.markerWrapper_=document.createElement('DIV'),this.markerWrapper_.style.position='absolute'),this.getZIndex()&&(this.markerWrapper_.style.zIndex=this.getZIndex()),this.markerWrapper_.style.display=this.getVisible()?'':'none',!this.markerContent_){this.markerContent_=document.createElement('DIV'),this.markerWrapper_.appendChild(this.markerContent_);var a=this;google.maps.event.addDomListener(this.markerContent_,'click',function(c){google.maps.event.trigger(a,'click',c)}),google.maps.event.addDomListener(this.markerContent_,'mouseover',function(c){google.maps.event.trigger(a,'mouseover',c)}),google.maps.event.addDomListener(this.markerContent_,'mouseout',function(c){google.maps.event.trigger(a,'mouseout',c)})}this.ready_=!0,this.content_changed(),this.flat_changed(),this.draggable_changed();var b=this.getPanes();b&&b.overlayMouseTarget.appendChild(this.markerWrapper_),google.maps.event.trigger(this,'ready')},RichMarker.prototype.onAdd=RichMarker.prototype.onAdd,RichMarker.prototype.draw=function(){if(this.ready_&&!this.dragging_){var a=this.getProjection();if(a){var b=this.get('position'),c=a.fromLatLngToDivPixel(b),d=this.getOffset_();this.markerWrapper_.style.top=c.y+d.height+'px',this.markerWrapper_.style.left=c.x+d.width+'px';var f=this.markerContent_.offsetHeight,g=this.markerContent_.offsetWidth;g!=this.get('width')&&this.set('width',g),f!=this.get('height')&&this.set('height',f)}}},RichMarker.prototype.draw=RichMarker.prototype.draw,RichMarker.prototype.onRemove=function(){this.markerWrapper_&&this.markerWrapper_.parentNode&&this.markerWrapper_.parentNode.removeChild(this.markerWrapper_),this.removeDragListeners_()},RichMarker.prototype.onRemove=RichMarker.prototype.onRemove;var RichMarkerPosition={TOP_LEFT:1,TOP:2,TOP_RIGHT:3,LEFT:4,MIDDLE:5,RIGHT:6,BOTTOM_LEFT:7,BOTTOM:8,BOTTOM_RIGHT:9};window.RichMarkerPosition=RichMarkerPosition;

	function deactivate_marker() {
		if (last_window !== null) {
			$('.info-window .slide').css('left', -200);
			setTimeout(function() {
				last_window.close();
				last_window = null;
			}, 300);
		}
	}

	function centerMap() {
		// console.log('Centering...');
		google.maps.event.trigger(map_object, 'resize');
		map_object.setCenter(marker.getPosition());
		last_window = info_window;
		map_object.setZoom(loc_zoom);
	}

	var map_object = new google.maps.Map(document.getElementById('map'), {
		center: { lat: loc_lat, lng: loc_lng},
		zoom: loc_zoom,
		styles: map_style,
		scrollwheel: false,
		mapTypeControl: false
	});

    for (i = 0; i < locations.length; i++) {
    	var lat_long = new google.maps.LatLng(locations[i][1], locations[i][2]);
        lat_long_bounds.extend(lat_long);

		var info_window = new InfoBox({
			content: '<div class="info-window"><div class="slide"><p>'+locations[i][0]+'</p><p><a href="#" class="cta text">View info</a></p><i class="icon-close" onclick="return close_info_window();"></i></div></div>',
			disableAutoPan: true,
			maxWidth: 400,
			pixelOffset: new google.maps.Size(-0.5*92, -0.5*92),
			closeBoxURL: '',
			isHidden: false,
			pane: "floatPane",
			enableEventPropagation: false,
			zIndex: 4,
		});
    }


	var marker;
    for (i = 0; i < locations.length; i++) {
        marker = new RichMarker({
            map: map_object,
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            draggable: false,
            flat: true,
            anchor: RichMarkerPosition.MIDDLE,
            content: '<div class="location-marker"><div class="circle"><div class="center-mark"></div></div></div>',
            visible: true,
            scaledSize: new google.maps.Size(92, 92),
        });
    };

	info_window.marker = marker;

	(function (marker, infowindow, map) {
		google.maps.event.addListener(marker, 'click', function() {
			info_window.open(map, marker);
			last_window = infowindow;
			setTimeout(function() {
				$('.info-window .slide').css('left', 0);
			}, 10);
		});
	})(marker, info_window, map_object);

	$(window).on('resize', function() { centerMap(); });
	centerMap();

	window.close_info_window = function() {
		deactivate_marker();
	}
})(jQuery);
