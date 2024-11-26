<section id="header-main" class="w-full">
    <div class="flex justify-between h-full w-full "> 
        <div class="left flex flex-col justify-center">
            <a href="<?php echo home_url(); ?>" id="logo">
                <img src="<?php echo get_url(); ?>/images/logo.png" width="100%" border="0" alt="<?php echo get_setting('naamwebsite'); ?>"/>
            </a>
        </div>
        <div class="flex flex-row items-center w-full justify-end md:justify-end">
            <?php include("php/menu.php"); ?>
            <?php include('php/topmenu.php');?> 
        </div>
    </div>
</section>

<?php if($_GET['page'] <> 'woning'): ?>
    <section id="slider-main" class="<?php echo (get_id() != "1") ? "slider-vervolg" : "";?>">
        <?php include("php/headers/slider.php"); ?>
        <?php //include("php/headers/slider-blokken.php"); ?>
        <?php //include("php/headers/slider-container.php"); ?>
        <div class="svg-container">
        <svg width="1728" height="158" viewBox="0 0 1728 158" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0L1728 42.1333V95.6778L0 158V0Z" fill="#7B2377"/>
        <path d="M0 82.5L1728 82.5V113.033L0 158V82.5Z" fill="#8A3486"/>
    </svg>
</div> 
    </section>
   



<?php endif; ?>
