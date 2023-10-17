var max_words = 250;
function calculate_words(value) {

        var word_count = 0;
        if (value.length == 0) {
                word_count = 0;
        } else {
                var regex = /\s+/gi;
                word_count = value.trim().replace(regex, ' ').split(' ').length;
        }
        return word_count;
}

function handle_word_count(content,id) {
	content = content.replace(/<\p>/ig,' ');
	content = content.replace(/<\li>/ig,' ');
        word_count = calculate_words(content.replace(/(<[a-zA-Z\/][^<>]*>|\[([^\]]+)\])|/ig,''));
        var p = jQuery("#" + id + "_count");
        p.find('span').html(word_count);
        if (word_count > max_words) {
                p.css('color','red');
        } else {
                p.css('color','black');
        }
        check_submitable();
}

function check_submitable() {
        var disabled = false;
        jQuery('.word-count span.total').each(function() {
                if (parseInt(jQuery(this).html(),10) > max_words) {
                        disabled = true;
                }
        });
        jQuery("#submit_answers").prop("disabled",disabled);
        if (disabled) {
                jQuery(".submit-warning").show();
        } else {
                jQuery(".submit-warning").hide();
        }
}

