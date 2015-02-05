var ComingSoon = function () {

    return {
        //main function to initiate the module
        init: function () {

            var austDay = new Date();
            austDay = new Date(austDay.getFullYear() + 1, 0 - 1, 13);
            $('#defaultCountdown').countdown({until: austDay});
            $('#year').text(austDay.getFullYear());
        }

    };

}();