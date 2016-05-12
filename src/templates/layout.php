<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HSWalkieTalkie</title>
    <!-- Font Awesome-->
    <link rel="stylesheet" href="/HSWalkieTalkie/src/libraries/font-awesome-4.6.2/css/font-awesome.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="/HSWalkieTalkie/src/libraries/bootstrap-3.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/HSWalkieTalkie/src/libraries/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">
    <script src="/HSWalkieTalkie/src/libraries/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="/HSWalkieTalkie/src/libraries/jQuery/jquery-2.2.3.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/HSWalkieTalkie/src/public/css/layout.css">
    <link rel="stylesheet" href="../public/css/poststylesheet.css">
    <link rel="stylesheet" href="../public/css/postwritestylesheet.css">
    <link href="bootstrap-fileinput-master/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="bootstrap-fileinput-master/js/fileinput.min.js"></script>



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
                <div id="content-right" class="col-lg-3 container-fluid">
                    <?=$content_left ?>
                </div>
                <div id="content-center" class="col-lg-6 container-fluid">
                    <?=$content_for_layout ?>
                </div>
                <div id="content-right" class="col-lg-3 container-fluid">
                    <?=$content_right ?>
                </div>
            </div>
        <? } else { ?>
            <div id="content-center" class="row content">
                <?=$content_for_layout ?>
            </div>
        <? } ?>
    </div>
    <footer id="footer">
        Made with <i class="fa fa-heart" aria-hidden="true"></i> and 99+ bottles of <i class="fa fa-beer" aria-hidden="true"></i>
    </footer>

</body>
</html>
