<div class="panel panel-default" style="border-color:#0e877f">
<div class="panel-heading" style="background-color:#0e877f;font-weight:bold;font-size:1.3em;color:white"></div>
<div class="panel-body" style="height:500px;max-height:500px">
  
<div id="map" style="width:980PX;height:450px;overflow-y: hidden;overflow-x: hidden;"></div>
</div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<!-- <script type="text/javascript" scr="https://maps.google.com/maps/api/js?sensor=false"></script> -->
  <script type="text/javascript">
    function initMap() {
      var latitude = "13.948388";
      var longitude = "79.584654";
      var faultyward = "{{json_encode($ward)}}";
      var ward = faultyward.split('&quot;,&quot;').join('","');
      ward = ward.split('&quot;').join('"');

      var ss = JSON.parse(ward);
      var shouldAlert;
      for(var i=0; i<Object(ss['original'].length); i++){
        
        var finalward = [];
        finalward = ss['original'][i]['lat'].map(s => eval('null,' +s ));

       var bermudaTriangle = new google.maps.Polygon({paths: finalward});  
        var locat = new google.maps.LatLng(latitude,longitude);
       shouldAlert = google.maps.geometry.poly.containsLocation(locat, bermudaTriangle);

              
               if(shouldAlert == true){

                      getBrands(ss['original'][i]['ward']);
                          break;

                }
           
      }
      if(shouldAlert == false){
        alert("not serviceable area");
      }
  }
function getBrands(arg){
    const Http = new XMLHttpRequest();
    var x = arg;
    alert(x);
  const url='{{URL::to('/')}}/subfind?id='+x;
   Http.open("GET", url);
   Http.send();

Http.onreadystatechange=(e)=>{
              

           initsubward(Http.responseText);
            
            
            }
  

  }

  function initsubward(arg){
      var latitude = "13.948388";
      var longitude = "79.584654";
     
        var subfaulty = arg;
      //console.log(subfaulty);
      /*
      var subward = subfaulty.split('&quot;,&quot;').join('","');
     
      subward = subward.split('&quot;').join('"');*/

      var subs = JSON.parse(subfaulty);

     // console.log(subs.length);


      for(var i=0; i<Object(subs.length); i++){
        
        var finalsubward = [];
        finalsubward = subs[i]['lat'].map(s => eval('null,' +s ));

       var bermudaTriangle = new google.maps.Polygon({paths: finalsubward});  
        var locat = new google.maps.LatLng(latitude,longitude);
       var shouldAlert = google.maps.geometry.poly.containsLocation(locat, bermudaTriangle);

              
               if(shouldAlert == true){
                       alert(subs[i]['subward']);
                }
           
      }



  }
  </script>

   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&libraries=geometry&callback=initMap"></script>
      