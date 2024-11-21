
<?php

$disclaimer = the_field('item2', 96);
$privacy = the_field('item2', 97);
$footer = use_query('kolom_1,kolom_2,kolom_3,kolom_4', 'sitework_website_settings');


if(get_meertaligheid() == true) {
	$ifTaalUtl = '/'.get_taal();
} else { $ifTaalUtl = ''; }
function replace_icon_keywords($content) {
    $replacements = [
        'icon-phone' => '
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="icon-phone" viewBox="0 0 24 24">
                <path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1.5 1.5 0 011.6-.33 11.72 11.72 0 003.7.6 1.5 1.5 0 011.5 1.5v3.58a1.5 1.5 0 01-1.29 1.48A20.33 20.33 0 012 4.71a1.5 1.5 0 011.5-1.5H7a1.5 1.5 0 011.5 1.5 11.72 11.72 0 00.6 3.7 1.5 1.5 0 01-.33 1.6l-2.2 2.2z"/>
            </svg>',
        'icon-mail' => '
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_18_287)">
                    <path d="M3 3H21C21.2652 3 21.5196 3.10536 21.7071 3.29289C21.8946 3.48043 22 3.73478 22 4V20C22 20.2652 21.8946 20.5196 21.7071 20.7071C21.5196 20.8946 21.2652 21 21 21H3C2.73478 21 2.48043 20.8946 2.29289 20.7071C2.10536 20.5196 2 20.2652 2 20V4C2 3.73478 2.10536 3.48043 2.29289 3.29289C2.48043 3.10536 2.73478 3 3 3ZM12.06 11.683L5.648 6.238L4.353 7.762L12.073 14.317L19.654 7.757L18.346 6.244L12.061 11.683H12.06Z" fill="#FFA3A3"/>
                </g>
                <defs>
                    <clipPath id="clip0_18_287">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg>',
        'icon-location' => '
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_18_290)">
                    <path d="M18.364 17.3639L12 23.7279L5.636 17.3639C4.37734 16.1052 3.52019 14.5016 3.17293 12.7558C2.82567 11.0099 3.00391 9.20035 3.6851 7.55582C4.36629 5.91129 5.51984 4.50569 6.99988 3.51677C8.47992 2.52784 10.22 2 12 2C13.78 2 15.5201 2.52784 17.0001 3.51677C18.4802 4.50569 19.6337 5.91129 20.3149 7.55582C20.9961 9.20035 21.1743 11.0099 20.8271 12.7558C20.4798 14.5016 19.6227 16.1052 18.364 17.3639ZM12 12.9999C12.5304 12.9999 13.0391 12.7892 13.4142 12.4141C13.7893 12.0391 14 11.5304 14 10.9999C14 10.4695 13.7893 9.96078 13.4142 9.58571C13.0391 9.21064 12.5304 8.99992 12 8.99992C11.4696 8.99992 10.9609 9.21064 10.5858 9.58571C10.2107 9.96078 10 10.4695 10 10.9999C10 11.5304 10.2107 12.0391 10.5858 12.4141C10.9609 12.7892 11.4696 12.9999 12 12.9999Z" fill="#FFA3A3"/>
                </g>
                <defs>
                    <clipPath id="clip0_18_290">
                        <rect width="24" height="24" fill="white"/>
                    </clipPath>
                </defs>
            </svg>'
    ];

    return str_replace(array_keys($replacements), array_values($replacements), $content);
}

$footer = use_query('kolom_1,kolom_2,kolom_3,kolom_4', 'sitework_website_settings');
$footer_col1 = replace_icon_keywords($footer['kolom_1']);

?>

<section id="footer-main">
    <div id="footer-info">
        <div class="container mx-auto flex flex-col lg:flex-row gap-8 items-center justify-center lg:justify-center">
            <div class="foot-col text-center lg:text-left">
                <p><?php echo $footer_col1; ?></p>
                <?php include('php/socialmedia.php'); ?>
            </div>
        </div>
    </div>
	<div id="footer-formulier" class="flex justify-center items-center h-[60vh]">
	<div class="w-[63%]">
		<?php
			$_GET['formIdBlock'] = '1';
			include('php/formulieren.php');
		?>
	</div>
</div>

</section> 

