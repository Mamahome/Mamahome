<div class="panel panel-default" style="border-color:#0e877f">
<div class="panel-heading" style="background-color:#0e877f;font-weight:bold;font-size:1.3em;color:white">{{ $name }}</div>
<div class="panel-body" style="height:600px;max-height:600px">
 <table class="table table-responsive table-striped table-hover" >
          <tbody >
                <tr>  
                  <td style="width: 30%;"><label>Login Time : </label></td>
                  <td><strong>{{ $login }}</strong></td>
                </tr>
               @foreach($ward as $ward)
                <tr>
                  <td style="width: 30%;"><label>Assigned Ward : </label></td>
                  <td><strong>{{ $ward->sub_ward_name }}</strong></td>
                </tr>
                @endforeach
          </tbody>
</table>
<div id="map" style="width:500px;height:500px"></div>
</div>
</div>
<script type="text/javascript" scr="https://maps.google.com/maps/api/js?sensor=false"></script>
@if(count($projects) == 0)
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
    @foreach($projects as $project)
      locations.push(["<a href=\"https://maps.google.com/?q={{ $project->address }}\">{{$project->project_id}} {{$project->project_name}},{{ $project->address }}</a>",{{ $project->latitude}}, {{ $project->longitude }}]);
      created.push("{{ $project->created_at}}");
      updated.push("{{ $project->updated_at}}");
      status.push("{{ $project->status }}");
    @endforeach

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15.5,
      center: new google.maps.LatLng(locations[0][1], locations[0][2]),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) { 
    if(created[i] == updated[i]){
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
      });
    }else if(status[i] == "Order Confirmed"){
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
      });
    }else{
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
      });
    }

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
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
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&callback=myMap"></script>
@endif