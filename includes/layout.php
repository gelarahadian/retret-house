<?php if(isset($layout_opener) && $layout_opener) : ?>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/middleware.php"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Retreat'; ?></title> 
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css" >
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <?php if (isset($additional_css)) : ?>
        <link rel="stylesheet" href="<?php echo $additional_css; ?>">
    <?php endif; ?>
</head>
<body>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php if (isset($header_content)) {
        include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php" ;
    } ?>
<?php endif; ?>
          
<?php if(isset($layout_closer) && $layout_closer) : ?>
    <?php if(isset($footer_content)) {
        include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php" ;
    } ?>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> -->
     <script src="/assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="module" src="<?php echo $base_url; ?>assets/js/main.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/bootstrap.bundle.min.js" ></script>
    <?php if (isset($additional_js)) : ?>
        <script type="module" src="<?php echo $base_url . $additional_js; ?>"></script>
    <?php endif; ?>
</body>
</html>
<?php endif; ?>