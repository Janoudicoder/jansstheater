<div class="container mx-auto">
    <div id="filters" class="flex flex-row flex-wrap items-end justify-between mb-6">
        <div id="filter-overzicht" class="flex items-end flex-row flex-wrap gap-4">
            <div class="filter-select form-group flex flex-col items-start gap-1">
                <label for="plaats" class="font-semibold">Plaats</label>
                <select name="plaats" id="plaats">
                    <option value="">Selecteer een plaats</option>
                </select>
            </div>
            <div class="filter-select form-group flex flex-col items-start gap-1">
                <label for="woonhuistype" class="font-semibold">Woonstijl</label>
                <select name="woonhuistype" id="woonhuistype">
                    <option value="">Selecteer een woonstijl</option>
                </select>
            </div>
        </div>
        <div id="filters-actief" class="flex items-end flex-row flex-wrap gap-2">
            <div id="aantal-woningen">
                <span id="aantal-woningen-nr"></span>
                <span>woning(en) gevonden</span>
            </div>
            <span class="zoekterm-vak zoekterm-hidden">|</span>
            <div id="zoektermen" class="zoekterm-vak zoekterm-hidden">
                <span>Uw zoekterm(en):</span>
                <strong id="zoektermen-waardes"></strong>
            </div>
            <span>|</span>
            <a href="<?php echo get_link();?>clear/" id="reset-zoekopdracht">
                <i class="fas fa-empty-set"></i>
                <span>reset zoekopdracht</span>
            </a>
        </div>
    </div>
    <div id="woning-overzicht" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">

    </div>
    <div id="pagination">

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const propertiesPerPage = 9; // Number of properties per page
        let properties = [];
        let propertiesAll = [];
        let woningStijlenAll = [];
        let plaatsenAll = [];
        let filterUrl = '';

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

                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }

                const data = await response.json();
                properties = data.resultaten;
                propertiesAll = data.resultaten;

                aantalWoningen();
                AllePlaatsen();
                AlleWoonstijlen();
                AlleZoektermen();
                initPagination();
                filterWoningen();
                setLocalFromUrl();
            } catch (error) {
                console.error('Error fetching data:', error);
                const container = document.getElementById('woning-overzicht');
                container.innerHTML = '<p>De woningen konden niet opgehaald worden, herlaad de pagina.</p>';
            }
        }

        function clearFilters() {
            const currentPath = window.location.pathname;
            if(currentPath.includes('/clear/')) {
                localStorage.clear();
                filterUrl = '';
                properties = propertiesAll;
            }
        }

        function set_updateLocal(naam, value) {
            localStorage.setItem(naam, value);
        }

        function aantalWoningen() {
            const aantalContainer = document.getElementById('aantal-woningen-nr');
            aantalContainer.innerHTML = properties.length;
        }

        function AllePlaatsen() {
            const plaatsContainer = document.getElementById('plaats');
            var placesOptions = '<option value="">Selecteer een plaats</option>';

            const places = [...new Set(propertiesAll.map(item => item.adres.plaats))];
            var opgeslagenPlaats = localStorage.getItem('plaats');

            places.forEach(plaats => {
                plaatsenAll.push(plaats.toLowerCase());

                var selected = '';
                if(opgeslagenPlaats != '' && opgeslagenPlaats === plaats.toLowerCase()) { selected = 'selected'; }

                placesOptions += `<option value="${plaats}" ${selected}>${capitalizeFirstLetter(plaats.toLowerCase())}</option>`;
            });
            plaatsContainer.innerHTML = placesOptions;
        }

        function AlleWoonstijlen() {
            const woonstijlContainer = document.getElementById('woonhuistype');
            var stylenOptions = '<option value="">Selecteer een woonstijl</option>';

            const stijlen = [...new Set(propertiesAll.map(item => item.algemeen.woonhuistype))];
            var opgeslagenStijl = localStorage.getItem('woonhuistype');

            stijlen.forEach(woonhuistype => {
                if(woonhuistype != 'null' && woonhuistype != null) {
                    woningStijlenAll.push(woonhuistype);

                    var selected = '';
                    if(opgeslagenStijl != '' && opgeslagenStijl === woonhuistype.toUpperCase()) { selected = 'selected'; }

                    stylenOptions += `<option value="${woonhuistype}" ${selected}>${capitalizeFirstLetter(woonhuistype.toLowerCase().replaceAll('_', ' '))}</option>`;
                }
            });
            woonstijlContainer.innerHTML = stylenOptions;
        }

        function AlleZoektermen() {
            const zoektermenContainer = document.getElementById('zoektermen-waardes');
            let storageItems = [];
            let storageItemsUrl = [];

            for (let i = 0; i < localStorage.length; i++) {
                let key = localStorage.key(i);
                if(localStorage.getItem(key) && (key ===  'plaats' || key === 'woonhuistype')) {
                    storageItems.push(capitalizeFirstLetter(localStorage.getItem(key).toLowerCase().replaceAll('_', ' ')));
                    storageItemsUrl.push(localStorage.getItem(key).toLowerCase());
                }
            }
            let result = storageItems.join(", ");
            filterUrl = storageItemsUrl.join("-");

            if(storageItems.length > 0) {
                document.querySelectorAll('.zoekterm-vak').forEach(function(element) {
                    element.classList.remove('zoekterm-hidden');
                });
            } else {
                document.querySelectorAll('.zoekterm-vak').forEach(function(element) {
                    element.classList.add('zoekterm-hidden');
                });
            }

            zoektermenContainer.innerHTML = result;
            initPagination();
        }

        function setLocalFromUrl() {
            const currentPath = window.location.pathname;

            let plaatsFound = plaatsenAll.find(plaats => currentPath.includes(plaats.toLowerCase()));
            if (plaatsFound) {
                localStorage.setItem('plaats', plaatsFound);
            }

            let woningStijlFound = woningStijlenAll.find(woningStijl => currentPath.toUpperCase().includes(woningStijl));
            if (woningStijlFound) {
                localStorage.setItem('woonhuistype', woningStijlFound);
            }

            AlleZoektermen();
            AllePlaatsen();
            AlleWoonstijlen();
            filterWoningen();
        }

        function filterWoningen() {
            if (filterUrl !== '') {
                var woningStijlFilter = localStorage.getItem('woonhuistype');
                var plaatsFilter = localStorage.getItem('plaats');

                properties = propertiesAll.filter(function(property) {
                    let matchesWoningStijl = true;
                    let matchesPlaats = true;

                    // Check for woonhuistype only if woningStijlFilter is provided
                    if (woningStijlFilter) {
                        // If woonhuistype is null or doesn't match the filter, set matchesWoningStijl to false
                        matchesWoningStijl = property.algemeen && property.algemeen.woonhuistype 
                            ? property.algemeen.woonhuistype === woningStijlFilter
                            : false;
                    }

                    // Check for plaats only if plaatsFilter is provided
                    if (plaatsFilter && property.adres && property.adres.plaats) {
                        matchesPlaats = property.adres.plaats.trim().toLowerCase() === plaatsFilter.trim().toLowerCase();
                    }

                    return matchesWoningStijl && matchesPlaats;
                });

                displayProperties();
                aantalWoningen();
                AlleZoektermen();
                initPagination();
            }
        }



        function displayProperties(page) {
            const start = (page - 1) * propertiesPerPage;
            const end = page * propertiesPerPage;

            const propertiesToDisplay = properties.slice(start, end);

            const container = document.getElementById('woning-overzicht');
            container.innerHTML = '';

            propertiesToDisplay.forEach(woning => {
                const woningCard = document.createElement('div');
                woningCard.id = `woning-${woning.id}`;
                woningCard.className = 'woning shadow-lg hover:shadow-2xl transition-all duration-300 bg-zinc-950';
                woningCard.dataset.woningId = `${woning.id}`;

                // Find the index of the 'HOOFDFOTO'
                const mediaArray = woning.media;
                const hoofdfotoIndex = mediaArray.findIndex(item => item.soort === "HOOFDFOTO");

                var huisnr = woning.adres.huisnummer.hoofdnummer;
                var huisnrToe = woning.adres.huisnummer.toevoeging || '';
                var straat = woning.adres.straat;
                var pc = woning.adres.postcode;
                var plaats = woning.adres.plaats;

                var straatStr = straat.replace(' ', '-');
                var woningURL = plaats.toLowerCase() + '-' + straatStr.toLowerCase() + '-' + huisnr + huisnrToe + '-' + woning.diversen.diversen.objectcode;

                let EURO = new Intl.NumberFormat('nl-NL', {
                    style: 'currency',
                    currency: 'EUR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                // Determine the status label based on the 'status' field
                let statusLabel = '';
                switch (woning.financieel.overdracht.status) {
                    case "Verkocht":
                    case "VERKOCHT":
                        statusLabel = '<span class="woning-labels verkocht bg-green-500 text-white px-3 py-1">Verkocht</span>';
                        break;
                    case "Verkocht onder voorbehoud":
                    case "VERKOCHT_ONDER_VOORBEHOUD":
                        statusLabel = '<span class="woning-labels verkocht bg-green-500 text-white px-3 py-1">Verkocht onder voorbehoud</span>';
                        break;
                    case "Onder optie":
                    case "ONDER_OPTIE":
                        statusLabel = '<span class="woning-labels verkocht bg-green-500 text-white px-3 py-1">Onder optie</span>';
                        break;
                    case "Verhuurd":
                    case "VERHUURD":
                        statusLabel = '<span class="woning-labels verkocht bg-green-500 text-white px-3 py-1">Verhuurd</span>';
                        break;
                    case "ONDER_BOD":
                        statusLabel = '<span class="woning-labels verkocht bg-green-500 text-white px-3 py-1">Onder bod</span>';
                        break;
                    default:
                        // Handle 'Nieuw' and 'Open House'
                        const nieuw = new Date(); // Replace with the actual logic to check 'nieuw'
                        const vrijgaveDatum = new Date(woning.marketing.publicatiedatum);
                        if (vrijgaveDatum > nieuw) {
                            statusLabel = '<span class="woning-labels nieuw">Nieuw</span>';
                        } else if (woning.marketing.website.openhuisdagen.length > 0) {
                            const openhuizen = woning.marketing.website.openhuisdagen;
                            const openhuizendatum = openhuizen.split('|');
                            const begin = openhuizendatum[0].split('-');
                            const eind = openhuizendatum[1].split('-');
                            const begintijd2 = openhuizendatum[0].split(' ')[1].slice(0, -3);
                            const eindtijd2 = openhuizendatum[1].split(' ')[1].slice(0, -3);
                            statusLabel = `
                                <span class="woning-labels openhuis bg-green-500 text-white px-3 py-1"><strong>OPEN HUIZEN DAG:</strong><br/> 
                                    ${begin[2]}-${begin[1]}-${begin[0]}<br/>${begintijd2} <strong>-</strong> ${eindtijd2} uur
                                </span>`;
                        } else {
                            statusLabel = '';
                        }
                        break;
                }

                // Construct the inner HTML
                woningCard.innerHTML = `
                    <a href="<?=$url;?><?php echo (get_meertaligheid() == true) ? '/'.get_taal() : ''; ?>/woning/${woningURL}" class="bg-zinc-950">
                        <div class="uitgelicht-inner aanbodblok-inner">
                            <div class="uitgelicht-image aspect-[4/3] relative">
                                <img src="${woning.media[hoofdfotoIndex].link}&width=500&height=330&resize=5&size=500x330" alt="${woning.media[hoofdfotoIndex].titel}" class="h-full w-full" loading="lazy" data-soort="${woning.media[hoofdfotoIndex].soort}" data-type="${woning.media[hoofdfotoIndex].mimetype}"/>
                                <span class="img-label aanbod absolute bottom-0 translate-y-1/2 right-4">
                                    ${statusLabel}
                                </span>
                                <span class="capitalize drop-shadow-xl h2 !text-white absolute bottom-0 translate-y-1/2 left-8">${plaats.toLowerCase()}</span>
                            </div>
                            <div class="woning_info_main pb-8 pt-4 px-8 text-white bg-zinc-950">
                                <h5 class="text-white mb-6">${straat} ${huisnr}${huisnrToe || ''}</h5>
                                <hr class="my-2" />
                                <div class="kenmerken !text-neutral-300 flex flex-wrap gap-4 items-center mb-8 text-base">
                                    ${woning.algemeen.totaleKadestraleOppervlakte ? `<span class="kenmerkje">Perceel: ${woning.algemeen.totaleKadestraleOppervlakte} m&sup2;</span>` : ''}
                                    ${woning.algemeen.aantalKamers ? `<span class="kenmerkje"><i class="fa fa-bed" aria-hidden="true"></i> Slaapkamers: ${woning.algemeen.aantalKamers}</span>` : ''}
                                    ${woning.algemeen.woonoppervlakte ? `<span class="kenmerkje">Woonopp: ${woning.algemeen.woonoppervlakte} m&sup2;</span>` : ''}
                                    ${woning.algemeen.bouwjaar ? `<span class="kenmerkje">Bouwjaar: ${woning.algemeen.bouwjaar}</span>` : ''}
                                </div>
                                <hr class="my-4" />
                                <div class="flex justify-between items-start">
                                    <div class="prijs flex flex-col gap-0">
                                        ${woning.financieel.overdracht.koopprijs !== null ? `<span class="prijssoort !text-neutral-300 text-xs">${woning.financieel.overdracht.koopprijsvoorvoegsel.toLowerCase()}</span><h6 class="!text-white">${EURO.format(woning.financieel.overdracht.koopprijs)}</h6>` : ''}
                                        ${woning.financieel.overdracht.huurprijs !== null ? `<span class="prijssoort !text-neutral-300 text-xs">${woning.financieel.overdracht.huurprijsvoorvoegsel.toLowerCase()}</span><h6 class="!text-white">${EURO.format(woning.financieel.overdracht.huurprijs)}</h6>` : ''}
                                        <span class="prijssoort capitalize !text-neutral-300 text-xs">${woning.financieel.overdracht.koopconditie ? woning.financieel.overdracht.koopconditie.replace('_', ' ').toLowerCase() : woning.financieel.overdracht.huurconditie.replace('_', ' ').toLowerCase()}</span>
                                    </div>
                                    <div class="btn custom-btn">meer info</div>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
                container.appendChild(woningCard);
            });

            if(propertiesToDisplay == '') {
                container.innerHTML = '<div class="h4 col-span-3 text-center mt-8">Er zijn geen woningen gevonden met deze zoektermen.</div>';
            }

            aantalWoningen();
        }

        function initPagination() {
            const currentPath = window.location.pathname;
            var basePath = '';
            var currentPage = 1;

            if (filterUrl !== '' && filterUrl.length !== 0) {
                if (currentPath.includes(filterUrl)) {
                    basePath = currentPath.substring(0, currentPath.indexOf(filterUrl)) + filterUrl;

                    const remainingPath = currentPath.substring(currentPath.indexOf(filterUrl) + filterUrl.length);

                    if (remainingPath.startsWith('/')) {
                        const pageSegment = remainingPath.substring(1); // Remove the leading slash
                        currentPage = pageSegment ? parseInt(pageSegment) : 1;
                    } else {
                        currentPage = 1;
                    }
                }
            } else if(currentPath.includes('/page/')) {
                basePath = currentPath.includes('/page/')
                    ? currentPath.substring(0, currentPath.indexOf('/page/'))
                    : currentPath;

                const pageSegment = currentPath.substring(currentPath.indexOf('/page/') + 6);
                currentPage = pageSegment ? parseInt(pageSegment) : 1;

                properties = propertiesAll;
            } else {
                properties = propertiesAll;
                currentPage = 1;
            }

            const totalProperties = properties.length;
            const totalPages = Math.ceil(totalProperties / propertiesPerPage);

            if(totalProperties > propertiesPerPage) {
                displayProperties(currentPage);
                setupPagination(totalPages, currentPage, basePath);
            } else {
                displayProperties(1);
                const paginationContainer = document.getElementById('pagination');
                paginationContainer.innerHTML = '';
            }
        }

        function setupPagination(totalPages, currentPage, basePath) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = ''; // Clear the container

            function scrollToTop() {
                window.scrollTo({
                    top: 300,
                    behavior: 'smooth' // Smooth scroll effect
                });
            }

            // Previous Button
            const prevButton = document.createElement('a');
            if(filterUrl != '' && filterUrl != '-') {
                prevButton.href = `<?php echo get_link(); ?>${filterUrl}/${currentPage - 1}/`;
            } else {
                prevButton.href = `<?php echo get_link(); ?>page/${currentPage - 1}/`;
            }

            prevButton.textContent = 'Vorige';
            prevButton.className = 'page-item prev';
            if (currentPage === 1) {
                prevButton.classList.add('disabled'); // Disable if on the first page
                prevButton.href = '#';
            }

            prevButton.addEventListener('click', (event) => {
                event.preventDefault();
                if (currentPage > 1) {
                    const pageNumber = currentPage - 1;
                    displayProperties(pageNumber);
                    setupPagination(totalPages, pageNumber, basePath);
                    window.history.pushState({}, '', prevButton.href);
                    scrollToTop();
                }
            });
            paginationContainer.appendChild(prevButton);

            // Page Links
            for (let page = 1; page <= totalPages; page++) {
                const link = document.createElement('a');
                if(filterUrl != '' && filterUrl != '-') {
                    link.href = `<?php echo get_link(); ?>${filterUrl}/${page}/`;
                } else {
                    link.href = `<?php echo get_link(); ?>page/${page}/`;
                }

                link.textContent = page;
                link.className = page === currentPage ? 'active page-item' : 'page-item';

                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const pageNumber = parseInt(event.target.textContent);
                    displayProperties(pageNumber);
                    setupPagination(totalPages, pageNumber, basePath);
                    window.history.pushState({}, '', link.href);
                    scrollToTop();
                });

                paginationContainer.appendChild(link);
            }

            // Next Button
            const nextButton = document.createElement('a');
            if(filterUrl != '' && filterUrl != '-') {
                nextButton.href = `<?php echo get_link(); ?>${filterUrl}/${currentPage + 1}/`;
            } else {
                nextButton.href = `<?php echo get_link(); ?>page/${currentPage + 1}/`;
            }

            nextButton.textContent = 'Volgende';
            nextButton.className = 'page-item next';
            if (currentPage === totalPages) {
                nextButton.classList.add('disabled'); // Disable if on the last page
                nextButton.href = '#';
            }

            nextButton.addEventListener('click', (event) => {
                event.preventDefault();
                if (currentPage < totalPages) {
                    const pageNumber = currentPage + 1;
                    displayProperties(pageNumber);
                    setupPagination(totalPages, pageNumber, basePath);
                    window.history.pushState({}, '', nextButton.href);
                    scrollToTop();
                }
            });
            paginationContainer.appendChild(nextButton);
        }

        // Fetch data and initialize pagination on page load
        fetchWoningen();
        clearFilters();

        // Handle back/forward navigation
        window.addEventListener('popstate', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = parseInt(urlParams.get('page')) || 1;
            displayProperties(currentPage);
        });

        const plaatsSelect = document.querySelector('select#plaats');
        plaatsSelect.addEventListener('change', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = parseInt(urlParams.get('page')) || 1;

            const currentPath = window.location.pathname;
            const basePath = currentPath.includes('/page/') 
                ? currentPath.substring(0, currentPath.indexOf('/page/'))
                : currentPath;

            const pageParamIndex = currentPath.indexOf('/page/') + 6;
            const currentPageNr = pageParamIndex > 5 ? parseInt(currentPath.substring(pageParamIndex)) : 1;

            const selectPlaatsVal = plaatsSelect.value.toLowerCase();

            displayProperties(currentPage);
            set_updateLocal('plaats', selectPlaatsVal);
            AlleZoektermen();
            filterWoningen();

            var plaatsURL = '';
            if(filterUrl != '' && filterUrl != '-') {
                if(properties.length > propertiesPerPage) {
                    plaatsURL = `<?php echo get_link(); ?>${filterUrl}/${currentPageNr}/`;
                } else {
                    plaatsURL = `<?php echo get_link(); ?>${filterUrl}/`;
                }
            } else {
                plaatsURL = `<?php echo get_link(); ?>page/${currentPageNr}/`;
            }
            window.history.pushState({}, '', plaatsURL);

        });

        const woningStijlSelect = document.querySelector('select#woonhuistype');
        woningStijlSelect.addEventListener('change', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = parseInt(urlParams.get('page')) || 1;

            const currentPath = window.location.pathname;
            const basePath = currentPath.includes('/page/') 
                ? currentPath.substring(0, currentPath.indexOf('/page/'))
                : currentPath;

            const pageParamIndex = currentPath.indexOf('/page/') + 6;
            const currentPageNr = pageParamIndex > 5 ? parseInt(currentPath.substring(pageParamIndex)) : 1;

            const selectWoningStijlVal = woningStijlSelect.value;

            displayProperties(currentPage);
            set_updateLocal('woonhuistype', selectWoningStijlVal);
            AlleZoektermen();
            filterWoningen();

            var woningStijlURL = '';
            if(filterUrl != '' && filterUrl != '-') {
                if(properties.length > propertiesPerPage) {
                    woningStijlURL = `<?php echo get_link(); ?>${filterUrl}/${currentPageNr}/`;
                } else {
                    woningStijlURL = `<?php echo get_link(); ?>${filterUrl}/`;
                }
            } else {
                woningStijlURL = `<?php echo get_link(); ?>page/${currentPageNr}/`;
            }
            window.history.pushState({}, '', woningStijlURL);
        });
    });



    // Call the function to fetch and display the woning data
    // fetchWoningen();

</script>