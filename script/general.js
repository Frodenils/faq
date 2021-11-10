// $(document).ready(function(){
// 	//Detailed Info expand and collapse
// 	$(".box-panel").hide();
//     $(".box-top").click(function(){
//     	if($(this).next(".box-panel").css("display")=="none"){
//         	$(this).next(".box-panel").show();
//         	$(this).find("img").attr("src","img/white_minus.gif");
//     	}
//     	else{
//         	$(this).next(".box-panel").hide();
//         	$(this).find("img").attr("src","img/white_plus.gif");
//     	}
// 	});
// });

$(document).ready(function(){
    $(".box-top").click(function(){
        $(this).next(".box-panel").slideToggle('fast');

        var src = ($(this).find("img").attr('src') === 'img/white_plus.gif')
            ? 'img/white_minus.gif'
            : 'img/white_plus.gif';
        $(this).find("img").attr('src', src);

    });
});

$(document).ready(function(){
    $("a.mobile").click(function(){
        $(".sidebar").slideToggle('fast');
    });

    window.onresize = function(event) {   // fonction pour que qd le menu est replier
        if($(window).width() > 500) {     // il soit comme même visible qd on ragrandi l'écran
            $(".sidebar").show();
        }
    }
});