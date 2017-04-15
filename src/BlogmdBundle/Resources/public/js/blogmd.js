;(function (window, $) {
    'use strict';

    window.Blogmd = {
        /**
         * Render a Markdown input to formatted HTML
         *
         * @param {String} text    The text to format
         * @param {String} preview The target element
         * @param {String} previewUrl
         */
        preview: function (text, preview, previewUrl) {
            var out = $(preview);

            out.html('Loading preview...').addClass('loading');

            $.post(
                previewUrl,
                {text: text},
                function (r) {
                    out.empty();

                    if (r.error) {
                        out.html(r.error);
                    } else if (!r.data.length) {
                        out.html('Invalid response.');
                    } else {
                        out.html(r.data);
                        Prism.highlightAll();
                    }

                    out.removeClass('loading')
                }
            );
        }
    }
})(window, jQuery);
