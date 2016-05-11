<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Musikverwaltung</title>
    <link rel="stylesheet" href="../public/css/standard.css">
    <!-- Font Awesome-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <!-- Das neueste kompilierte und minimierte CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- Optionales Theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <!-- Das neueste kompilierte und minimierte JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>


</head>
<body>
    <div class="container-fluid full">
        <? if (isset($content_top)) { ?>
            <div class="row">
                <?=$content_top ?>
            </div>
        <? }
        if (isset($content_left) && isset($content_right)) { ?>
            <div class="row content">
                <div class="col-lg-3 container-fluid">
                    <?=$content_left ?>
                </div>
                <div class="col-lg-6 container-fluid">
                    <?=$content_for_layout ?>
                </div>
                <div class="col-lg-3 container-fluid">
                    <?=$content_right ?>
                </div>
            </div>
        <? } else { ?>
            <div class="row content">
                <?= $content_for_layout ?>
            </div>
        <? } ?>
    </div>
</body>
</html>
