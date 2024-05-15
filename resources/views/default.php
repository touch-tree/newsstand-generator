<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php render_view('partials.header'); ?>

    <title>Document</title>
</head>

<body>

<div class="editor">
    <?php foreach ($pages ?? [] as $id => $page) { ?>
        <div class="editor-section">
            <div class="editor-section__screen">
                <img class="lazy-load" data-src="<?php echo url('public/temp/page_' . $id . '.jpg') ?>" alt="" src="">
            </div>
            <div class="editor-section__controls">
                <!-- Placeholder content -->
            </div>
            <!-- Add a data attribute with the URL to load the content lazily -->
            <div class="lazy-load-content" data-src="<?php echo url('load-content/' . $id) ?>"></div>
        </div>
    <?php } ?>
</div>

<script>
    $(document).ready(function () {
        const lazyloadSections = $(".lazy-load-content");

        $(window).scroll(function () {
            const scrollTop = $(this).scrollTop();

            lazyloadSections.each(function () {
                const section = $(this);
                if (section.offset().top < (window.innerHeight + scrollTop)) {
                    if (!section.hasClass('loaded')) {
                        $.get(section.data("src"), function (data) {
                            section.html(data);
                            section.addClass('loaded');
                        }).fail(function (error) {
                            console.error('Error loading content:', error);
                        });
                    }
                }
            });

            if (lazyloadSections.length === 0) {
                $(window).off("scroll");
            }
        });
    });
</script>

<?php render_view('partials.footer'); ?>

</body>

</html>