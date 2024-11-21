<section id="content-woning-main" class="mt-32 mb-8">
    <?php include ('php/breadcrumbs.php'); ?>
	<div class="container mx-auto grid grid-cols-1 md:grid-cols-5 gap-x-12 gap-y-2 pt-6">
		<div class="md:col-span-5 grid grid-cols-4 content gap-4"> <!-- md:col-span-3 -->
			<!-- <div id="hoofd-afbeelding" class="h-auto w-full aspect-[5/3]">

            </div>
            <div id="foto-slider" class="overflow-hidden flex flex-row gap-4">
            
            </div> -->
            <swiper-container init="false" id="hoofd-afbeelding" class="aspect-video col-span-2" style="--swiper-pagination-fraction-color: #fff ;--swiper-navigation-color: var(--lightGray); --swiper-pagination-color: var(--primary)" thumbs-swiper=".woningGalerij" >
            </swiper-container>

            <swiper-container init="false" id="foto-slider" style="--swiper-navigation-color: var(--primary); --swiper-pagination-color: var(--primary)" class="aspect-video woningGalerij col-span-2">
            </swiper-container>
		</div>
        <div id="knoppen" class="md:col-span-5 py-2 px-2">
            <div id="all-img-fancy">

            </div>
        </div>
        <div class="md:col-span-5 grid grid-cols-1 md:grid-cols-5 gap-12">
            <article class="md:col-span-3 content gap-4">
                <h1 id="woning-titel" class="mb-6"></h1>
                <p id="tekst-inhoud" class="whitespace-pre-wrap"></p>
                <div class="tekst-leesmeer">lees meer +</div>
            </article>
            <aside class="md:col-span-2 shadow-2xl rounded-lg pt-4 pb-8 px-8 h-fit">
                <h2>Kenmerken</h2>
                <table id="kenmerken-table" class="w-full">
                </table>
            </aside>
        </div>
	</div>
</section>

<!-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
