$(document).ready(function() {
console.log('Client-side code running');

const button = document.getElementById('success');
if(button) {
 
  button.addEventListener('click', function(e) {
    console.log('button was clicked');
    speed_small = document.getElementById('speed_small').value
    datepicker = document.getElementById('datepicker').value
    time_limit = document.getElementById('time_limit').value
    speed_big = document.getElementById('speed_big').value
    time2_limit = document.getElementById('time2_limit').value
    laneoccupy_big = document.getElementById('laneoccupy_big').value
    console.log(speed_small) 
    console.log(datepicker)  
    console.log(time_limit)  
    console.log(speed_big)  
    console.log(time2_limit)  
    console.log(laneoccupy_big)  
    var data = {};
    data.speed_small = speed_small
    data.datepicker = datepicker
    data.time_limit = time_limit
    data.speed_big = speed_big
    data.time2_limit = time2_limit
    data.laneoccupy_big = laneoccupy_big


    $.ajax({ 
      url: 'http://localhost:3000/',
      type: 'POST',
      cache: false, 
      data: JSON.stringify(data), 
      contentType: 'application/json',
      success: function(data){
        console.log(data)
        
        alert('text status '+textStatus+', err '+err)
      }
      , error: function(jqXHR, textStatus, err){
          alert('text status '+textStatus+', err '+err)
      }
   })
  });
}
const button_1 = document.getElementById('reset');
if(button_1) {
  console.log('reset_button is exist')
  button_1.addEventListener('click', function(e) {
    console.log('reset_button was clicked');
    $(".form-control").val("");
    $("#datepicker").val("");
  });
}
$("#datepicker").datepicker({
  "dateFormat":"yy-mm-dd",
});
})