<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HSWalkieTalkie</title>
    <link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/layout.css">
    <!-- Font Awesome-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <!-- Das neueste kompilierte und minimierte CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- Optionales Theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <!-- Das neueste kompilierte und minimierte JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="../public/css/poststylesheet.css">
    <link rel="stylesheet" href="../public/css/postwritestylesheet.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="../../bootstrap-fileinput-master/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../../bootstrap-fileinput-master/js/fileinput.min.js"></script>

    
    
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
    <footer id="footer">
        Made with <i class="fa fa-heart" aria-hidden="true"></i> and 99+ bottles of <i class="fa fa-beer" aria-hidden="true"></i>
    </footer>
    
</body>
</html>
