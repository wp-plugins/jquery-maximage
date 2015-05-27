jQuery(document).ready(function ($) {

    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id;

    $('.upload-button').live('click', function (e) {

        var button = $(this);
        var button_id = button.attr('id');

        e.preventDefault();

        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text')
            },
            multiple: false
        });

        file_frame.on('select', function () {

            attachment = file_frame.state().get('selection').first().toJSON();

            var url = '';
            url = attachment['url'];
            $('#i' + button_id).val(url);

        });

        file_frame.open();

    });

});