function days_remaining(iso_end_date) {
    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24;

    // Convert both dates to milliseconds
    var end_date_ms = new Date(iso_end_date);
    var now_ms = (new Date()).getTime();

    // Calculate the difference in milliseconds
    var difference_ms = end_date_ms - now_ms;

    // Convert back to days and return
    return Math.floor(difference_ms/ONE_DAY) + 1;
}

(function($) {
    $(function() {
        var $countdown = $(".countdown");
        var end_date = $countdown.data('end-date');
        var days_until = days_remaining(end_date);

        if (days_until > 0) {
            var days = (days_until == 1) ? "day" : "days";
            var countdown_message = 'There are <strong>' + days_until + ' ' + days + '</strong> until the election.';
            $countdown.html('<p>' + countdown_message + '</p>');
        }
    });
}(jQuery));
