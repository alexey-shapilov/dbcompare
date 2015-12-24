!function ($) {
    $(document).ready(function () {
        $('.db-compare').on('click', '.count', function (e) {
            var $this = $(this),
                dataShow = $this.attr('data-show'),
                dbPanel = $this.closest('.db-panel');

            dataShow = '.table' + dataShow.charAt(0).toUpperCase() + dataShow.substr(1).toLowerCase();
            dbPanel.find(dataShow).toggleClass('hide');
            $this.toggleClass('dataShow');
        })
    });
}(jQuery);