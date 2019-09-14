$(document).ready(function(){

    $('#aa').html('yeeeee');
    
});

$('.fullBackground').fullClip({
  images: ["{{asset('bg-img/ad2.jpeg')}}", "{{asset('bg-img/ad4.jpeg')}}"],
  transitionTime: 3000,
  wait: 7000
});
