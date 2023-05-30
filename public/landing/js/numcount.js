
$(document).ready(function(){
    let frame =  $('a[href="#participating_industries"]');
    var time = 2, cc = 1;

    function countStart()
    {
        if (cc < 2) {
            $('.number_op').addClass('viz');
            $('div').each(function (){
                var
                    i = 1,
                    num = $(this).data('num'),
                    step = 1000 * time / num,
                    that = $(this),
                    int = setInterval(function (){
                        if (i <= num) {
                            that.html(i);
                        }
                        else {
                            cc = cc + 2;
                            clearInterval(int);
                        }
                        i++;
                    }, step);
            });
        }
    }
    function onNeededUrl()
    {
        let current_href = location.href;
        if(current_href.includes('#participating_industries'))
        {
            countStart();
        }
    }

    frame.on('click', countStart);
    $(window).on('hashchange', onNeededUrl);
    $(window).scroll(function (){
        console.log('aaa');
        $('#counter').each(function(){
            var
                cPos = $(this).offset().top,
                topWindow = $(window).scrollTop();
            if (cPos < topWindow + 500) {
                countStart();
            }
        });
    });

    onNeededUrl();

    var time2 = 2, cc2 = 1;
    $(window).scroll(function (){
        $('#counter2').each(function(){
            var
                cPos = $(this).offset().top,
                topWindow = $(window).scrollTop();
            if (cPos < topWindow + 800) {
                if (cc2 < 2) {
                    $('.number_op').addClass('viz');
                    $('div').each(function (){
                        var
                            i = 1,
                            num = $(this).data('num'),
                            step = 1000 * time2 / num,
                            that = $(this),
                            int = setInterval(function (){
                                if (i <= num) {
                                    that.html(i);
                                }
                                else {
                                    cc2 = cc2 + 2;
                                    clearInterval(int);
                                }
                                i++;
                            }, step);
                    });
                }
            }
        });
    });
});

