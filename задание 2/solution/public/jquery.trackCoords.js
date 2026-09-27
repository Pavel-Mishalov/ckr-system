(function ($) {
    var pos = null;

    $(document)
        .on('mousemove.trackCoords', function (e) {
            pos = {x: e.clientX, y: e.clientY};
        })
        .on('mouseleave.trackCoords', function () {
            // курсор ушел из окна
            pos = null;
        })
        .on('visibilitychange.trackCoords', function () {
            if (document.hidden) {
                pos = null;
            }
        });

    $.fn.trackCoords = function (options) {
        var settings = $.extend({}, $.fn.trackCoords.defaults, options);

        if (!settings.url) {
            return this;
        }

        return this.each(function () {
            var el = this;
            var points = [];
            var lastTime = Date.now();

            setInterval(function () {
                var now = Date.now();
                var time = now - lastTime;
                lastTime = now;

                if (!pos) {
                    return;
                }

                var rect = el.getBoundingClientRect(); // элемент мог уехать при прокрутке
                var x = Math.round(pos.x - rect.left);
                var y = Math.round(pos.y - rect.top);
                var prev = points[points.length - 1];

                if (prev && prev.x === x && prev.y === y) {
                    prev.time += time;
                } else {
                    points.push({x: x, y: y, time: time});
                }
            }, settings.checkInterval);

            setInterval(function () {
                if (points.length) {
                    $.post(settings.url, {coords: JSON.stringify(points)});
                    points = [];
                }
            }, settings.sendInterval);
        });
    };

    $.fn.trackCoords.defaults = {
        checkInterval: 30,
        sendInterval: 3000,
        url: ''
    };
})(jQuery);
