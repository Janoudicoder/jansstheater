<?php 
    $getSeo = $url . '/php/realworks/get-woning-json.php';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $getSeo);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: sw_get'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log('cURL error: ' . curl_error($ch));
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    if ($response !== false) {
        $woningData = json_decode($response, true);

        $woningURL = $_GET['title'];
        $expWoningURL = explode('-', $woningURL);
        $woningObjectCode = $expWoningURL[count($expWoningURL) - 1];

        $woning = findPropertyByObjectcode($woningData['resultaten'], $woningObjectCode);
        
        // Woning data
        $woningStraat = $woning['adres']['straat'];
        $woningHuisNrToe = $woning['adres']['huisnummertoevoeging'];
        $woningHuisNr = $woning['adres']['huisnummer'];
        $woningPlaats = $woning['adres']['plaats'];

        $mediaArray = $woning['media'];
        $hoofdfotoImage = '';

        foreach ($mediaArray as $mediaItem) {
            if (isset($mediaItem['soort']) && $mediaItem['soort'] === 'HOOFDFOTO') {
                $hoofdfotoImage = $mediaItem['link'] . '&width=1440&height=960&resize=5&size=936x593';
                break; // Stop the loop once the HOOFDFOTO is found
            }
        }

        if($woning['teksten']['eigenSiteTekst']) {
            $limit_desc = limited_text($woning['teksten']['eigenSiteTekst'], 200);
        } else {
            $limit_desc = limited_text($woning['teksten']['eigenSiteTekst'], 200);
        }

        $woningKey =    $woning['adres']['provincie'] . ',' . 
                        $woning['adres']['plaats'] .
                        ($woning['algemeen']['woonhuissoort'] ? ',' . $woning['algemeen']['woonhuissoort'] : '') . 
                        ($woning['algemeen']['woonhuistype'] ? ',' . $woning['algemeen']['woonhuistype'] : '');

        $titel = $woningStraat . ' ' . $woningHuisNr . ($woningHuisNrToe ? $woningHuisNrToe : '') . ' - ' . ucfirst(strtolower($woningPlaats)); 
        $keywords = $woningKey;
        $description = $limit_desc;
        $socialimage = $hoofdfotoImage;
    } else {
        $titel = ''; 
        $keywords = '';
        $description = '';
        $socialimage = '';
    }