<section id="footer-small" class="py-4 md:px-8 lg:px-12 xl:px-16">
	<div class="container mx-auto flex flex-col md:flex-row justify-between">
		<div class="left flex text-white flex-wrap items-center gap-0 md:gap-4 flex-col md:flex-row">
			<a href="<?php echo get_url();?>">&copy <?=date('Y');?>, <?=$sitenaam;?></a>
			<span>&bullet;</span>
			<a href="<?php echo get_link(96);?>"><?=$disclaimer;?></a>
			<span>&bullet;</span>
			<a href="<?php echo get_link(97);?>"><?=$privacy;?></a>
		</div> 
		<div class="right mt-8 md:mt-0 justify-center md:justify-end">
            <div id="sitework" class="flex items-center justify-center md:justify-end">
                <a href="https://sitework.nl" aria-label="Sitework Lochem B.V." target="_blank" rel="noopener" rel="noreferrer" class="flex flex-row items-center mr-2">
                    <span class="sitework-footer font-sitework">sitework</span>

                    <svg class="ml-2" width="32" height="33" viewBox="0 0 32 33" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M30.1731 24.0334C31.3398 21.8129 32 19.2845 32 16.602C32 7.76572 24.8366 0.602051 16.0008 0.602051C7.16179 0.602051 0 7.76572 0 16.602C0 25.4384 7.16179 32.6021 16.0008 32.6021C18.6829 32.6021 21.211 31.9419 23.4314 30.7753C24.3104 31.888 25.6717 32.6021 27.2002 32.6021C29.851 32.6021 32 30.4529 32 27.8021C32 26.2739 31.2859 24.9125 30.1731 24.0334ZM30.4 27.8021C30.4 29.5693 28.9673 31.0021 27.2001 31.0021C25.4324 31.0021 24 29.5693 24 27.8021C24 26.0348 25.4324 24.6021 27.2001 24.6021C28.9673 24.6021 30.4 26.0348 30.4 27.8021ZM17.6335 11.2711C18.1107 11.6015 18.4411 12.0787 18.4779 12.7028H25.086C24.9025 7.82054 19.9463 6.68205 15.835 6.68205C12.017 6.68205 6.84055 7.93068 6.84055 12.7395C6.84055 16.0069 9.07998 17.7695 14.6231 18.7236C18.0006 19.311 18.6247 19.6414 18.6247 20.7428C18.6247 21.8445 17.1195 22.2112 16.1654 22.2112C15.2839 22.2112 14.5864 21.9914 14.2193 21.661C13.669 21.1837 13.3753 20.633 13.3386 19.9722H6.4C6.51014 24.8912 11.4291 26.6905 15.908 26.6905C20.5337 26.6905 25.6 25.2588 25.6 19.9722C25.6 16.8513 23.4707 15.4199 21.0477 14.649C19.7643 14.2212 18.3882 13.9787 17.1977 13.7688C16.1406 13.5825 15.2298 13.422 14.6602 13.1805C14.256 12.9969 13.8158 12.7395 13.8158 12.1889C13.8158 10.9406 14.9906 10.7204 16.0182 10.7204C16.6055 10.7204 17.1929 10.9039 17.6335 11.2711Z" fill="white"/>
                    </svg>
                </a>
                <a href="https://achterhoekhosting.com/" aria-label="Achterhoekhosting" target="_blank" rel="noopener" rel="noreferrer" class="">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5933 16.7099C16.5394 16.4828 17.2774 16.2368 17.8072 15.9719V17.3911C17.8072 18.4507 17.5045 19.2833 16.899 19.8888C16.2934 20.4944 15.4609 20.7971 14.4012 20.7971C13.7578 20.7971 13.2659 20.6457 12.9253 20.343C12.5847 20.0024 12.4144 19.5482 12.4144 18.9806C12.4144 18.5643 12.5468 18.148 12.8117 17.7317C13.1145 17.4289 13.6065 17.1829 14.2877 16.9937L15.5933 16.7099Z" fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M32 16C32 24.8366 24.8366 32 16 32C7.16344 32 0 24.8366 0 16C0 7.16344 7.16344 0 16 0C24.8366 0 32 7.16344 32 16ZM26.237 21.2675C26.237 22.414 25.3075 23.3435 24.161 23.3435C23.0144 23.3435 22.0849 22.414 22.0849 21.2675C22.0849 20.1209 23.0144 19.1914 24.161 19.1914C25.3075 19.1914 26.237 20.1209 26.237 21.2675ZM16.161 23.6922C17.0693 23.6166 17.9208 23.3706 18.7155 22.9543C19.4724 22.5758 20.1158 21.9703 20.6456 21.1377C21.2133 20.2673 21.4971 19.0752 21.4971 17.5614V12.3956C21.4971 11.0332 20.9484 9.97349 19.8509 9.2166C18.7912 8.49755 17.2017 8.13802 15.0824 8.13802C13.1902 8.13802 11.7331 8.57324 10.7113 9.44367C9.80306 10.2006 9.27323 11.1656 9.12185 12.3388H12.9253C13.1145 11.9225 13.4172 11.6387 13.8335 11.4873C14.2498 11.3359 14.7229 11.2602 15.2527 11.2602C15.7447 11.2602 16.2745 11.3548 16.8422 11.5441C17.4477 11.6954 17.7505 12.055 17.7505 12.6226C17.7505 13.3795 16.9179 13.8715 15.2527 14.0986C14.9121 14.1364 14.6094 14.1932 14.3444 14.2689C14.0795 14.3067 13.8525 14.3257 13.6632 14.3257C12.2251 14.5527 11.033 15.0069 10.0869 15.6881C9.17862 16.3693 8.72448 17.5046 8.72448 19.0941C8.72448 20.7971 9.23538 22.0271 10.2572 22.784C11.279 23.503 12.4711 23.8625 13.8335 23.8625C14.5147 23.8625 15.2906 23.8058 16.161 23.6922Z" fill="white"/>
                    </svg>
                </a>
            </div>
		</div>
	</div>
</section>