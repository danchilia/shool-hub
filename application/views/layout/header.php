<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="DCK Solutions - School Management System">
    <meta name="author" content="DCK Solutions">
    <title><?php echo html_escape($title);?></title>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
    <!-- PWA manifest -->
    <link rel="manifest" href="<?php echo base_url('manifest.json'); ?>">
    <meta name="theme-color" content="#1a5276">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="DCK Schools">

    <!-- Stylesheets + early jQuery -->
    <?php include 'stylesheet.php'; ?>

    <?php
    // Per-page extra CSS/JS injected by controllers via $headerelements
    if (isset($headerelements)) {
        foreach ($headerelements as $type => $elements) {
            if ($type === 'css') {
                foreach ((array)$elements as $css) {
                    echo '<link rel="stylesheet" href="' . base_url('assets/' . $css) . '">' . "\n";
                }
            } elseif ($type === 'js') {
                foreach ((array)$elements as $js) {
                    echo '<script src="' . base_url('assets/' . $js) . '"></script>' . "\n";
                }
            }
        }
    }
    ?>

    <!-- CSRF token exposed for jQuery AJAX -->
    <script>
    var base_url = '<?php echo base_url(); ?>';
    var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
    $(function () {
        $.ajaxSetup({ data: csrfData });
    });
    </script>
</head>
