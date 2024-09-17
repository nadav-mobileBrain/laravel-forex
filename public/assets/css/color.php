<?php
header ("Content-Type:text/css");
$color = "#ff0000";

function checkhexcolor($color) {
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if( isset( $_GET[ 'color' ] ) AND $_GET[ 'color' ] != '' ) {
    $color = "#" . $_GET[ 'color' ];
}

if( !$color OR !checkhexcolor( $color ) ) {
    $color = "#ff0000";
}

?>


<!-- Color -->

.colored-text,.email-phone a i,.header-social-menu li a:hover,.user-area li a:hover,.user-icon,.expert-menu li > ul > li a > i,
.expert-menu ul li:hover > a,.expert-menu ul ul li:hover > a,
.expert-menu li ul li a:hover,.intro-content h3,.project-title:hover a,.portfolio-category a:hover,.member-bookmark li a,
.single-contact-option a,.post-title a:hover,.post-date li i,.post-cat-list a:hover,.post-widget-content h4 a:hover,.counter-icon i,
.footer-conatct-menu li i,.links a:hover,.links li i,.footer-copyright-info p a,.footer-copyright-info p a:hover,.footer-main-menu li a:hover,
.intro-content h3,.footer-copyright-info p a{
    color: <?php echo $color; ?>;
}

.colored-bg,.nav-tabs>li>a::before,.process-icon:before {
    background-color: <?php echo $color; ?>;
}
.user-icon,.email-phone a i,.user-area li a:hover,.intro-content h3,.footer-copyright-info p a:hover,.footer-copyright-info p a,.links li i,.footer-conatct-menu li i,.links a:hover,.post-date li i {
    color: <?php echo $color; ?>;
}

.button,.load-more-btn:focus,.area-heading::before,.area-heading:after,.mean-container .mean-nav,.mean-container .mean-nav ul li a.mean-expand:hover,
.video-play-icon,.portfolio-filter li a:hover,.portfolio-filter li,.active a,.project-zoom:hover,.member-content,.team-style-2, .member-bookmark,.team-style-2, .member-content,
.process-box:hover, .process-icon,.widget-title:before,.table-active .pricing-head,.footer-social-menu li a,.footer-widget-heading h3::before,.tags li a:hover
.tags li a:hover,.to-top-btn,.slick-prev, .slick-next
{
    background: <?php echo $color; ?>;
}

.area-heading:after {
    box-shadow: 10px 0px 0px 0px <?php echo $color; ?>;
}
.header-top-2,.header-top-area {
border-bottom: 2px solid <?php echo $color; ?>;
}
.expert-menu li ul {
border-top: 2px solid <?php echo $color; ?>;}
.blog-post blockquote,.single-service-content blockquote {
border-left: 5px solid <?php echo $color; ?>;
}
#expert-loader {
    background-image: -webkit-linear-gradient(left, #0af9d7 0%, <?php echo $color; ?> 100%);
    background-image: -o-linear-gradient(left, #0af9d7 0%, <?php echo $color; ?> 100%);
    background-image: linear-gradient(to right, #0af9d7 0%, <?php echo $color; ?> 100%);
}
.email-submit-btn .btn-white{
background: #fff !important;
}