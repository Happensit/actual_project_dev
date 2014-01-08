(function ($) {

    $(document).ready(function(){
        var form = document.getElementById('edit-name');

        $('#user-pass a').click(function(){
            $('#user-pass a').removeClass("active");
            $(this).addClass("active");
            $(form).focus().attr('placeholder', $(this).data('hint'));
        });
        $('#user-pass a').first().click();
    });

})(jQuery);