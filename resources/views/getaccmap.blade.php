<div class="panel panel-default" style="border-color:#0e877f">
<div class="panel-heading" style="background-color:#0e877f;font-weight:bold;font-size:1.3em;color:white"></div>
<div class="panel-body" style="height:500px;max-height:500px">
  <b>Name : </b>{{ $name }}<br><br>
  @foreach($login as $login)
  <b>Field Login Time : </b>{{ $login->logintime }}<br><br>
  <b>Remark(Late Login) : </b>{{ $login->remark }}<br><br>
  @endforeach
   @foreach($ward as $ward)
                <b>Assigned Ward : </b>{{ $ward->sub_ward_name }}
    
    @endforeach
    <br><br>
<div id="map" style="width:900PX;height:450px;overflow-y: hidden;overflow-x: hidden;"></div>
</div>
</div>
<script type="text/javascript" scr="https://maps.google.com/maps/api/js?sensor=false"></script>
@if($projects == null)
<script type="text/javascript">
    window.onload = function() {
    var locations = new Array();
    var created = new Array();
    var updated = new Array();
    var status = new Array();
    var newpath = [];
    @if($subwardMap != "None")
    var latlng = "{{ $subwardMap->lat }}";
    var col = "{{ $subwardMap->color }}";
    @else
    var latlng = "";
    var col = "456369"
    @endif
    var places = latlng.split(",");
    for(var i=0;i<places.length;i+=2){
          newpath.push({lat: parseFloat(places[i]), lng: parseFloat(places[i+1])});
    }

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 16,
      center: new google.maps.LatLng(12.9716, 77.5946),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();
   
    var marker, i;
    var subward = new google.maps.Polygon({
        paths:  newpath,
        strokeColor: '#'+col,
        strokeOpacity: 1,
        strokeWeight: 2,
        fillColor: '#'+col,
        fillOpacity: 0.9
      });
  subward.setMap(map);
  }
  </script>
@else
  <script type="text/javascript">
    window.onload = function() {
    var locations = new Array();
    var created = new Array();
    var updated = new Array();
    var status = new Array();
    var newpath = [];
    @if($subwardMap != "None")
    var latlng = "{{ $subwardMap->lat }}";
    var col = "{{ $subwardMap->color }}";
    @else
    var latlng = "";
    var col = "456369"
    @endif
    var places = latlng.split(",");
    for(var i=0;i<places.length;i+=2){
          newpath.push({lat: parseFloat(places[i]), lng: parseFloat(places[i+1])});
    }

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15.5,
      center: new google.maps.LatLng("{{ $projects->latitude }}", "{{ $projects->longitude }}"),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    var latitude = "{{ $projects->latitude }}";
    var longitude = "{{ $projects->longitude }}";
    var contentString = "{{ $projects->address }}";
    var infowindow = new google.maps.InfoWindow({
        content: contentString
      });

      marker = new google.maps.Marker({
        position: new google.maps.LatLng(latitude, longitude),
        map: map,
      });
      marker.addListener('click', function() {
          infowindow.open(map, marker);
        });

    if(newpath.length > 1){
    
      var subward = new google.maps.Polygon({
          paths: newpath,
          strokeColor: '#'+col,
          strokeOpacity: 1,
          strokeWeight: 2,
          fillColor: '#'+col,
          fillOpacity: 0.4
        });
    subward.setMap(map);
    }
  }
  </script>
@endif
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&callback=map"></script>