/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
    var slidePosInit=60;
    var slidePosTop = 20;
    
    if ($('#visualZoneTop').length){
        var visualZoneDisplay = 0;
        $('#visualZoneTop').css('display','');
        var zoneHeight = $('#visualZoneView').height() + $('#visualZoneDisplay').height() - $('#visualZoneDisplayMin').height();
        
        $('#visualZoneTop').attr(
            'style',
            'visibility:visible;position:fixed;top:-'+zoneHeight+'px;z-index:150;'
        );
        $('#visualZoneDisplay').css('bottom', '-' + $('#visualZoneDisplay').height() + 'px');
            
        $('#visualZoneView').hover(function(){
            visualZoneDisplay = 1;
        }, function(){
            visualZoneDisplay = 0;
            $(this).parents('div').stop().animate({top:'-'+zoneHeight+'px'},500);
        });
        
        $('#visualZoneDisplay').hover(function(){
            if (!visualZoneDisplay)
                $(this).parents('div').stop().animate({top:'-'+$('#visualZoneView').height()+'px'},500);
        }, function(){
            if (!visualZoneDisplay)
                $(this).parents('div').stop().animate({top:'-'+zoneHeight+'px'},500);
        }).click(function(){
            visualZoneDisplay = 1;
            $(this).parents('div').stop().animate({top:'0px'},500);
        });
        
    } else {
        $('#visualZone').css('display','');
        $('#visualZone').attr(
            'style',
            'visibility:visible;display:block;position:absolute;left:-350px;top:' + slidePosInit + 'px;z-index:150;width:1px;'
        ).hover(function() {
            $(this).stop().animate({left:'0px'},1000);
        }, function() {
            $(this).stop().animate({left:'-350px'},1000);
        });

        $(window).scroll(function() {
            posScroll = $(document).scrollTop();
            if(posScroll >= (slidePosInit - slidePosTop) ) {
                $('#visualZone').css('top', (posScroll + slidePosTop) + 'px');
            } else {
                $('#visualZone').css('top', slidePosInit + 'px');
            }
        });
    }
    
});