?>
<script type="text/javascript" defer>
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    document.addEventListener('DOMContentLoaded', () => {
        let woning = [];

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        
        async function fetchWoningen() {
            try {
                const response = await fetch('/php/realworks/get-woning-json.php', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'sw_get',
                    }
                });
                
                const data = await response.json();

                const woningURL = '<?=$_GET['title'];?>';
                let URLparts = woningURL.split('-');
                let woning_objectcode = URLparts[URLparts.length - 1];

                const propertie = data.resultaten.find(item => item.diversen.diversen.objectcode === woning_objectcode);
                woning = propertie;

                displayProperties();
            } catch (error) {
                console.error('Error fetching data:', error);
                const container = document.getElementById('woning-overzicht');
                container.innerHTML = '<p>Error loading articles. Please try again later.</p>';
            }
        }

        function displayProperties() {
            const mediaArray = woning.media;
            const hoofdfotoIndex = mediaArray.findIndex(item => item.soort === "HOOFDFOTO");

            let imageString = '';
            const filteredMediaArray = mediaArray.filter(mediaItem => mediaItem.vrijgave);
            filteredMediaArray.sort((a, b) => a.volgnummer - b.volgnummer);

            filteredMediaArray.forEach(mediaItem => {
                imageString += `<swiper-slide class="slide aspect-video cursor-pointer">`;
                    imageString += `<img class="w-full h-full object-cover" src="${mediaItem.link}&width=1440&height=960&resize=5&size=936x593" alt="${mediaItem.omschrijving ? mediaItem.omschrijving : mediaItem.titel}">`;
                imageString += `</swiper-slide>`;
            });


            var huisnr = woning.adres.huisnummer.hoofdnummer;
            var huisnrToe = woning.adres.huisnummer.toevoeging;
            var straat = woning.adres.straat;
            var pc = woning.adres.postcode;
            var plaats = woning.adres.plaats;
            plaats = plaats.toLowerCase();
            plaats = plaats.charAt(0).toUpperCase() + plaats.slice(1);
            const title = straat + ' ' + huisnr + (huisnrToe ? huisnrToe : '') + ' - ' + plaats;

            var woningTekst = '';

            // Tekst
            if(woning.teksten.eigenSiteTekst != "") {
                woningTekst = woning.teksten.eigenSiteTekst;
            } else { woningTekst = woning.teksten.aanbiedingstekst; }

            // Kamers
            let totaalKamers = 0;
            let totaalSlaapkamers = 0;

            woning.detail.etages.forEach((etage, index) => {
                totaalSlaapkamers += etage.aantalSlaapkamers;
            });
            woning.detail.etages.forEach((etage, index) => {
                totaalKamers += etage.aantalKamers;
            });

            let EURO = new Intl.NumberFormat('nl-NL', {
                style: 'currency',
                currency: 'EUR',
            });

            // Voeg waardes op hun plek
            // ========================
                // Algemeen
                // ========
                $('#woning-titel').text(title);
                $('#tekst-inhoud').text(woningTekst);

                // Afbeeldingen
                // ============

                    // Hoofdafbeelding
                    // ===============
                    // $('#hoofd-afbeelding').html('<img class="w-full h-full object-cover" src="'+woning.media[hoofdfotoIndex].link+'&width=1000&height=648&resize=5&size=936x593" />');

                    // Foto's
                    // ======
                    try {
                        $('#hoofd-afbeelding').html(imageString);
                        $('#foto-slider').html(imageString);

                        var galleryHtml = '';
                        filteredMediaArray.forEach(image => {
                            galleryHtml += '<a class="woning-gallery-foto hidden" href="' + image.link + '&width=1440&height=960&resize=5&size=936x593" data-fancybox="gallery" data-thumb="' + image.link + '&width=1440&height=960&resize=5&size=936x593" data-fancybox="gallery"" data-type="image" data-caption="' + (image.titel ? image.titel : title) + '">';
                                galleryHtml += '<p class="!text-primary hover:!text-secondary transition-all duration-300 flex items-center gap-2"><i class="fad fa-images"></i><span>Foto overzicht (<span id="count">'+filteredMediaArray.length+'</span>)</span></p>';
                            galleryHtml += '</a>';
                        });

                        $('#all-img-fancy').append(galleryHtml);

                    } catch (error) {
                        
                    } finally {
                        const swiperEl = document.querySelector('#hoofd-afbeelding');
                        const swiperGAL = document.querySelector('#foto-slider');

                        const swiperParams = {
                            slidesPerView: 1,
                            loop: true,
                            spaceBetween: 10,
                            pagination: {
                                type: 'fraction',  // or 'bullets' for bullet pagination
                            },
                            navigation: true,
                            on: {
                                init() {
                                    // Additional initialization logic if needed
                                },
                            },
                        };

                        const swiperParamsGAL = {
                            slidesPerView: 2.5,
                            loop: true,
                            spaceBetween: 10,
                            navigation: false,
                            freeMode: true,
                            watchSlidesProgress: true,
                            pagination: {
                                // el: '.swiper-pagination',
                                dynamicBullets: true,
                            },
                            grid: {
                                rows: 2, // 2 rows to make a 2x2 grid
                            },
                            // breakpoints: {
                            //     640: {
                            //         slidesPerView: 4.5,
                            //     },
                            //     1024: {
                            //         slidesPerView: 6.5,
                            //     },
                            // },
                            on: {
                                init() {
                                    // ...
                                },
                            },
                        };
                        // now we need to assign all parameters to Swiper element
                        Object.assign(swiperEl, swiperParams);
                        swiperEl.initialize();

                        Object.assign(swiperGAL, swiperParamsGAL);
                        swiperGAL.initialize();
                    }


                // Kenmerkenπ
                // =========
                var kenmerkenString = '';

                if(woning.algemeen.woonhuissoort) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Soort woonhuis: </th>`;
                        kenmerkenString += `<td>${capitalizeFirstLetter(woning.object.type.objecttype.toLowerCase().replaceAll('_', ' '))}${woning.algemeen.woonhuistype ? ', ' + capitalizeFirstLetter(woning.algemeen.woonhuistype.toLowerCase().replaceAll('_', ' ')) : ''}</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(woning.financieel.overdracht.status) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Status: </th>`;
                        kenmerkenString += `<td>${capitalizeFirstLetter(woning.financieel.overdracht.status.toLowerCase().replaceAll('_', ' '))}</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(woning.financieel.overdracht.aanvaarding) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Aanvaarding: </th>`;
                        kenmerkenString += `<td>${capitalizeFirstLetter(woning.financieel.overdracht.aanvaarding.toLowerCase().replaceAll('_', ' '))}</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(woning.algemeen.bouwjaar) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Bouwjaar: </th>`;
                        kenmerkenString += `<td>${capitalizeFirstLetter(woning.algemeen.bouwjaar.toLowerCase().replaceAll('_', ' '))}</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(woning.algemeen.woonoppervlakte) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Woonoppervlakte: </th>`;
                        kenmerkenString += `<td>${woning.algemeen.woonoppervlakte} m&#178;</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(woning.algemeen.totaleKadestraleOppervlakte) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Perceeloppervlakte: </th>`;
                        kenmerkenString += `<td>${woning.algemeen.totaleKadestraleOppervlakte} m&#178;</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(woning.algemeen.inhoud) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Inhoud: </th>`;
                        kenmerkenString += `<td>${woning.algemeen.inhoud} m&#179;</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(totaalKamers > 0) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Aantal kamers: </th>`;
                        kenmerkenString += `<td><i class="fas fa-door-open mr-4"></i>${totaalKamers}</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(totaalSlaapkamers > 0) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Aantal slaapkamers: </th>`;
                        kenmerkenString += `<td><i class="fas fa-bed mr-4"></i>${totaalSlaapkamers}</td>`;
                    kenmerkenString += `</tr>`;
                }
                if(woning.algemeen.energieklasse) {
                    kenmerkenString += `<tr class="grid grid-cols-2 gap-6 justify-items-start py-2">`;
                        kenmerkenString += `<th>Energieklasse: </th>`;
                        kenmerkenString += `<td>${woning.algemeen.energieklasse.toUpperCase()}</td>`;
                    kenmerkenString += `</tr>`;
                }
                // &#179; hoge 3
                // &#178; hoge 2
                
                $('#kenmerken-table').html(kenmerkenString);
        };

        // Fetch data and initialize pagination on page load
        fetchWoningen();
    });
</script>