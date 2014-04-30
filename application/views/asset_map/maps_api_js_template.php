    <style type="text/css">
/*      #map_canvas {
          width: 100%;
         height: 100%;
       position: absolute; 
            top: px; 
           left: 0; 
          right: 0;
         bottom: 0; 
        z-index: 1; 
       overflow: hidden; 
      }*/
    </style>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=&sensor=true">
    </script>
    
    <!-- load the google maps visualization library -->
    <script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?libraries=visualization&sensor=false">
    </script>
    
    <script type="text/javascript">
        <?php
          if(isset($providers))
          {
              // parse out the provider information into the appropriate strings needed to set Google Map markers
                $locations = array();
          
                foreach($providers as $key => $provider)
                {
                      $name = $provider['name_ofc'];
                       $lat = $provider['latitude_ofc'];
                       $lng = $provider['longitude_ofc'];
                    $street = $provider['street_ofc']."<br>".$provider['city_ofc']." ".$provider['state_ofc']." ".$provider['zipcode_ofc'];
                     $phone = $provider['phone_ofc'];

                     if(isset($provider['email_ofc']))
                     {
                         $email = $provider['email_ofc'];
                     }
                     else
                     {
                         $email = '&nbsp;';
                     }
                     //build the locations string to be inserted into the javascript function
                     $locations[] = "['".$name."','".$lng."','".$lat."','".$street."','".$phone."','".$email."']";
                }
                
                $locations = implode(",",$locations);
          }
          
        ?>
    var map;
    var htmp;
    function initialize() {
        // set the style array for the map 
        var styles =                   
                        [
                            {
                              "stylers": [
                                { "visibility": "on" },
                                { "hue": "#00d4ff" },
                                { "saturation": -93 },
                                { "gamma": 1.8 },
                                { "lightness": -7 }
                              ]
                            },{
                              "featureType": "water",
                              "stylers": [
                                  { "color": "#28bfa9" },
//                                { "hue": "#00ffff" },
//                                { "gamma": 0.27 },
//                                { "saturation": 48 }
                              ]
                            }
                          ];
                      
        // Create a new StyledMapType object, passing it the array of styles,
        // as well as the name to be displayed on the map type control.
        var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});

        /* Allow customization of where the center point is  */
        var mapOptions = {
             center: new google.maps.LatLng(<?=$centerlat?>,<?=$centerlng?>),
             zoom: 11,
             mapTypeControlOptions: {
                                    mapTypeIds: [google.maps.MapTypeId.ROADMAP,'map_style']
                                    }
            };
                    
          map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
          
          //Associate the styled map with the MapTypeId and set it to display.
          map.mapTypes.set('map_style', styledMap);
          map.setMapTypeId('map_style');

          var locations = [<?=@$locations ?>];    
        var heatmapData = [<?=@$heatmap   ?>];  
        <?php 
            // insert any markers we have
            if(isset($locations)){
                echo "\r\n"."setMarkers(map,locations)";
            }
          
            // insert the heat map Javascript if we need to
            if(isset($heatmap) && !empty($heatmap)){
                echo "\r\n".'var pointArray = new google.maps.MVCArray(heatmapData);';
                echo "\r\n".'var heatmap = new google.maps.visualization.HeatmapLayer({data: pointArray});';
                echo "\r\n".'heatmap.setMap(map);';
                echo "\r\n".'htmp = heatmap;';
                echo "\r\n".'htmp.setOptions({radius: htmp.get('."'radius'".') ? null : 20});';
            }           
        ?>      
    }
      
    function toggleHeatmap() {
        heatmap.setMap(heatmap.getMap() ? null : map);
    }

    function changeGradient() {
      var gradient = [
        'rgba(0, 255, 255, 0)',
        'rgba(0, 255, 255, 1)',
        'rgba(0, 191, 255, 1)',
        'rgba(0, 127, 255, 1)',
        'rgba(0, 63, 255, 1)',
        'rgba(0, 0, 255, 1)',
        'rgba(0, 0, 223, 1)',
        'rgba(0, 0, 191, 1)',
        'rgba(0, 0, 159, 1)',
        'rgba(0, 0, 127, 1)',
        'rgba(63, 0, 91, 1)',
        'rgba(127, 0, 63, 1)',
        'rgba(191, 0, 31, 1)',
        'rgba(255, 0, 0, 1)'
      ]
      heatmap.setOptions({
        gradient: heatmap.get('gradient') ? null : gradient
      });
    }

    function changeRadius() {
      htmp.setOptions({radius: htmp.get('radius') ? null : 20});
    }

    function changeOpacity() {
      htmp.setOptions({opacity: htmp.get('opacity') ? null : 0.2});
    }
    
//    window.onresize = function(event) {
//        var style = document.getElementById('map_canvas').style;
//        style.width = '100%';
//        style.height = '100%';
//        style.position = 'absolute';
//        style.top = '42px';
//        style.left = '0';
//        style.right = '0';
//        style.bottom = '0';
////        style.z-index = '0';
//        style.overflow = 'hidden';
//        google.maps.event.trigger(map, 'resize');
//        map.setZoom(map.getZoom());
//    }
   
    function setMarkers(map,locations){

      var marker, i
      
      var infowindow = new google.maps.InfoWindow
                (
                    {content: ''}
                );


        for (i = 0; i < locations.length; i++)
        {  
            var name = locations[i][0]
             var lat = locations[i][1]
            var long = locations[i][2]
         var address = locations[i][3]
           var phone = locations[i][4]
           var email = locations[i][5] 
            latlngset = new google.maps.LatLng(lat, long);

            var marker = new google.maps.Marker({  
                map: map, title: name , position: latlngset,labelContent: i  
            });
            
            var content = "<div id=\'marker_container\' class=\'clearfix\'>"
                content = content+"<H2>"+name+"</H2>"
                content = content+"<span id=\"map_address\">"+address+"</span><br>"
                content = content+"<br><span id=\"map_phone\"><a href='tel:+1"+phone+"'>"+phone+"</a></span>"
                content = content+"<br><span id=\"map_email\"><a href='mailto:"+email+"'>"+email+"</a></span>"
                content = content+"</div>"
//              marker.content = content;    
//            var infowindow = new google.maps.InfoWindow
//                (
//                    {content: content}
//                );

            google.maps.event.addListener(marker,'click', 
            (function(marker,content,infowindow){ 
                return function() {
                    infowindow.close(map,marker);
                    infowindow.setContent(content);
                    infowindow.open(map,marker);
                };
            })(marker,content,infowindow));
            
//            google.maps.event.addListener(marker,'click', 
//                   function() 
//                    {
//                       infowindow.content = marker.content;
//                       infowindow.open(map,marker);
//                    }
//            );
        }
    }
    
    google.maps.event.addDomListener(window, 'load', initialize);
   
  </script>