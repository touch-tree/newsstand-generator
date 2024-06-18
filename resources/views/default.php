<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php render_view('partials.header'); ?>

    <title>Document</title>
</head>

<body>

<div class="header">
    <button>Exit</button>
    <button>Save</button>
    <button>Export</button>
</div>

<div class="editor">
    <?php if (isset($article_id)) { ?>
        <?php foreach ($pages ?? [] as $id => $page) { ?>
            <div class="editor-section" id="page<?php echo $id ?>">
                <div class="editor-section__screen">
                    <img class="lazy-load"
                         data-src="<?php echo asset('images/' . $article_id . '/page' . $id . '.jpg') ?>"
                         alt=""
                         src="">
                </div>
                <div class="editor-section__controls">
                    <div class="lazy-load-content control-container"
                         data-src="<?php echo url('content/', ['article_id' => $article_id, 'id' => $id]); ?>"></div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div>No article_id provided</div>
    <?php } ?>
</div>

<?php render_view('partials.footer'); ?>

</body>

</html>